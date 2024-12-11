<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'navi_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'grid_container.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'modal.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'search_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'form_elements.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'toast.php';

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
    <title>Report Incident</title>
    <style>
        @import "../css/dis_cw2_common.css";

    </style>
</head>
<body>
<?php
render_navi_bar(__FILE__);
?>
<div class="main_page_wrapper">
    <div class = "add_form_container">
        <h2>Report Incident</h2>
        <?php
        $modal_id = "myModal";
        $button_id_modal_close = "close_modal_button";

        start_modal($modal_id , $button_id_modal_close, "Search Owner");

        $search_people_renderer = new SearchOptionRenderer();
        $search_people_renderer->dropdown_button_min_width = '7rem';

        $link_id_1 = "search_type_general";
        $link_id_2 = "search_type_name";
        $link_id_3 = "search_type_driving_license";
        $link_id_4 = "search_type_id";
        $text_1 = "General";
        $text_2 = "Name";
        $text_3 = "Licence";
        $text_4 = "ID";
        $type_1 = "general";
        $type_2 = "name";
        $type_3 = "driving_license";
        $type_4 = "id";
        $placeholder_1 = "";
        $placeholder_2 = "";
        $placeholder_3 = "";
        $placeholder_4 = "";

        $search_input_id = "search_people_input";
        $search_input_name = "search_people_text";
        $search_button_id = "search_button";
        $invisible_input_id = "search_people_type_input";
        $invisible_input_name = "search_people_type";

        $search_people_renderer->set_parameters(
            $invisible_input_id = $invisible_input_id,
            $invisible_input_name = $invisible_input_name,
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
        start_search_bar($action="");
        $search_people_renderer->render();
        render_search_input_and_button($search_input_id, $search_input_name, "",$search_button_id);
        end_search_bar();

        $driver_select_button_id = 'driver_select_button';
        $driver_select_input_id = 'driver_select_input';
        $target_php_file_path = 'search_people.php';
        $col_id_name = 'People_ID';
        $col_name_name = 'People_name';
        $col_licence_name = 'People_licence';
        $result_container_id = 'results';
        echo "<div id=\"$result_container_id\" class=\"scrollable-modal-container\"> </div>";
        function bind_search_ajax($modal_id,
                                  $search_button_id,
                                  $search_input_id,
                                  $invisible_input_id,
                                  $result_container_id,
                                  $invisible_input_name,
                                  $search_input_name,
                                  $target_select_input_id,
                                  $target_select_button_id,
                                  $target_php_file_path,
                                  $col_id_name,
                                  $col_names_array){
            //$col_names_array = array($col_name_name, $col_licence_name);
            $js_col_names_array_string = "new Array(";
            foreach ($col_names_array as $col_name){
                $js_col_names_array_string .= "'".$col_name."',";
            }
            $js_col_names_array_string = rtrim($js_col_names_array_string,",");
            $js_col_names_array_string.=")";

            $bind_search_ajax_doc = <<<EOT
                <script>
                function reset_${modal_id}_searching_results(){
                    const resultsContainer = document.getElementById('$result_container_id');
                    resultsContainer.innerHTML = '';
                }
                
        document.getElementById('$search_button_id').addEventListener('click', function(event) {
            event.preventDefault();
            const search_text_input = document.getElementById('$search_input_id');
            const search_type_input = document.getElementById('$invisible_input_id');
            const resultsContainer = document.getElementById('$result_container_id');
            const prefix = '$modal_id.'
            const col_names_array = $js_col_names_array_string ;
            
            const data = {
                $invisible_input_name: search_type_input.value,
                $search_input_name: search_text_input.value
            };
            
            fetch('$target_php_file_path', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    
                } else if (data.results.length === 0) {
                    
                } else {
                    reset_${modal_id}_searching_results();
                    data.results.forEach(result => {
                        const resultItem = document.createElement('div');
                        resultItem.className = 'result-item';
                        resultItem.innerHTML = `
                        <div>
                            <input name="id" value=\${result.$col_id_name} style="display:none" id='\${prefix}\${result.$col_id_name}.id_input' />
                            <button class="btn btn_modal_search_result" id='\${prefix}\${result.$col_id_name}.confirm_button'>
                            </button>
                        </div>
                        `;
                        resultsContainer.appendChild(resultItem);
                        var button = document.getElementById(`\${prefix}\${result.$col_id_name}.confirm_button`)
                        for(var i = 0; i<col_names_array.length; i++ ){
                            var col_name = col_names_array[i];
                            button.innerHTML += `                                
                                <span style = "flex: 0 0 50%; text-align: left;">
                                \${result[col_name]}
                                </span>`
                        }
                        
                        const confirm_button = document.getElementById(`\${prefix}\${result.$col_id_name}.confirm_button`)
                        confirm_button.onclick = function() {
                            const id_input = document.getElementById(`\${prefix}\${result.$col_id_name}.id_input`)
                            const target_invisible_input = document.getElementById('$target_select_input_id');
                            const owner_select_button = document.getElementById('$target_select_button_id');
                            target_invisible_input.value = id_input.value;
                            owner_select_button.textContent = confirm_button.textContent;
                            reset_${modal_id}_searching_results();
                            const modal = document.getElementById("$modal_id");
                            modal.style.display = "none";
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
            
        });
    </script>
EOT;
            echo $bind_search_ajax_doc;
        }

        $col_name_array = array($col_name_name, $col_licence_name);

        bind_search_ajax($modal_id,
            $search_button_id,
            $search_input_id,
            $invisible_input_id,
            $result_container_id,
            $invisible_input_name,
            $search_input_name,
            $driver_select_input_id,
            $driver_select_button_id,
            $target_php_file_path,
            $col_id_name,
            $col_name_array );

        end_modal($modal_id, $button_id_modal_close);
        ?>


        <?php
        $vehicle_modal_id = "search_vehicle_modal";
        $button_id_modal_close = "close_vehicle_modal_button";

        start_modal($vehicle_modal_id , $button_id_modal_close, "Search Vehicle");
        $search_people_renderer = new SearchOptionRenderer();

        $link_id_1 = "search_type_general";
        $link_id_2 = "search_type_brand";
        $link_id_3 = "search_type_plate";
        $link_id_4 = "search_type_id";
        $text_1 = "General Search";
        $text_2 = "Search Brand";
        $text_3 = "Search Plate";
        $text_4 = "Search ID";
        $type_1 = "general";
        $type_2 = "brand";
        $type_3 = "plate";
        $type_4 = "id";
        $placeholder_1 = "Type in vehicle\'s brand or plate";
        $placeholder_2 = "Type in vehicle\'s brand";
        $placeholder_3 = "Type in vehicle\'s driving license number";
        $placeholder_4 = "Type in vehicle\'s exact ID number";

        $vehicle_select_button_id = 'vehicle_select_button';
        $vehicle_select_input_id = 'vehicle_select_input';

        $search_input_id = "search_vehicle_input";
        $search_input_name = "search_vehicle_text";
        $search_button_id = "search_vehicle_button";
        $invisible_input_id = "search_vehicle_type_input";
        $invisible_input_name = "search_vehicle_type";

        $search_people_renderer->set_parameters(
            $invisible_input_id = $invisible_input_id,
            $invisible_input_name = $invisible_input_name,
            $search_opt_button_id = "dropdown-button-search-type-vehicle",
            $search_opt_button_text_id = "dropdown-button-search-type-vehicle-text",
            $dropdown_menu_item_type_placeholder_array = array(
                array('id'=>$link_id_1, 'text'=>$text_1, 'type'=>$type_1, 'func'=>javascript_replace_placeholder_string($link_id_1, $search_input_id, $placeholder_1)),
                array('id'=>$link_id_2, 'text'=>$text_2, 'type'=>$type_2, 'func'=>javascript_replace_placeholder_string($link_id_2, $search_input_id, $placeholder_2)),
                array('id'=>$link_id_3, 'text'=>$text_3, 'type'=>$type_3, 'func'=>javascript_replace_placeholder_string($link_id_3, $search_input_id, $placeholder_3)),
                array('id'=>$link_id_4, 'text'=>$text_4, 'type'=>$type_4, 'func'=>javascript_replace_placeholder_string($link_id_4, $search_input_id, $placeholder_4))
            ),
            $dropdown_menu_id = "dropdown-menu-search-type-vehicle",
            $dropdown_button_id = "dropdown-button-search-type-vehicle"
        );
        $search_input_name = "search_vehicle_text";
        $search_type_name = "search_vehicle_type";
        start_search_bar();
        $search_people_renderer->render();
        render_search_input_and_button($search_input_id, $search_input_name, $placeholder_1, $search_button_id);
        end_search_bar();

        $driver_select_button_id = 'vehicle_select_button';
        $driver_select_input_id = 'vehicle_select_input';
        $target_php_file_path = 'search_vehicle.php';
        $col_id_name = 'Vehicle_ID';
        $col_make_name = 'Vehicle_make';
        $col_name_name = 'Vehicle_model';
        $col_licence_name = 'Vehicle_plate';
        $result_container_id = 'vehicle_results';
        echo "<div id=\"$result_container_id\" class=\"scrollable-modal-container\"> </div>";

        $col_name_array = array($col_make_name, $col_name_name, $col_licence_name);

        bind_search_ajax($vehicle_modal_id,
            $search_button_id,
            $search_input_id,
            $invisible_input_id,
            $result_container_id,
            $invisible_input_name,
            $search_input_name,
            $driver_select_input_id,
            $driver_select_button_id,
            $target_php_file_path,
            $col_id_name,
            $col_name_array );

        end_modal($vehicle_modal_id, $button_id_modal_close);
        ?>


        <?php
        $offence_modal_id = "search_offence_modal";
        $button_id_modal_close = "close_offence_modal_button";

        start_modal($offence_modal_id , $button_id_modal_close, "Search Offence");
        $search_people_renderer = new SearchOptionRenderer();

        $link_id_1 = "search_type_general";
        $link_id_2 = "search_type_description";
        $link_id_4 = "search_type_id";
        $text_1 = "General Search";
        $text_2 = "Search Description";
        $text_4 = "Search ID";
        $type_1 = "general";
        $type_2 = "description";
        $type_4 = "id";
        $placeholder_1 = "Type in offence related information";
        $placeholder_2 = "Type in offence\'s description";
        $placeholder_4 = "Type in offence\'s exact ID number";

        $offence_select_button_id = 'offence_select_button';
        $offence_select_input_id = 'offence_select_input';

        $search_input_id = "search_offence_input";
        $search_input_name = "search_offence_text";
        $search_button_id = "search_offence_button";
        $invisible_input_id = "search_offence_type_input";
        $invisible_input_name = "search_offence_type";

        $search_people_renderer->set_parameters(
            $invisible_input_id = $invisible_input_id,
            $invisible_input_name = $invisible_input_name,
            $search_opt_button_id = "dropdown-button-search-type-offence",
            $search_opt_button_text_id = "dropdown-button-search-type-offence-text",
            $dropdown_menu_item_type_placeholder_array = array(
                array('id'=>$link_id_1, 'text'=>$text_1, 'type'=>$type_1, 'func'=>javascript_replace_placeholder_string($link_id_1, $search_input_id, $placeholder_1)),
                array('id'=>$link_id_2, 'text'=>$text_2, 'type'=>$type_2, 'func'=>javascript_replace_placeholder_string($link_id_2, $search_input_id, $placeholder_2)),
                array('id'=>$link_id_4, 'text'=>$text_4, 'type'=>$type_4, 'func'=>javascript_replace_placeholder_string($link_id_4, $search_input_id, $placeholder_4))
            ),
            $dropdown_menu_id = "dropdown-menu-search-type-offence",
            $dropdown_button_id = "dropdown-button-search-type-offence"
        );
        $search_input_name = "search_offence_text";
        $search_type_name = "search_offence_type";
        start_search_bar();
        $search_people_renderer->render();
        render_search_input_and_button($search_input_id, $search_input_name, $placeholder_1, $search_button_id);
        end_search_bar();

        $driver_select_button_id = 'offence_select_button';
        $driver_select_input_id = 'offence_select_input';
        $target_php_file_path = 'search_offence.php';
        $col_id_name = 'Offence_ID';
        $col_name_name = 'Offence_description';
        $result_container_id = 'offence_results';
        echo "<div id=\"$result_container_id\" class=\"scrollable-modal-container\"> </div>";

        $col_name_array = array($col_name_name);

        bind_search_ajax($offence_modal_id,
            $search_button_id,
            $search_input_id,
            $invisible_input_id,
            $result_container_id,
            $invisible_input_name,
            $search_input_name,
            $driver_select_input_id,
            $driver_select_button_id,
            $target_php_file_path,
            $col_id_name,
            $col_name_array );

        end_modal($offence_modal_id, $button_id_modal_close);
        ?>
        <script src="../js/dynamic_form_elements.js">
        </script>
        <form id="dynamic-form" class="login-form" method="post">
            <p style="color: dimgrey;">Fields marked with an <span style="color: red;">*</span> are required</p>
            <div style="margin: 1rem"></div>
<?php



$date_wrapper_id = 'date_wrapper';
$input_name = 'date';

$driver_input_option_name = 'driver_input_option';

$driver_select_wrapper_id = 'driver_select_wrapper';
$driver_select_button_id = 'driver_select_button';
$driver_select_input_id = 'driver_select_input';

$driver_name_wrapper_id = 'driver_name_wrapper';
$driver_name_input_name = 'driver_name';
$driver_address_wrapper_id = 'driver_address_wrapper';
$driver_address_input_name = 'driver_address';
$driver_licence_wrapper_id = 'driver_licence_wrapper';
$driver_licence_input_name = 'driver_licence';
$new_driver_space_1_id = 'new_driver_space_1';
$new_driver_space_2_id = 'new_driver_space_2';

$form_id = 'dynamic-form';
$new_group_id_array = array($driver_name_wrapper_id, $driver_address_wrapper_id, $driver_licence_wrapper_id, $new_driver_space_1_id, $new_driver_space_2_id);

render_form_input($date_wrapper_id, '*', 'Incident Date', $input_name, 'date', 'date_checkmark');
render_space_html();

render_owner_input_radio($driver_input_option_name, 'Driver Input Option');
render_space_html();
render_form_search_button('Driver Select',false, 'driver','driver_checkmark',$driver_select_wrapper_id, $driver_select_button_id,$driver_select_input_id,"Select Driver");
render_form_input($driver_name_wrapper_id, '*', 'Driver Name', $driver_name_input_name, 'text', 'driver_name_checkmark','Enter driver\'s name');
render_space_html($new_driver_space_1_id);
render_form_input($driver_address_wrapper_id, '', 'Driver Address', $driver_address_input_name, 'text', 'driver_address_checkmark','Enter driver\'s address');
render_space_html($new_driver_space_2_id);
render_form_input($driver_licence_wrapper_id, '*', 'Driver Licence', $driver_licence_input_name, 'text', 'driver_licence_checkmark','Enter driver\'s driving licence');
render_space_html();
bind_select_or_new_group($form_id, $new_group_id_array, $driver_select_wrapper_id, $driver_input_option_name);
bind_modal_open_button($modal_id, $driver_select_button_id);

$vehicle_input_option_name = 'vehicle_input_option';

$vehicle_select_wrapper_id = 'vehicle_select_wrapper';
$vehicle_select_button_id = 'vehicle_select_button';
$vehicle_select_input_id = 'vehicle_select_input';

$vehicle_model_wrapper_id = 'vehicle_model_wrapper';
$vehicle_model_input_name = 'vehicle_model';
$vehicle_make_wrapper_id = 'vehicle_make_wrapper';
$vehicle_make_input_name = 'vehicle_make';
$vehicle_plate_wrapper_id = 'vehicle_plate_wrapper';
$vehicle_plate_input_name = 'vehicle_plate';
$vehicle_color_wrapper_id = 'vehicle_color_wrapper';
$vehicle_color_input_name = 'vehicle_color';
$new_vehicle_space_1_id = 'new_vehicle_space_1';
$new_vehicle_space_2_id = 'new_vehicle_space_2';
$new_vehicle_space_3_id = 'new_vehicle_space_3';

$new_group_id_array = array($vehicle_model_wrapper_id, $vehicle_make_wrapper_id, $vehicle_plate_wrapper_id, $vehicle_color_wrapper_id, $new_vehicle_space_1_id, $new_vehicle_space_2_id, $new_vehicle_space_3_id);

render_owner_input_radio($vehicle_input_option_name, 'Vehicle Input Option');
render_space_html();
render_form_search_button('Vehicle Select',false, 'vehicle','vehicle_checkmark',$vehicle_select_wrapper_id, $vehicle_select_button_id, $vehicle_select_input_id,"Select Vehicle");
render_form_input($vehicle_model_wrapper_id, '', 'Vehicle Model', $vehicle_model_input_name, 'text', 'vehicle_model_checkmark','Enter vehicle\'s model');
render_space_html($new_vehicle_space_1_id);
render_form_input($vehicle_make_wrapper_id, '', 'Vehicle Make', $vehicle_make_input_name, 'text', 'vehicle_make_checkmark','Enter vehicle\'s make');
render_space_html($new_vehicle_space_2_id);
render_form_input($vehicle_plate_wrapper_id, '*', 'Vehicle Plate', $vehicle_plate_input_name, 'text', 'vehicle_plate_checkmark','Enter vehicle\'s plate');
render_space_html($new_vehicle_space_3_id);
render_form_input($vehicle_color_wrapper_id, '', 'Vehicle Color', $vehicle_color_input_name, 'text', 'vehicle_plate_checkmark','Enter vehicle\'s color');
render_space_html();
bind_select_or_new_group($form_id, $new_group_id_array, $vehicle_select_wrapper_id, $vehicle_input_option_name);
bind_modal_open_button($vehicle_modal_id, $vehicle_select_button_id);

$offence_select_wrapper_id = "offence_select_wrapper";
$offence_select_button_id = 'offence_select_button';
$offence_select_input_id = 'offence_select_input';

render_form_search_button('Offence Select',true, 'offence','offence_checkmark',$offence_select_wrapper_id, $offence_select_button_id, $offence_select_input_id,"Select Offence");
render_space_html();
bind_modal_open_button($offence_modal_id, $offence_select_button_id);

$report_wrapper_id = "report_select_wrapper";
$report_input_name = 'report';

render_form_text_area($report_wrapper_id, "*", 'Report Statement', $report_input_name, 'report_checkmark', $placeholder = 'Input statement here');
render_space_html();
?>

            <div class="login-form-submit form group">
                <div class="password-change-row-wrapper">
                    <button id="password-change-confirm-button" class="btn btn-primary btn_generic_form" type="submit">Submit</button>
                    <div style="width: 2rem;"></div>
                    <button id="password-change-cancel-button" class="btn btn-primary btn_generic_form btn_generic_form_cancel" type="reset" onclick="location.reload()">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    foreach ($_POST as $key => $value) {
        //echo $key . ": " . $value . "<br>";
    }
//
//    //Column name to shown name
//    $id_column_name = 'People_ID';

//
    $people_col_name_to_alias = array("People_ID"=>"driver","People_name"=>"driver_name","People_address"=>"driver_address","People_licence"=>"driver_licence");
    $vehicle_col_name_to_alias = array("Vehicle_ID"=>"vehicle","Vehicle_plate"=>'vehicle_plate',"Vehicle_make"=>'vehicle_make',"Vehicle_model"=>'vehicle_model',"Vehicle_colour"=>'vehicle_color');
    $select_existing = 'select_existing';
    $input_new = 'input_new';

    $date = $_POST['date'];
    $people_input_option = $_POST['driver_input_option'];
    $people_id = $_POST['driver'];
    $people_name = $_POST['driver_name'];
    $people_address = $_POST['driver_address'];
    $people_licence = $_POST['driver_licence'];
    $vehicle_input_option = $_POST['vehicle_input_option'];
    $vehicle_id = $_POST['vehicle'];
    $vehicle_model = $_POST['vehicle_model'];
    $vehicle_make = $_POST['vehicle_make'];
    $vehicle_plate = $_POST['vehicle_plate'];
    $vehicle_color = $_POST['vehicle_color'];
    $offence_id = $_POST['offence'];
    $report = $_POST['report'];

    $date_valid = ($date != '');
    $driver_valid = ($people_id != '' && $people_input_option == $select_existing || $people_licence != '' && $people_input_option == $input_new);
    $vehicle_valid = ($vehicle_id != '' && $vehicle_input_option == $select_existing || $vehicle_plate != '' && $vehicle_input_option == $input_new);
    $offence_valid = ($offence_id != '');
    $report_valid = ($report != '');

    $valid = $date_valid && $driver_valid && $vehicle_valid && $offence_valid && $report_valid;

    if(!$valid){
        if(!$date_valid) {
            store_alert_message("Invalid date input");
        }
        else if(!$driver_valid){
            store_alert_message("Invalid driver input");
        }
        else if(!$vehicle_valid){
            store_alert_message("Invalid vehicle input");
        }
        else if(!$offence_valid){
            store_alert_message("Invalid offence input");
        }
        else if(!$report_valid){
            store_alert_message("Invalid report input");
        }
        else{
            store_alert_message("Valid input");
        }
        render_warning_message_if_exist(fetch_alert_message());
        die();
    }

    $conn = start_mysql_connection();
    if($people_input_option == $input_new){
        $stmt = $conn->prepare("SELECT * FROM People WHERE People_licence = ?");
        $stmt->bind_param("s", $people_licence);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            //echo "Found licence already in database";
            while ($row = $result->fetch_assoc()) {
                $people_id = $row["People_ID"];
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO People (People_name, People_address, People_licence) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $people_name, $people_address, $people_licence);
            if ($stmt->execute()) {
                $people_id = $conn->insert_id;

                record_insert_people($conn, $people_id, $people_name, $people_licence);

            }
        }
    }

    if($vehicle_input_option == $input_new){
        $stmt = $conn->prepare("SELECT * FROM Vehicle WHERE Vehicle_plate = ?");
        $stmt->bind_param("s", $vehicle_plate);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            //echo "Found licence already in database";
            while ($row = $result->fetch_assoc()) {
                $vehicle_id = $row["Vehicle_ID"];
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO Vehicle (Vehicle_make, Vehicle_model, Vehicle_plate, Vehicle_colour) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $vehicle_make, $vehicle_model, $vehicle_plate,  $vehicle_color);
            if ($stmt->execute()) {
                $vehicle_id = $conn->insert_id;

                record_insert_vehicle($conn, $vehicle_id, $vehicle_make, $vehicle_model, $vehicle_plate);
                //echo "Inserted Vehicle".$vehicle_id."<br>";
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO Incident (Vehicle_ID, People_ID, Incident_Date, Incident_Report, Offence_ID ) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissi", $vehicle_id, $people_id, $date, $report, $offence_id);
    if($stmt->execute()){
        $incident_id = $conn->insert_id;

        record_insert_incident($conn, $incident_id, $vehicle_id, $people_id, $offence_id, $date);
        //echo "Incident ID: ".$incident_id."<br>";
    }
    $stmt->close();
    end_mysql_connection($conn);
}
render_success_message_if_exist(fetch_alert_message());
?>
</body>
</html>
