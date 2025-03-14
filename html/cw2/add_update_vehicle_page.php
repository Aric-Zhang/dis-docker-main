<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'navi_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'grid_container.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'modal.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'search_bar.php';
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
    <?php
        function render_generic_form_input($label_text, $required, $input_name, $checkmark_id, $placeholder="", $input_type="text"){
            $required_asterisk = $required ? '*' : ' ';
            $generic_input_doc = <<<EOT
                <div style="display: flex; flex-direction: row;">
                    <label class="password-change-star-label">$required_asterisk</label>
                    <label class="password-change-input-label">$label_text</label>
                    <input type="$input_type" name="$input_name" class="form-control form-control-normal" placeholder="$placeholder">
                    <label id="$checkmark_id" class="password-change-mark-label" style="color: green;"></label>
                </div>
EOT;
            echo $generic_input_doc;
        }
    ?>
    <div class = "add_form_container">
        <h2>Add Vehicle Information</h2>
        <?php
        $modal_id = "myModal";
        $button_id_modal_close = "close_modal_button";
        $button_id_modal_open = "change_password_button";
        $button_id_modal_cancel = "password-change-cancel-button";
        start_modal($modal_id , $button_id_modal_close, "Search Owner");
        ?>
        <?php
        $context_input_id = "search_context";
            echo "<input id = '$context_input_id' class = 'hidden'/>"
        ?>
        <?php

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
        ?>
        <div id="results" class="scrollable-modal-container"> </div>
        <?php
            $bind_search_ajax_doc = <<<EOT
                <script>
                function reset_searching_results(){
                    const resultsContainer = document.getElementById('results');
                    resultsContainer.innerHTML = '';
                }
                
        document.getElementById('$search_button_id').addEventListener('click', function(event) {
            event.preventDefault();
            const search_text_input = document.getElementById('$search_input_id');
            const search_type_input = document.getElementById('$invisible_input_id');
            const resultsContainer = document.getElementById('results');
            const context_input = document.getElementById('$context_input_id')
            
            const data = {
                $invisible_input_name: search_type_input.value,
                $search_input_name: search_text_input.value
            };
            
            fetch('search_people.php', {
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
                    reset_searching_results();
                    data.results.forEach(result => {
                        const resultItem = document.createElement('div');
                        resultItem.className = 'result-item';
                        resultItem.innerHTML = `
                        <div>
                            <input name="id" value=\${result.People_ID} style="display:none" id='\${context_input.value}.\${result.People_ID}.id_input' />
                            <button class="btn btn_modal_search_result" id='\${context_input.value}.\${result.People_ID}.confirm_button'>
                                <span style = "flex: 0 0 50%; text-align: left;">
                                \${result.People_name}
                                </span>
                                <span style = "flex: 0 0 50%; text-align: left;"> 
                                \${result.People_licence}
                                </span>
                            </button>
                        </div>
                        `;
                        resultsContainer.appendChild(resultItem);
                        
                        const prefix = context_input.value;
                        
                        const confirm_button = document.getElementById(`\${context_input.value}.\${result.People_ID}.confirm_button`)
                        confirm_button.onclick = function() {
                            const id_input = document.getElementById(`\${context_input.value}.\${result.People_ID}.id_input`)
                            const target_invisible_input = document.getElementById(`\${prefix}owner_select_input`);
                            const owner_select_button = document.getElementById(`\${prefix}owner_select_button`);
                            target_invisible_input.value = id_input.value;
                            owner_select_button.textContent = confirm_button.textContent;
                            reset_searching_results();
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

        ?>

        <?php
        end_modal($modal_id, $button_id_modal_close);
        ?>
        <form id="dynamic-form" class="login-form" method="post">
            <p style="color: dimgrey;">Fields marked with an <span style="color: red;">*</span> are required</p>
            <button id="add-row_button" class="add_row_button btn" onclick="add_vehicle_form_group('form-group-wrapper')" type="button"> </button>
            <div style="margin: 1rem"></div>
            <div class="login-form-submit form group">
                <div class="password-change-row-wrapper">
                    <button id="password-change-confirm-button" class="btn btn-primary btn_generic_form" type="submit">Submit</button>
                    <div style="width: 2rem;"></div>
                    <button id="password-change-cancel-button" class="btn btn-primary btn_generic_form btn_generic_form_cancel" type="reset" onclick="location.reload()">Cancel</button>
                </div>
            </div>
            <script src="../js/dynamic_form_elements.js">
            </script>
            <script>
                let form_group_count = 0

                function display_new_owner_input(prefix, display = true){
                    var suffixes= ["owner_name","new_owner_space_1","owner_address","new_owner_space_2","owner_licence"];
                    display_form_elements(prefix, suffixes, display)
                }

                function display_select_owner_input(prefix, display = true){
                    var suffixes = ['owner_select']
                    display_form_elements(prefix, suffixes, display)
                }

                function add_vehicle_form_group(wrapper_id_prefix){
                    const form = document.getElementById("dynamic-form");
                    const new_form_group_wrapper = document.createElement("div");
                    new_form_group_wrapper.className = "form-group-wrapper";
                    const new_form_group_wrapper_id = `${wrapper_id_prefix}_${form_group_count}`;
                    new_form_group_wrapper.id = new_form_group_wrapper_id;

                    const new_form_group = document.createElement("div");
                    new_form_group.className = "login-form-password form-group"
                    const new_form_group_id = `dynamic_group_${form_group_count}`;
                    new_form_group.id = new_form_group_id;

                    const prefix = `${form_group_count}.Vehicle.`

                    new_form_group.innerHTML += form_input_html("Plate", true, `${prefix}plate`, "checkmark_plate",`${prefix}plate`);
                    new_form_group.innerHTML += space_html();
                    new_form_group.innerHTML += form_input_html("Make", false, `${prefix}make`, "checkmark_make",`${prefix}make`);
                    new_form_group.innerHTML += space_html();
                    new_form_group.innerHTML += form_input_html("Model", false, `${prefix}model`, "checkmark_model",`${prefix}model`);
                    new_form_group.innerHTML += space_html();
                    new_form_group.innerHTML += form_input_html("Color", false, `${prefix}color`, "checkmark_color",`${prefix}color`);
                    new_form_group.innerHTML += space_html();
                    new_form_group.innerHTML += owner_input_radio_html(prefix, 'ownership_input');
                    new_form_group.innerHTML += space_html();
                    new_form_group.innerHTML += form_search_button_html("Owner Select", false, `${prefix}owner`, "checkmark_owner",`${prefix}owner_select`,`${prefix}owner_select_button`,`${prefix}owner_select_input`, "Select People");

                    new_form_group.innerHTML += form_input_html("Owner Name", false, `${prefix}owner_name`, "checkmark_owner_name",`${prefix}owner_name`);
                    new_form_group.innerHTML += space_html(`${prefix}new_owner_space_1`);
                    new_form_group.innerHTML += form_input_html("Owner Address", false, `${prefix}owner_address`, "checkmark_owner_address",`${prefix}owner_address`);
                    new_form_group.innerHTML += space_html(`${prefix}new_owner_space_2`);
                    new_form_group.innerHTML += form_input_html("Owner Licence", false, `${prefix}owner_licence`, "checkmark_owner_licence",`${prefix}owner_licence`);

                    const radio_group = document.createElement('div');
                    radio_group.className = "flex_row";

                    const del_button = document.createElement("button");
                    del_button.textContent = "";
                    del_button.type = "button";
                    del_button.className = "del_row_button add_row_button btn";

                    del_button.addEventListener('click',function(){
                        const group_wrapper = document.getElementById(new_form_group_wrapper_id);
                        if(group_wrapper){
                            group_wrapper.remove();
                        }
                    });

                    new_form_group_wrapper.appendChild(new_form_group);
                    new_form_group_wrapper.appendChild(del_button);

                    form.insertBefore(new_form_group_wrapper, form.querySelector('button[id="add-row_button"]'));
                    display_select_owner_input(prefix, false);
                    display_new_owner_input(prefix,false);

                    const modal_id = "myModal";
                    const openBtn_id = `${prefix}owner_select_button`;
                    const context_input_id = "search_context";
                    var modal = document.getElementById(modal_id);
                    var openBtn = document.getElementById(openBtn_id);
                    var context_input = document.getElementById(context_input_id);

                    openBtn.onclick = function(event) {
                        event.preventDefault();
                        modal.style.display = "flex";
                        context_input.value = prefix
                    }
                    
                    form.addEventListener('change', function(event) {

                        if (event.target.type === 'radio' && event.target.name === `${prefix}ownership_input`) {
                            const selected_ownership_input = document.querySelector(`input[name="${prefix}ownership_input"]:checked`);
                            if(selected_ownership_input){
                                if(selected_ownership_input.value == "select_existing"){
                                    display_select_owner_input(prefix, true);
                                    display_new_owner_input(prefix, false);
                                }
                                else if(selected_ownership_input.value == "input_new"){
                                    display_select_owner_input(prefix, false);
                                    display_new_owner_input(prefix, true);
                                }
                                else{
                                    display_select_owner_input(prefix, false);
                                    display_new_owner_input(prefix, false);
                                }
                            }
                        }
                    });

                    form_group_count += 1
                }

            </script>
        </form>
    </div>
</div>
<?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    //Column name to shown name
    $id_column_name = 'People_ID';
    $conn = start_mysql_connection();

    $people_col_name_to_alias = array("People_id"=>"owner","People_name"=>"owner_name","People_address"=>"owner_address","People_licence"=>"owner_licence");
    $vehicle_col_name_to_alias = array("Vehicle_plate"=>'plate',"Vehicle_make"=>'make',"Vehicle_model"=>'model',"Vehicle_colour"=>'color');
    $insert_row_info_array = array();
    foreach ($_POST as $key => $value) {
        $info_field = explode("_", $key,3);
        $row_number = $info_field[0];
        $table_name = $info_field[1];
        $record_key = $info_field[2];
        if(!isset($insert_row_info_array[$table_name])){
            $insert_row_info_array[$table_name] = array();
        }
        if(!isset($insert_row_info_array[$table_name][$row_number])){
            $insert_row_info_array[$table_name][$row_number] = array();
        }
        $insert_row_info_array[$table_name][$row_number][$record_key] = $value;
    }
    foreach ($insert_row_info_array as $table_name => $table_info_array) {
        foreach ($table_info_array as $row_number => $record_info_array) {
            $people_id = null;
//            foreach ($record_info_array as $record_key => $record_value) {
//                echo $record_key.":".$record_value."<br>";
//
//            }
            if($record_info_array['plate']!='') {
                if ($record_info_array['ownership_input'] == 'input_new') {
                    $people_name = $record_info_array[$people_col_name_to_alias["People_name"]];
                    $people_address = $record_info_array[$people_col_name_to_alias["People_address"]];
                    $people_licence = $record_info_array[$people_col_name_to_alias["People_licence"]];
                    if ($people_name != "" && $people_licence != "") {
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
                            }
                        }
                    }
                } elseif ($record_info_array['ownership_input'] == 'select_existing') {
                    $people_id = $record_info_array[$people_col_name_to_alias["People_id"]];
                    $stmt = $conn->prepare("SELECT * FROM People WHERE People_id = ?");
                    $stmt->bind_param("s", $people_id);
                    if ($stmt->execute()) {
                        $result = $stmt->get_result();
                        if ($result->num_rows == 0) {
                            $people_id = null;
                        }
                    } else {
                        $people_id = null;
                    }
                    store_alert_message("Existing People: " . $people_id);
                }
                $vehicle_plate = $record_info_array[$vehicle_col_name_to_alias['Vehicle_plate']];
                $vehicle_make = $record_info_array[$vehicle_col_name_to_alias['Vehicle_make']];
                $vehicle_model = $record_info_array[$vehicle_col_name_to_alias['Vehicle_model']];
                $vehicle_colour = $record_info_array[$vehicle_col_name_to_alias['Vehicle_colour']];

                $vehicle_id = null;
                $stmt = $conn->prepare("SELECT * FROM Vehicle WHERE Vehicle_plate = ?");
                $stmt->bind_param("s", $vehicle_plate);
                $stmt->execute();
                $result = $stmt->get_result();
                // update old

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $vehicle_id = $row["Vehicle_ID"];
                    if($vehicle_make!=''){
                        $stmt = $conn->prepare("UPDATE Vehicle SET Vehicle_make = ? WHERE Vehicle_plate = ?");
                        $stmt->bind_param("ss", $vehicle_make, $vehicle_plate);
                        if($stmt->execute()){
                            record_update_vehicle($conn, $vehicle_id, 'Vehicle_make', $vehicle_make);
                        }
                    }
                    if($vehicle_model!=''){
                        $stmt = $conn->prepare("UPDATE Vehicle SET Vehicle_model = ? WHERE Vehicle_plate = ?");
                        $stmt->bind_param("ss", $vehicle_model, $vehicle_plate);
                        if($stmt->execute()){
                            record_update_vehicle($conn, $vehicle_id, 'Vehicle_model', $vehicle_model);
                        }
                    }
                    if($vehicle_colour!=''){
                        $stmt = $conn->prepare("UPDATE Vehicle SET Vehicle_colour = ? WHERE Vehicle_plate = ?");
                        $stmt->bind_param("ss", $vehicle_colour, $vehicle_plate);
                        if($stmt->execute()){
                            record_update_vehicle($conn, $vehicle_id, 'Vehicle_colour', $vehicle_colour);
                        }
                    }
                }
                else {
                    $stmt = $conn->prepare("INSERT INTO Vehicle (Vehicle_plate, Vehicle_make, Vehicle_model, Vehicle_colour) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $vehicle_plate, $vehicle_make, $vehicle_model, $vehicle_colour);
                    if ($stmt->execute()) {
                        $vehicle_id = $conn->insert_id;
                        record_insert_vehicle($conn, $vehicle_id, $vehicle_make, $vehicle_model, $vehicle_plate);
                    }
                }
                if($people_id!=null && $vehicle_id!=null){
                    $stmt = $conn->prepare("SELECT * FROM Ownership WHERE Vehicle_ID = ?");
                    $stmt->bind_param("s", $vehicle_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $stmt = $conn->prepare("UPDATE Ownership SET People_ID = ? WHERE Vehicle_ID = ?");
                        $stmt->bind_param("ss", $people_id, $vehicle_id);
                        if($stmt->execute()){
                            record_update_ownership($conn, $people_id, $vehicle_id);
                        }
                    }
                    else{
                        $stmt = $conn->prepare("INSERT INTO Ownership (People_ID, Vehicle_ID) VALUES (?, ?)");
                        $stmt->bind_param("ss", $people_id, $vehicle_id);
                        if($stmt->execute()){
                            record_insert_ownership($conn, $people_id, $vehicle_id);
                        }
                    }
                }
            }
        }
    }
    render_success_message_if_exist(fetch_alert_message());
    end_mysql_connection($conn);
}
else{

}
?>
</body>
</html>
