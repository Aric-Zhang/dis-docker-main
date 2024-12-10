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
        $link_id_2 = "search_type_username";
        $link_id_3 = "search_type_description";
        $link_id_4 = "search_type_id";
        $text_1 = "General Search";
        $text_2 = "Search Username";
        $text_3 = "Search Description";
        $text_4 = "Search ID";
        $type_1 = "general";
        $type_2 = "username";
        $type_3 = "description";
        $type_4 = "id";
        $placeholder_1 = "Type in police officer\'s username or modification description";
        $placeholder_2 = "Type in police officer\'s username";
        $placeholder_3 = "Type in modification description";
        $placeholder_4 = "Type in exact modification ID number";

        $search_input_id = "search_modification_input";

        $search_people_renderer->set_parameters(
            $invisible_input_id = "search_modification_type_input",
            $invisible_input_name = "search_modification_type",
            $search_opt_button_id = "dropdown-button-search-type-modification",
            $search_opt_button_text_id = "dropdown-button-search-type-modification-text",
            $dropdown_menu_item_type_placeholder_array = array(
                array('id'=>$link_id_1, 'text'=>$text_1, 'type'=>$type_1, 'func'=>javascript_replace_placeholder_string($link_id_1, $search_input_id, $placeholder_1)),
                array('id'=>$link_id_2, 'text'=>$text_2, 'type'=>$type_2, 'func'=>javascript_replace_placeholder_string($link_id_2, $search_input_id, $placeholder_2)),
                array('id'=>$link_id_3, 'text'=>$text_3, 'type'=>$type_3, 'func'=>javascript_replace_placeholder_string($link_id_3, $search_input_id, $placeholder_3)),
                array('id'=>$link_id_4, 'text'=>$text_4, 'type'=>$type_4, 'func'=>javascript_replace_placeholder_string($link_id_4, $search_input_id, $placeholder_4))
            ),
            $dropdown_menu_id = "dropdown-menu-search-type-modification",
            $dropdown_button_id = "dropdown-button-search-type-modification"
        );
        $search_input_name = "search_modification_text";
        $search_type_name = "search_modification_type";
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
                $table_headings_array = array("Modification_ID"=>"ID","Username"=>"Username","Modification_type"=>"Type","Modification_datetime"=>"Time","Modification_table"=>"Table","Modification_description"=>"Description");

                $id_column_name = 'Modification_ID';
                $conn = start_mysql_connection();
                $name_input = $_GET[$search_input_name];
                $name_cond = "%".$name_input."%";
                $search_type = $_GET[$search_type_name];
                if($search_type==$type_2) {  // username
                    $stmt = $conn->prepare("SELECT m.Modification_ID, u.Username, m.Modification_datetime, m.Modification_table, m.Modification_type, m.Modification_description, m.Modification_ref_ID 
                                                    FROM `Modification` m 
                                                    JOIN `User` u ON m.User_ID = u.ID 
                                                    WHERE u.Username LIKE ?
                                                    ORDER BY m.Modification_datetime DESC");
                    $stmt->bind_param("s", $name_cond);
                }
                else if($search_type==$type_3) { // description
                    $stmt = $conn->prepare("SELECT m.Modification_ID, u.Username, m.Modification_datetime, m.Modification_table, m.Modification_type, m.Modification_description, m.Modification_ref_ID 
                                                    FROM `Modification` m 
                                                    JOIN `User` u ON m.User_ID = u.ID 
                                                    WHERE m.Modification_description LIKE ?
                                                    ORDER BY m.Modification_datetime DESC");
                    $stmt->bind_param("s", $name_cond);
                }
                else if($search_type==$type_4) { //id
                    $stmt = $conn->prepare("SELECT m.Modification_ID, u.Username, m.Modification_datetime, m.Modification_table, m.Modification_type, m.Modification_description, m.Modification_ref_ID 
                                                    FROM `Modification` m 
                                                    JOIN `User` u ON m.User_ID = u.ID 
                                                    WHERE m.Modification_ID = ?
                                                    ORDER BY m.Modification_datetime DESC");
                    $stmt->bind_param("i", $name_input);
                }
                else{
                    $stmt = $conn->prepare("SELECT m.Modification_ID, u.Username, m.Modification_datetime, m.Modification_table, m.Modification_type, m.Modification_description, m.Modification_ref_ID 
                                                    FROM `Modification` m 
                                                    JOIN `User` u ON m.User_ID = u.ID 
                                                    WHERE u.Username LIKE ? OR m.Modification_description LIKE ?
                                                    ORDER BY m.Modification_datetime DESC");
                    $stmt->bind_param("ss", $name_cond, $name_cond);
                }
                $stmt->execute();
                $result = $stmt->get_result();

                $caption = "No results found";
                if($result->num_rows > 0){
                    $caption = "Found ".$result->num_rows." matched results";
                }
                $search_page_alias = "Search audit trail: ";
                $caption = $search_page_alias.$caption;
                //$caption = "Found ".$result->num_rows." matched results";
                $table_id = "search_res_table";

                start_search_table($table_id, $caption, $table_headings_array);

                while ($row = $result->fetch_assoc()) {
                    render_search_table_row($row, $id_column_name, $table_headings_array);
                    $row_id = $row[$id_column_name];
                    if(isset($_GET['expand_id']) && $_GET['expand_id']==$row_id){
                        $table_name = $row['Modification_table'];
                        $ref_id = $row['Modification_ref_ID'];
                        if($table_name == 'Vehicle' && $ref_id != null){
                            $colspan = count($row) + 1;
                            $nested_table_make_url_data = array(
                                "base_url"=>"search_people_page.php",
                                "search_type_name"=>"search_people_type",
                                "search_type_value"=>"id",
                                "search_text_name"=>"search_people_text",
                                "search_text_column_name"=>"People_ID",
                                "expand_id_name"=>"expand_id",
                                "id_column_name"=>"People_ID",
                            );
                            $stmt = $conn->prepare("SELECT * FROM People WHERE People_ID = (SELECT People_ID FROM Ownership WHERE Vehicle_ID = ?)");
                            $stmt->bind_param("i", $ref_id);
                            $stmt->execute();
                            $nested_result = $stmt->get_result();
                            $nested_table_caption = "Owner Information";

                            $nested_people_id = null;
                            # todo: use this to generate other methods
                            $nested_header_array = array("People_ID"=>"ID", "People_name"=>"Name", "People_address"=>"Address", "People_licence"=>"Driving Licence");
                            $no_result_placeholder = "No owner information found";

                            render_nested_table($nested_table_caption, $nested_result, $nested_header_array, $no_result_placeholder, $nested_table_make_url_data, $colspan);

                            $stmt = $conn->prepare("SELECT Incident_ID, Incident_Date, People_name, Incident_Report FROM `Incident` NATURAL JOIN `People` WHERE Vehicle_ID = ?");
                            $stmt->bind_param("i", $ref_id);
                            $stmt->execute();
                            $nested_result = $stmt->get_result();
                            $nested_table_caption = "Related Traffic Violation Incidents";

                            $nested_header_array = array("Incident_Date"=>"Incident Date","People_name"=>"Driver","Incident_Report"=>"Incident Report");
                            $nested_table_content_array = array();
                            $no_result_placeholder = "No traffic violation incident record found";

                            $nested_table_make_url_data = array(
                                "base_url"=>"search_incident_page.php",
                                "search_type_name"=>"search_incident_type",
                                "search_type_value"=>"id",
                                "search_text_name"=>"search_incident_text",
                                "search_text_column_name"=>"Incident_ID",
                                "expand_id_name"=>"expand_id",
                                "id_column_name"=>"Incident_ID",
                            );

                            render_nested_table($nested_table_caption, $nested_result, $nested_header_array, $no_result_placeholder, $nested_table_make_url_data, $colspan);
                        }
                        else if($table_name == 'People' && $ref_id != null) {
                            $colspan = count($row) + 1;
                            $stmt = $conn->prepare("SELECT * FROM People WHERE People_ID = ?");
                            $stmt->bind_param("i", $ref_id);
                            $stmt->execute();
                            $nested_result = $stmt->get_result();
                            if($nested_result->num_rows > 0) {
                                $people_row = $nested_result->fetch_assoc();

                                $nested_table_make_url_data = array(
                                    "base_url" => "search_people_page.php",
                                    "search_type_name" => "search_people_type",
                                    "search_type_value" => "id",
                                    "search_text_name" => "search_people_text",
                                    "search_text_column_name" => "People_ID",
                                    "expand_id_name" => "expand_id",
                                    "id_column_name" => "People_ID",
                                );
                                $nested_table_caption = "Basic Information";
                                $nested_people_url = make_nested_table_detail_url($people_row, $nested_table_make_url_data);
                                # todo: use this to generate other methods
                                $nested_header_array = array("People_name" => "Name", "People_address" => "Address", "People_licence" => "Driving Licence");

                                render_vertical_expand_row_nested_table($people_row, $nested_table_caption, $nested_header_array, $colspan);

                                $stmt = $conn->prepare("SELECT Incident_ID, Incident_Date, Vehicle_plate, Incident_Report FROM `Incident`
                                                            NATURAL JOIN People 
                                                            NATURAL JOIN Vehicle 
                                                            WHERE People_ID = ?");
                                $stmt->bind_param("i", $ref_id);
                                $stmt->execute();
                                $nested_result = $stmt->get_result();
                                $nested_table_caption = "Traffic Violation Incidents";

                                $nested_header_array = array("Incident_Date" => "Incident Date", "Vehicle_plate" => "Vehicle Plate", "Incident_Report" => "Incident Report");
                                $nested_table_content_array = array();
                                $no_result_placeholder = "No traffic violation incident record found";

                                $nested_table_make_url_data = array(
                                    "base_url" => "search_incident_page.php",
                                    "search_type_name" => "search_incident_type",
                                    "search_type_value" => "id",
                                    "search_text_name" => "search_incident_text",
                                    "search_text_column_name" => "Incident_ID",
                                    "expand_id_name" => "expand_id",
                                    "id_column_name" => "Incident_ID",
                                );

                                render_nested_table($nested_table_caption, $nested_result, $nested_header_array, $no_result_placeholder, $nested_table_make_url_data, $colspan);

                                $stmt = $conn->prepare("SELECT * FROM Vehicle WHERE Vehicle_ID IN (SELECT Vehicle_ID FROM Ownership WHERE People_ID = ?);");
                                $stmt->bind_param("i", $ref_id);
                                $stmt->execute();
                                $nested_result = $stmt->get_result();
                                $nested_table_caption = "Owned Vehicles";
                                $nested_header_array = array("Vehicle_ID" => "ID", "Vehicle_make" => "Make", "Vehicle_model" => "Model", "Vehicle_plate" => "Vehicle Plate", "Vehicle_colour" => "Color");
                                $no_result_placeholder = "No owned vehicle found";

                                $nested_table_make_url_data = array(
                                    "base_url" => "search_vehicle_page.php",
                                    "search_type_name" => "search_vehicle_type",
                                    "search_type_value" => "id",
                                    "search_text_name" => "search_vehicle_text",
                                    "search_text_column_name" => "Vehicle_ID",
                                    "expand_id_name" => "expand_id",
                                    "id_column_name" => "Vehicle_ID",
                                );

                                render_nested_table($nested_table_caption, $nested_result, $nested_header_array, $no_result_placeholder, $nested_table_make_url_data, $colspan);
                            }
                        }
                        else if(($table_name == 'Incident' || $table_name == 'Fines') && $ref_id != null){

                            if($table_name == 'Fines'){
                                $stmt = $conn->prepare("SELECT Incident_ID FROM Fines WHERE Fine_ID = ?;");
                                $stmt->bind_param("i", $ref_id);
                                $stmt->execute();
                                $nested_result = $stmt->get_result();
                                if($nested_result->num_rows > 0){
                                    $ref_id = $nested_result->fetch_assoc()["Incident_ID"];
                                }
                            }

                            $colspan = count($row) + 1;

                            $stmt = $conn->prepare("SELECT Fine_Amount, Fine_Points, Incident_Report FROM Incident i LEFT JOIN Fines f ON i.Incident_ID = f.Incident_ID WHERE i.Incident_ID = ?");
                            $stmt->bind_param("i", $ref_id);
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
                                    render_vertical_expand_row_nested_table($nested_row, $nested_table_caption,$nested_header_array,  $colspan);
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
                            $stmt->bind_param("i", $ref_id);
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
                            $stmt->bind_param("i", $ref_id);
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
