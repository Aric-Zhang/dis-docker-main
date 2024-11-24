<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'navi_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'grid_container.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'dropdown_menu.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'search_bar.php';

session_start();
if (!isset($_SESSION[USERNAME])) {
    $login_relative_path = get_relative_path(get_relative_path_from_root(__FILE__), LOGIN_PAGE_PATH);
    header("Location: ".$login_relative_path);
    die();
}
?>
<html lang="en-GB">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIS Home Page</title>
    <style>
        @import "../css/dis_cw2_common.css";

        .search_res_table_container{
            width: 90%;
            border: 1px solid #CFD4D8;
            border-radius: 8px;
            overflow: hidden;
            background-color: #ffffff;
        }

        .search_res_table {
            width: 100%;
            border-collapse: collapse;
        }

        .search_res_table caption {
            padding: 0.5rem;
            font-weight: bold;
            background-color: #e9e9e9;
            border-bottom: 1px solid #eaeaea;
            text-align: start;
        }

        .search_res_table_nested{
            width: 100%;
            border-collapse: collapse;
            user-select: text;
        }

        .search_res_table th, .search_res_table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eaeaea;
            user-select: none;
        }

        .search_res_table th {
            background-color: #e9e9e9;
            color: #333333;
            font-weight: bold;
        }

        .search_res_table tr:nth-child(even) {
            background-color: #e9e9e9;
        }

        .search_res_table tr:nth-child(odd) {
            background-color: #f1f1f1;
        }


        .search_res_table_nested caption {
            padding: 0.5rem;
            font-weight: bold;
            background-color: #ffffff;
            text-align: center;
        }

        .search_res_table_nested tr:nth-child(odd), .search_res_table_nested tr:nth-child(even){
            background-color: #ffffff;
            user-select: text;
        }

        .search_res_table_nested td{
            background-color: #ffffff;
            user-select: text;
        }

        .search_res_table_nested tr:last-child td{
            border-bottom: none;
            background-color: #ffffff;
            user-select: text;
        }

        .search_res_table tr:hover {
            background-color: #f7f7f7;
        }

        .search_res_table .clickable:hover {
            cursor: pointer;
        }

        .zero-padding{
            padding: 0 !important;
        }

        .selected_row{
            background-color: #ffffff;
        }


    </style>
</head>
<body>
<?php
render_navi_bar(__FILE__);
?>
<?php
?>
<div class="main_page_wrapper">
    <div>
        <?php

        $search_people_renderer = new SearchOptionRenderer();

        $link_id_1 = "search_type_general";
        $link_id_2 = "search_type_name";
        $link_id_3 = "search_type_driving_license";
        $text_1 = "General Search";
        $text_2 = "Search Name";
        $text_3 = "Search Driving Licence";
        $type_1 = "general";
        $type_2 = "name";
        $type_3 = "driving_license";
        $placeholder_1 = "Type in people\'s name or driving licence number";
        $placeholder_2 = "Type in people\'s name";
        $placeholder_3 = "Type in people\'s driving license number";

        $search_input_id = "search_people_input";

        $search_people_renderer->set_parameters(
            $invisible_input_id = "search_people_type_input",
            $invisible_input_name = "search_people_type",
            $search_opt_button_id = "dropdown-button-search-type-people",
            $search_opt_button_text_id = "dropdown-button-search-type-people-text",
            $dropdown_menu_item_type_placeholder_array = array(
                array('id'=>$link_id_1, 'text'=>$text_1, 'type'=>$type_1, 'func'=>javascript_replace_placeholder_string($link_id_1, $search_input_id, $placeholder_1)),
                array('id'=>$link_id_2, 'text'=>$text_2, 'type'=>$type_2, 'func'=>javascript_replace_placeholder_string($link_id_2, $search_input_id, $placeholder_2)),
                array('id'=>$link_id_3, 'text'=>$text_3, 'type'=>$type_3, 'func'=>javascript_replace_placeholder_string($link_id_3, $search_input_id, $placeholder_3))
            ),
            $dropdown_menu_id = "dropdown-menu-search-type-people",
            $dropdown_button_id = "dropdown-button-search-type-people"
        );
        start_search_bar();
        $search_people_renderer->render();
        render_search_input_and_button($search_input_id, "search_people_text", "Type in people's name or driving licence number");
        end_search_bar();
        ?>
    </div>
    <div class="search_page_wrapper">
        <hr>
        <div class="search_res_table_container">
            <?php
            function start_nested_table($selected_class_string, $nested_table_caption){
                $start_nested_table_doc = <<<EOT
                                <tr class='$selected_class_string'>
                                    <td colspan='5' style='user-select: text'  class='zero-padding'> 
                                    <table class='search_res_table_nested' style='user-select: text'>                
                                        <caption>$nested_table_caption</caption>
                                            <tbody>
EOT;
                echo $start_nested_table_doc;
            }
            function end_nested_table(){
                $end_nested_table_doc = <<<EOT
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
EOT;
                echo $end_nested_table_doc;
            }

            if(isset($_GET["search_people_text"]) && isset($_GET["search_people_type"]) && $_GET["search_people_text"]!=""){
                //Column name to shown name
                $table_headings_array = array("People_ID"=>"ID","People_name"=>"Name","People_address"=>"Address","People_licence"=>"Driving Licence",);
                $id_column_name = 'People_ID';
                $conn = start_mysql_connection();
                $name_input = $_GET["search_people_text"];
                $name_cond = "%".$name_input."%";
                $search_type = $_GET["search_people_type"];
                if($search_type=="name") {
                    $stmt = $conn->prepare("SELECT * FROM People WHERE People_name LIKE ?");
                    $stmt->bind_param("s", $name_cond);
                }
                else if($search_type=="driving_license") {
                    $stmt = $conn->prepare("SELECT * FROM People WHERE People_licence LIKE ?");
                    $stmt->bind_param("s", $name_cond);
                }
                else{
                    $stmt = $conn->prepare("SELECT * FROM People WHERE People_name LIKE ? OR people_licence LIKE ?");
                    $stmt->bind_param("ss", $name_cond, $name_cond);
                }
                $stmt->execute();
                $result = $stmt->get_result();

                if($result->num_rows < 1){
                    echo "No results found";
                }
                else{
                    $caption = "Found ".$result->num_rows." matched results";
                    $table_id = "search_res_table";
                    $table_headings_string = "";
                    foreach ($table_headings_array as $heading_name=>$heading_alias) {
                        $table_headings_string .= "<th>".$heading_alias."</th>";
                    }
                    $table_headings_string.="<th></th>";
                    $table_start = <<<EOT
            <table class="search_res_table" id="$table_id">
                <caption>$caption</caption>
                <thead>
                <tr>
                    $table_headings_string
                </tr>
                </thead>
                <tbody>
EOT;
                    $table_end = <<<EOT
                </tbody>
            </table>
EOT;
                    $bind_tr_url_script_doc = <<<EOT
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('$table_id');
            table.addEventListener('click', function(event) {
                let target = event.target;

                if (target.tagName === 'TD') {
                    target = target.parentNode;
                }

                if (target.tagName === 'TR') {
                    const url = target.getAttribute('data-url');
                    if (url) {
                        window.location.href = url;
                    }
                }
            });
        });
    </script>
