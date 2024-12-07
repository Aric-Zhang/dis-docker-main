<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'navi_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'grid_container.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'modal.php';
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
        <h2>Add Vehicle Information</h2>
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
                                  $col_id_name ,
                                  $col_name_name,
                                  $col_licence_name){
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
                                <span style = "flex: 0 0 50%; text-align: left;">
                                \${result.$col_name_name}
                                </span>
                                <span style = "flex: 0 0 50%; text-align: left;"> 
                                \${result.$col_licence_name}
                                </span>
                            </button>
                        </div>
                        `;
                        resultsContainer.appendChild(resultItem);
                        
                        
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
            $col_name_name,
            $col_licence_name );

        end_modal($modal_id, $button_id_modal_close);
        ?>
        <?php
        $vehicle_modal_id = "search_vehicle_modal";
        $button_id_modal_close = "close_vehicle_modal_button";

        start_modal($vehicle_modal_id , $button_id_modal_close, "Search Vehicle");


        end_modal($vehicle_modal_id, $button_id_modal_close);
        ?>
        <script src="../js/dynamic_form_elements.js">
        </script>
        <form id="dynamic-form" class="login-form" method="post">
            <p style="color: dimgrey;">Fields marked with an <span style="color: red;">*</span> are required</p>
            <div style="margin: 1rem"></div>
<?php

function render_form_input($wrapper_id, $required_asterisk, $label_text, $input_name, $input_type, $checkmark_id, $placeholder = '')
{

    $form_input_doc = <<<EOT
                <div style="display: flex; flex-direction: row;" id=${wrapper_id}>
                    <label class="password-change-star-label">${required_asterisk}</label>
                    <label class="password-change-input-label">${label_text}</label>
                    <input type="${input_type}" name="${input_name}" class="form-control form-control-normal" placeholder="${placeholder}">
                    <label id="${checkmark_id}" class="password-change-mark-label" style="color: green;"></label>
                </div>
EOT;
    echo $form_input_doc;
}

function render_space_html($id=""){
    $id_string = $id == "" ? "" : "id='${id}'";
    $space_html_doc = <<<EOT
<div style="margin: 1rem;" ${id_string}></div>
EOT;
    echo $space_html_doc;
}

function render_owner_input_radio($name, $label_text){
    $input_option_doc = <<<EOT

        <div style="display: flex; flex-direction: row;">
            <label class = "password-change-star-label">*</label>
                            <label class = "password-change-input-label">$label_text</label>
            <label style="margin-right: 1rem;">
                <input type="radio" name="$name" value="select_existing"> Select Existing
            </label>
            <label style="margin-right: 1rem;">
                <input type="radio" name="$name" value="input_new"> Input New
            </label>
            <label style="margin-right: 1rem;">
                <input type="radio" name="$name" value="leave_it_empty" checked> Leave it empty
            </label>
        </div>
EOT;

    echo $input_option_doc;
}

function render_form_search_button($label_text, $required, $input_name, $checkmark_id, $wrapper_id, $button_id, $invisible_input_id, $placeholder=""){
    $required_asterisk = $required ? "*" : " ";
    $form_search_button_doc = <<<EOT
<div style="display: flex; flex-direction: row;" id=${wrapper_id}>
                    <label class="password-change-star-label">${required_asterisk}</label>
                    <label class="password-change-input-label">${label_text}</label>
                    <input name="${input_name}" id="${invisible_input_id}" class="hidden">
                    <button type="button" name="${input_name}_button" id ="${button_id}" class="form-control form-control-normal btn-primary btn btn_generic_search_form">${placeholder}
                    </button>
                    <label id="${checkmark_id}" class="password-change-mark-label" style="color: green;"></label>
                </div>
EOT;
    echo $form_search_button_doc;
}

function bind_select_or_new_group($form_id, $new_group_id_array, $select_wrapper_id, $radio_name){
    $new_group_id_js_string = "[";
    foreach ($new_group_id_array as $new_element_id){
        $new_group_id_js_string .= ("'".$new_element_id."', ");
    }
    $new_group_id_js_string .= "]";

    $bind_select_or_new_doc = <<<EOT
            <script>
                var form = document.getElementById("$form_id");

                var suffixes = $new_group_id_js_string;
                display_form_elements('', suffixes, false);
                suffixes = ['$select_wrapper_id'];
                display_form_elements('', suffixes, false);

                form.addEventListener('change', function(event) {

                    function display_select(display){
                        var suffixes = ['$select_wrapper_id'];
                        display_form_elements('', suffixes, display);
                    }

                    function display_new(display){
                        var suffixes = $new_group_id_js_string;
                        display_form_elements('', suffixes, display);
                    }

                    if (event.target.type === 'radio' && event.target.name === '$radio_name') {
                        const selected_driver_input = document.querySelector(`input[name="$radio_name"]:checked`);
                        if(selected_driver_input){
                            if(selected_driver_input.value == "select_existing"){
                                display_select(true);
                                display_new(false);
                            }
                            else if(selected_driver_input.value == "input_new"){
                                display_select(false);
                                display_new(true);
                            }
                            else{
                                display_select(false);
                                display_new(false);
                            }
                        }
                    }
                });
            </script>
EOT;
    echo $bind_select_or_new_doc;
}

function bind_modal_open_button($modal_id, $openBtn_id){
    $bind_modal_open_button_doc = <<<EOT
            <script>
                var modal_id = "$modal_id";
                var openBtn_id = '$openBtn_id';
                var modal = document.getElementById(modal_id);
                var openBtn = document.getElementById(openBtn_id);

                openBtn.onclick = function(event) {
                    event.preventDefault();
                    modal.style.display = "flex";
                }
            </script>
EOT;
    echo $bind_modal_open_button_doc;
}

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


?>

            <div class="login-form-submit form group">
                <div class="password-change-row-wrapper">
                    <button id="password-change-confirm-button" class="btn btn-primary btn_generic_form" type="submit">Submit</button>
                    <div style="width: 2rem;"></div>
                    <button id="password-change-cancel-button" class="btn btn-primary btn_generic_form btn_generic_form_cancel" type="reset">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
//if($_SERVER['REQUEST_METHOD'] === 'POST'){
//
//    //Column name to shown name
//    $id_column_name = 'People_ID';
//    $conn = start_mysql_connection();
//
//    $people_col_name_to_alias = array("People_id"=>"owner","People_name"=>"owner_name","People_address"=>"owner_address","People_licence"=>"owner_licence");
//    $vehicle_col_name_to_alias = array("Vehicle_plate"=>'plate',"Vehicle_make"=>'make',"Vehicle_model"=>'model',"Vehicle_colour"=>'color');
//    $insert_row_info_array = array();
//    foreach ($_POST as $key => $value) {
//        $info_field = explode("_", $key,3);
//        $row_number = $info_field[0];
//        $table_name = $info_field[1];
//        $record_key = $info_field[2];
//        if(!isset($insert_row_info_array[$table_name])){
//            $insert_row_info_array[$table_name] = array();
//        }
//        if(!isset($insert_row_info_array[$table_name][$row_number])){
//            $insert_row_info_array[$table_name][$row_number] = array();
//        }
//        $insert_row_info_array[$table_name][$row_number][$record_key] = $value;
//    }
//    foreach ($insert_row_info_array as $table_name => $table_info_array) {
//        foreach ($table_info_array as $row_number => $record_info_array) {
//            $people_id = null;
//            foreach ($record_info_array as $record_key => $record_value) {
//                echo $record_key.":".$record_value."<br>";
//
//            }
//            if($record_info_array['plate']!='') {
//                if ($record_info_array['ownership_input'] == 'input_new') {
//                    $people_name = $record_info_array[$people_col_name_to_alias["People_name"]];
//                    $people_address = $record_info_array[$people_col_name_to_alias["People_address"]];
//                    $people_licence = $record_info_array[$people_col_name_to_alias["People_licence"]];
//                    if ($people_name != "" && $people_licence != "") {
//                        $stmt = $conn->prepare("SELECT * FROM People WHERE People_licence = ?");
//                        $stmt->bind_param("s", $people_licence);
//                        $stmt->execute();
//                        $result = $stmt->get_result();
//                        if ($result->num_rows > 0) {
//                            //echo "Found licence already in database";
//                            while ($row = $result->fetch_assoc()) {
//                                $people_id = $row["People_ID"];
//                            }
//                        } else {
//                            $stmt = $conn->prepare("INSERT INTO People (People_name, People_address, People_licence) VALUES (?, ?, ?)");
//                            $stmt->bind_param("sss", $people_name, $people_address, $people_licence);
//                            if ($stmt->execute()) {
//                                $people_id = $conn->insert_id;
//                            }
//                        }
//                    }
//                } elseif ($record_info_array['ownership_input'] == 'select_existing') {
//                    $people_id = $record_info_array[$people_col_name_to_alias["People_id"]];
//                    $stmt = $conn->prepare("SELECT * FROM People WHERE People_id = ?");
//                    $stmt->bind_param("s", $people_id);
//                    if ($stmt->execute()) {
//                        $result = $stmt->get_result();
//                        if ($result->num_rows == 0) {
//                            $people_id = null;
//                        }
//                    } else {
//                        $people_id = null;
//                    }
//                    echo "Existing People: " . $people_id . "<br>";
//                }
//                $vehicle_plate = $record_info_array[$vehicle_col_name_to_alias['Vehicle_plate']];
//                $vehicle_make = $record_info_array[$vehicle_col_name_to_alias['Vehicle_make']];
//                $vehicle_model = $record_info_array[$vehicle_col_name_to_alias['Vehicle_model']];
//                $vehicle_colour = $record_info_array[$vehicle_col_name_to_alias['Vehicle_colour']];
//
//                $vehicle_id = null;
//                $stmt = $conn->prepare("SELECT * FROM Vehicle WHERE Vehicle_plate = ?");
//                $stmt->bind_param("s", $vehicle_plate);
//                $stmt->execute();
//                $result = $stmt->get_result();
//                // update old
//
//                if ($result->num_rows > 0) {
//                    $row = $result->fetch_assoc();
//                    $vehicle_id = $row["Vehicle_ID"];
//                    if($vehicle_make!=''){
//                        $stmt = $conn->prepare("UPDATE Vehicle SET Vehicle_make = ? WHERE Vehicle_plate = ?");
//                        $stmt->bind_param("ss", $vehicle_make, $vehicle_plate);
//                        $stmt->execute();
//                    }
//                    if($vehicle_model!=''){
//                        $stmt = $conn->prepare("UPDATE Vehicle SET Vehicle_model = ? WHERE Vehicle_plate = ?");
//                        $stmt->bind_param("ss", $vehicle_model, $vehicle_plate);
//                        $stmt->execute();
//                    }
//                    if($vehicle_colour!=''){
//                        $stmt = $conn->prepare("UPDATE Vehicle SET Vehicle_colour = ? WHERE Vehicle_plate = ?");
//                        $stmt->bind_param("ss", $vehicle_colour, $vehicle_plate);
//                        $stmt->execute();
//                    }
//                    echo "Update Vehicle: " . $vehicle_id . "<br>";
//                }
//                else {
//                    $stmt = $conn->prepare("INSERT INTO Vehicle (Vehicle_plate, Vehicle_make, Vehicle_model, Vehicle_colour) VALUES (?, ?, ?, ?)");
//                    $stmt->bind_param("ssss", $vehicle_plate, $vehicle_make, $vehicle_model, $vehicle_colour);
//                    if ($stmt->execute()) {
//                        $vehicle_id = $conn->insert_id;
//                        echo "Add Vehicle: " . $vehicle_id . "<br>";
//                    }
//                }
//                if($people_id!=null && $vehicle_id!=null){
//                    $stmt = $conn->prepare("SELECT * FROM Ownership WHERE Vehicle_ID = ?");
//                    $stmt->bind_param("s", $vehicle_id);
//                    $stmt->execute();
//                    $result = $stmt->get_result();
//                    if ($result->num_rows > 0) {
//                        $stmt = $conn->prepare("UPDATE Ownership SET People_ID = ? WHERE Vehicle_ID = ?");
//                        $stmt->bind_param("ss", $people_id, $vehicle_id);
//                        if($stmt->execute()){
//                            echo "Update Vehicle Ownership: " . $vehicle_id . "<br>";
//                        }
//                    }
//                    else{
//                        $stmt = $conn->prepare("INSERT INTO Ownership (People_ID, Vehicle_ID) VALUES (?, ?)");
//                        $stmt->bind_param("ss", $people_id, $vehicle_id);
//                        if($stmt->execute()){
//                            echo "Add Vehicle Ownership: " . $vehicle_id . "<br>";
//                        }
//                    }
//                }
//            }
//        }
//    }
//
//    end_mysql_connection($conn);
//
//}
//else{
//
//}
//?>
</body>
</html>
