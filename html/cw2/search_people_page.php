<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'navi_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'grid_container.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'dropdown_menu.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'search_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'search_result_table.php';

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
        $link_id_4 = "search_type_id";
        $text_1 = "General Search";
        $text_2 = "Search Name";
        $text_3 = "Search Driving Licence";
        $text_4 = "Search ID";
        $type_1 = "general";
        $type_2 = "name";
        $type_3 = "driving_license";
        $type_4 = "id";
        $placeholder_1 = "Type in people\'s name or driving licence number";
        $placeholder_2 = "Type in people\'s name";
        $placeholder_3 = "Type in people\'s driving license number";
        $placeholder_4 = "Type in people\'s exact ID number";

        $search_input_id = "search_people_input";

        $search_people_renderer->set_parameters(
            $invisible_input_id = "search_people_type_input",
            $invisible_input_name = "search_people_type",
            $search_opt_button_id = "dropdown-button-search-type-people",
            $search_opt_button_text_id = "dropdown-button-search-type-people-text",
            $dropdown_menu_item_type_placeholder_array = array(
                array('id'=>$link_id_1, 'text'=>$text_1, 'type'=>$type_1, 'func'=>javascript_replace_placeholder_string($link_id_1, $search_input_id, $placeholder_1)),
                array('id'=>$link_id_2, 'text'=>$text_2, 'type'=>$type_2, 'func'=>javascript_replace_placeholder_string($link_id_2, $search_input_id, $placeholder_2)),
                array('id'=>$link_id_3, 'text'=>$text_3, 'type'=>$type_3, 'func'=>javascript_replace_placeholder_string($link_id_3, $search_input_id, $placeholder_3)),
                array('id'=>$link_id_4, 'text'=>$text_4, 'type'=>$type_4, 'func'=>javascript_replace_placeholder_string($link_id_4, $search_input_id, $placeholder_4))
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
                else if($search_type=="id"){
                    $stmt = $conn->prepare("SELECT * FROM People WHERE People_ID = ?");
                    $stmt->bind_param("i", $name_input);
                }
                else{
                    $stmt = $conn->prepare("SELECT * FROM People WHERE People_name LIKE ? OR people_licence LIKE ?");
                    $stmt->bind_param("ss", $name_cond, $name_cond);
                }
                $stmt->execute();
                $result = $stmt->get_result();

                $caption = "No results found";
                if($result->num_rows > 0){
                    $caption = "Found ".$result->num_rows." matched results";
                }
                $caption = "Found ".$result->num_rows." matched results";
                $table_id = "search_res_table";

                start_search_table($table_id, $caption, $table_headings_array);

                while ($row = $result->fetch_assoc()) {
                    render_search_table_row($row, $id_column_name, $table_headings_array);
                    $row_id = $row[$id_column_name];
                    if(isset($_GET['expand_id']) && $_GET['expand_id']==$row_id){

                        $nested_table_make_url_data = array(
                            "base_url"=>"search_people_page.php",
                            "search_type_name"=>"search_people_type",
                            "search_type_value"=>"id",
                            "search_text_name"=>"search_people_text",
                            "search_text_column_name"=>"People_ID",
                            "expand_id_name"=>"expand_id",
                            "id_column_name"=>"People_ID",
                        );
                        $nested_table_caption = "Basic Information";
                        $nested_people_url = make_nested_table_detail_url($row, $nested_table_make_url_data);
                        # todo: use this to generate other methods
                        $nested_header_array = array("People_name"=>"Name", "People_address"=>"Address", "People_licence"=>"Driving Licence");

                        render_vertical_expand_row_nested_table($row, $nested_table_caption, $nested_header_array);

                        $stmt = $conn->prepare("SELECT * FROM Incident WHERE People_ID = ?");
                        $stmt->bind_param("i", $row_id);
                        $stmt->execute();
                        $nested_result = $stmt->get_result();
                        $nested_table_caption = "Traffic Violation Incidents";

                        $nested_header_array = array("Incident_Date"=>"Incident Date","Incident_Report"=>"Incident Report");
                        $nested_table_content_array = array();
                        $no_result_placeholder = "No traffic violation incident record found";

                        render_nested_table($nested_table_caption, $nested_result, $nested_header_array, $no_result_placeholder);
                    }
                }
                end_search_table_and_bind_expand_url($table_id);
                $stmt->close();
                end_mysql_connection($conn);
            }

            ?>
        </div>
    </div>
</div>
</body>
</html>