<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'navi_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'grid_container.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'dropdown_menu.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'search_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'search_result_table.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'toast.php';

session_start();
if (!isset($_SESSION[USERNAME])) {
    $login_relative_path = get_relative_path(get_relative_path_from_root(__FILE__), LOGIN_PAGE_PATH);
    header("Location: ".$login_relative_path);
    die();
}
?>
<?php
if(isset($_SESSION[AUTHORITY]) && $_SESSION[AUTHORITY] == AUTHORITY_ADMIN){
    foreach ($_POST as $key => $value) {
        //echo $key.": ".$value."<br>";
    }
    $incident_id_alias = 'Incident_ID';
    $id_column_name = 'Incident_ID';
    if(isset($_POST[$incident_id_alias])){
        $conn = start_mysql_connection();
        $incident_id = $_POST[$incident_id_alias];
        $nested_header_array = array("Incident_Report"=>"Report", "Fine_Amount"=>"Fine_Amount", "Fine_Points"=>"Fine_Points");
        $flipped_header_array = array_flip($nested_header_array);
        foreach ($_POST as $key => $value) {
            if(isset($flipped_header_array[$key]) && $value != ''){
                $col_name = $flipped_header_array[$key];
                if($col_name == "Fine_Amount" || $col_name == "Fine_Points"){
                    $max_fine = 0;
                    $max_points = 0;

                    $stmt = $conn->prepare("SELECT Offence_maxFine, Offence_maxPoints FROM Offence WHERE Offence_ID = (SELECT Offence_ID FROM Incident WHERE Incident_ID = ?)");
                    $stmt->bind_param("i", $incident_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if($result->num_rows > 0){
                        $row = $result->fetch_assoc();
                        $max_fine = $row['Offence_maxFine'];
                        $max_points = $row['Offence_maxPoints'];
                    }

                    $stmt = $conn->prepare("SELECT * FROM Fines WHERE Incident_ID = ?");
                    $stmt->bind_param("i", $incident_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if($result->num_rows > 0){
                        $row = $result->fetch_assoc();
                        $fine_id =  $row['Fine_ID'];

                        if($col_name == "Fine_Amount"){
                            $value = min($value, $max_fine);
                        }
                        if($col_name == "Fine_Points"){
                            $value = min($value, $max_points);
                        }
                        $stmt = $conn->prepare("UPDATE Fines SET $col_name = ? WHERE Incident_ID = ?");
                        $stmt->bind_param("ii", $value, $incident_id);
                        if($stmt->execute()){
                            //echo "Update successful";
                            record_update_fine($conn, $fine_id, $col_name, $value);
                        }
                    }
                    else{
                        $fine_amount = 0;
                        $fine_points = 0;
                        if($col_name == 'Fine_Amount'){
                            $fine_amount = min($value, $max_fine);
                            $value = $fine_amount;
                        }
                        else if($col_name == 'Fine_Points'){
                            $fine_points = min($value, $max_points);
                            $value = $fine_points;
                        }
                        $stmt = $conn->prepare("INSERT INTO Fines (Fine_Amount, Fine_Points, Incident_ID) VALUES (?, ?, ?)");
                        $stmt->bind_param("iii", $fine_amount,$fine_points, $incident_id);
                        if($stmt->execute()){
                            //echo "Insert successful";
                            $fine_id =  $conn->insert_id;
                            record_insert_fine($conn, $fine_id, $col_name, $value);
                        }

                    }
                }
                elseif($col_name == "Incident_Report"){
                    $stmt = $conn->prepare("UPDATE Incident SET $col_name = ? WHERE Incident_ID = ?");
                    $stmt->bind_param("si", $value, $incident_id);
                    $stmt->execute();
                }
            }
        }
        end_mysql_connection($conn);
    }
}
render_success_message_if_exist(fetch_alert_message());
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
<div class="main_page_wrapper">
    <div>
        <?php

        $search_people_renderer = new SearchOptionRenderer();

        $link_id_1 = "search_type_general";
        $link_id_2 = "search_type_people_name";
        $link_id_3 = "search_type_vehicle_plate";
        $link_id_4 = "search_type_id";
        $text_1 = "General Search";
        $text_2 = "Search People";
        $text_3 = "Search Vehicle Plate";
        $text_4 = "Search ID";
        $type_1 = "general";
        $type_2 = "people_name";
        $type_3 = "vehicle_plate";
        $type_4 = "id";
        $placeholder_1 = "Type in incident\'s driver name or vehicle plate";
        $placeholder_2 = "Type in driver\'s name";
        $placeholder_3 = "Type in vehicle\'s plate number";
        $placeholder_4 = "Type in incident\'s exact ID number";

        $search_input_id = "search_incident_input";

        $search_people_renderer->set_parameters(
            $invisible_input_id = "search_incident_type_input",
            $invisible_input_name = "search_incident_type",
            $search_opt_button_id = "dropdown-button-search-type-incident",
            $search_opt_button_text_id = "dropdown-button-search-type-incident-text",
            $dropdown_menu_item_type_placeholder_array = array(
                array('id'=>$link_id_1, 'text'=>$text_1, 'type'=>$type_1, 'func'=>javascript_replace_placeholder_string($link_id_1, $search_input_id, $placeholder_1)),
                array('id'=>$link_id_2, 'text'=>$text_2, 'type'=>$type_2, 'func'=>javascript_replace_placeholder_string($link_id_2, $search_input_id, $placeholder_2)),
                array('id'=>$link_id_3, 'text'=>$text_3, 'type'=>$type_3, 'func'=>javascript_replace_placeholder_string($link_id_3, $search_input_id, $placeholder_3)),
                array('id'=>$link_id_4, 'text'=>$text_4, 'type'=>$type_4, 'func'=>javascript_replace_placeholder_string($link_id_4, $search_input_id, $placeholder_4))
            ),
            $dropdown_menu_id = "dropdown-menu-search-type-incident",
            $dropdown_button_id = "dropdown-button-search-type-incident"
        );
        $search_input_name = "search_incident_text";
        $search_type_name = "search_incident_type";
        start_search_bar();
        $search_people_renderer->render();
        render_search_input_and_button($search_input_id, $search_input_name, $placeholder_1);
        end_search_bar();
        ?>
    </div>
    <div class="search_page_wrapper">
        <div class="search_res_table_container">
            <?php

            if(isset($_GET[$search_input_name]) && isset($_GET[$search_type_name]) && $_GET[$search_input_name]!=""){
                //Column name to shown name
                $table_headings_array = array("Incident_ID"=>"ID","Incident_Date"=>"Date", "Offence_description"=>"Offence","People_name"=>"Driver", "Vehicle_plate"=>"Vehicle Plate");
                $id_column_name = 'Incident_ID';
                $conn = start_mysql_connection();
                $name_input = $_GET[$search_input_name];
                $name_cond = "%".$name_input."%";
                $search_type = $_GET[$search_type_name];
                if($search_type==$type_2) {  // people name
                    $stmt = $conn->prepare("SELECT Incident_ID, Incident_Date, Offence_description, People_name, Vehicle_plate FROM `Incident` NATURAL JOIN People NATURAL JOIN Vehicle NATURAL JOIN Offence WHERE People_name LIKE ?");
                    $stmt->bind_param("s", $name_cond);
                }
                else if($search_type==$type_3) { // plate
                    $stmt = $conn->prepare("SELECT Incident_ID, Incident_Date, Offence_description, People_name, Vehicle_plate FROM `Incident` NATURAL JOIN People NATURAL JOIN Vehicle NATURAL JOIN Offence WHERE Vehicle_plate LIKE ?");
                    $stmt->bind_param("s", $name_cond);
                }
                else if($search_type==$type_4) { //id
                    $stmt = $conn->prepare("SELECT Incident_ID, Incident_Date, Offence_description, People_name, Vehicle_plate FROM `Incident` NATURAL JOIN People NATURAL JOIN Vehicle NATURAL JOIN Offence WHERE Incident_ID = ?");
                    $stmt->bind_param("i", $name_input);
                }
                else{
                    $stmt = $conn->prepare("SELECT Incident_ID, Incident_Date, Offence_description, People_name, Vehicle_plate FROM `Incident` NATURAL JOIN People NATURAL JOIN Vehicle NATURAL JOIN Offence WHERE People_name LIKE ? OR Vehicle_plate LIKE ?");
                    $stmt->bind_param("ss", $name_cond, $name_cond);
                }
                $stmt->execute();
                $result = $stmt->get_result();

                $caption = "No results found";
                if($result->num_rows > 0){
                    $caption = "Found ".$result->num_rows." matched results";
                }
                $search_page_alias = "Search incident: ";
                $caption = $search_page_alias.$caption;
                $table_id = "search_res_table";

                start_search_table($table_id, $caption, $table_headings_array);

                while ($row = $result->fetch_assoc()) {
                    render_search_table_row($row, $id_column_name, $table_headings_array);
                    $row_id = $row[$id_column_name];
                    if(isset($_GET['expand_id']) && $_GET['expand_id']==$row_id){
                        $colspan = count($row) + 1;

                        $stmt = $conn->prepare("SELECT Fine_Amount, Fine_Points, Incident_Report FROM Incident i LEFT JOIN Fines f ON i.Incident_ID = f.Incident_ID WHERE i.Incident_ID = ?");
                        $stmt->bind_param("i", $row_id);
                        $stmt->execute();
                        $nested_result = $stmt->get_result();


                        $nested_header_array = array("Incident_Report"=>"Report", "Fine_Amount"=>"Fine Amount", "Fine_Points"=>"Fine Points");
                        $nested_input_type_array = array("Fine_Amount"=>"number", "Fine_Points"=>"number");
                        $nested_table_caption = "Incident Details and Fines";
                        if ($nested_result->num_rows > 0){
                            while ($nested_row = $nested_result->fetch_assoc()){
                                $nested_table_id = gen_nested_table_id($nested_table_caption, $id_column_name, $row_id);
                                $caption_button_function_name = $nested_table_id.'_submit_all';
                                $caption_button_html = '';
                                if($_SESSION[AUTHORITY] == AUTHORITY_ADMIN){
                                    $caption_button_html = caption_right_button('Submit All Modifications',$caption_button_function_name.'()');
                                }
                                render_editable_vertical_expand_row_nested_table($nested_row, $nested_table_caption, $nested_header_array, $id_column_name, $row_id, $colspan, $nested_input_type_array, $caption_button_html);

                                $submit_all_in_table_doc = <<<EOT
                                    <script>
                                        function getFormsRecursively(element) {
                                            let forms = [];
                                            if (element.tagName.toLowerCase() === 'form') {
                                                forms.push(element);
                                            }
                                            for (let i = 0; i < element.children.length; i++) {
                                                forms = forms.concat(getFormsRecursively(element.children[i]));
                                            }
                                            return forms;
                                        }
                                                
                                        function $caption_button_function_name () {
                                            const nested_table_id = $nested_table_id ;
                                            const table = document.getElementById('$nested_table_id');
                                            const all_forms = getFormsRecursively(table)
                                            
                                            const combinedForm = document.createElement('form');
                                            combinedForm.method = 'POST';
                                            
                                            for(var i = 0; i < all_forms.length; i++) {
                                                var form = all_forms[i];
                                                for (let i = 0; i < form.children.length; i++) {
                                                    var children = form.children[i];
                                                    if(children.tagName.toLowerCase() === 'input') {
                                                        const hiddenField = document.createElement('input');
                                                        hiddenField.type = 'hidden';
                                                        hiddenField.name = children.name;
                                                        hiddenField.value = children.value;
                                                        combinedForm.appendChild(hiddenField);
                                                        console.log(children.name +': ' + children.value);
                                                    }
                                                }
                                            }
                                            document.body.appendChild(combinedForm);
                                            combinedForm.submit();
                                        }
                                    </script>
EOT;
                                echo $submit_all_in_table_doc;

                            }
                        }

                        $nested_table_make_url_data = array(
                            "base_url"=>"search_people_page.php",
                            "search_type_name"=>"search_people_type",
                            "search_type_value"=>"id",
                            "search_text_name"=>"search_people_text",
                            "search_text_column_name"=>"People_ID",
                            "expand_id_name"=>"expand_id",
                            "id_column_name"=>"People_ID",
                        );
                        $stmt = $conn->prepare("SELECT * FROM People WHERE People_ID = (SELECT People_ID FROM Incident WHERE Incident_ID = ?)");
                        $stmt->bind_param("i", $row_id);
                        $stmt->execute();
                        $nested_result = $stmt->get_result();
                        $nested_table_caption = "Driver Information";

                        $nested_people_id = null;
                        # todo: use this to generate other methods
                        $nested_header_array = array("People_ID"=>"ID", "People_name"=>"Name", "People_address"=>"Address", "People_licence"=>"Driving Licence");
                        $no_result_placeholder = "No owner information found";

                        render_nested_table($nested_table_caption, $nested_result, $nested_header_array, $no_result_placeholder, $nested_table_make_url_data, $colspan);
//
                        $stmt = $conn->prepare("SELECT * FROM Vehicle WHERE Vehicle_ID IN (SELECT Vehicle_ID FROM Incident WHERE Incident_ID = ?);");
                        $stmt->bind_param("i", $row_id);
                        $stmt->execute();
                        $nested_result = $stmt->get_result();
                        $nested_table_caption = "Related Vehicle";
                        $nested_header_array = array("Vehicle_ID"=>"ID","Vehicle_make"=>"Make","Vehicle_model"=>"Model","Vehicle_plate"=>"Vehicle Plate","Vehicle_colour"=>"Color");
                        $no_result_placeholder = "No owned vehicle found";

                        $nested_table_make_url_data = array(
                            "base_url"=>"search_vehicle_page.php",
                            "search_type_name"=>"search_vehicle_type",
                            "search_type_value"=>"id",
                            "search_text_name"=>"search_vehicle_text",
                            "search_text_column_name"=>"Vehicle_ID",
                            "expand_id_name"=>"expand_id",
                            "id_column_name"=>"Vehicle_ID",
                        );

                        render_nested_table($nested_table_caption, $nested_result, $nested_header_array, $no_result_placeholder, $nested_table_make_url_data, $colspan);

                    }
                }
                end_search_table_and_bind_expand_url($table_id);
                record_search($conn, 'Incident', $search_type, $name_input);
                $stmt->close();
                end_mysql_connection($conn);
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>