EOT;

                    echo $table_start;


                    while ($row = $result->fetch_assoc()) {
                        $row_id = $row[$id_column_name];
                        $row_query = $_GET;
                        $row_query['expand_id'] = $row_id;
                        $query_string = http_build_query($row_query);
                        $url = getBaseUrl().'?'.$query_string;
                        $selected = (isset($_GET['expand_id']) && $_GET['expand_id']==$row_id);
                        $selected_class = "";
                        if($selected){
                            $selected_class = "selected_row";
                        }
                        echo "<tr data-url='$url' class='clickable ".$selected_class."' >";
                        foreach ($table_headings_array as $heading_name=>$heading_alias) {
                            echo "<td class='".$selected_class."'>".$row[$heading_name]."</td>";
                        }
                        echo "<td class='".$selected_class."' style='position:relative'> <span class='arrow-down' style='right: 0.75rem; top: 1rem; pointer-events: none'></span> </td>";
                        echo "</tr>";
                        if(isset($_GET['expand_id']) && $_GET['expand_id']==$row_id){

                            $people_name = $row["People_name"];
                            $people_address = $row["People_address"];
                            $people_driving_licence = $row["People_licence"];

                            $nested_table_caption = "Basic Information";
                            $start_nested_table_doc = <<<EOT
                                <tr class='$selected_class'>
                                    <td colspan='5' style='user-select: text'  class='zero-padding'> 
                                    <table class='search_res_table_nested' style='user-select: text'>                
                                        <caption>$nested_table_caption</caption>
                                            <tbody>
EOT;
                            $end_nested_table_doc = <<<EOT
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
EOT;

                            $nested_table_doc = <<<EOT
                                <tr class='$selected_class'>
                                    <td colspan='5' style='user-select: text'  class='zero-padding'> 
                                    <table class='search_res_table_nested' style='user-select: text'>                
                                        <caption>$nested_table_caption</caption>
                                            <tbody>
                                            <tr>
                                                <td>Name</td>
                                                <td>$people_name</td>
                                                <td style='width: 5rem'>Details</td>
                                            </tr>
                                            <tr>
                                                <td>Address</td>
                                                <td>$people_address</td>
                                                <td style='width: 5rem'>Details</td>
                                            </tr>
                                            <tr>
                                                <td>Driving Licence</td>
                                                <td>$people_driving_licence</td>
                                                <td style='width: 5rem'>Details</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td></tr>
EOT;
                            echo $nested_table_doc;

                            $stmt = $conn->prepare("SELECT * FROM Incident WHERE People_ID = ?");
                            $stmt->bind_param("i", $row_id);
                            $stmt->execute();
                            $nested_result = $stmt->get_result();
                            $nested_table_caption = "Traffic Violation Incidents";

                            $nested_header_array = array("Incident_Date"=>"Incident Date","Incident_Report"=>"Incident Report");
                            $nested_table_content_array = array();
                            

                            start_nested_table($selected_class, $nested_table_caption);

                            while ($nested_row = $nested_result->fetch_assoc()){
                                $nested_row_array = array();
                                foreach ($nested_header_array as $nested_header_name=>$nested_header_alias) {
                                    array_push($nested_row_array, $nested_row[$nested_header_name]);
                                }
                                array_push($nested_table_content_array, $nested_row_array);
                            }
                            foreach ($nested_table_content_array as $nested_row_array) {
                                echo "<tr>";
                                foreach ($nested_row_array as $nested_td) {
                                    echo "<td>$nested_td</td>";
                                }
                                echo "<td style='width: 5rem'>Details</td>";
                                echo "</tr>";
                            }
                            if($nested_result->num_rows < 1){
                                echo "<tr><td>No traffic violation incident record found<td></tr>";
                            }
                            end_nested_table();


                        }
                    }
                    echo $table_end;
                    echo $bind_tr_url_script_doc;
                }
                $stmt->close();
                end_mysql_connection($conn);
            }

            ?>
        </div>
    </div>


</div>
</body>
</html>