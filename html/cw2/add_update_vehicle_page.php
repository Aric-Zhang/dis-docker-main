<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'navi_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'grid_container.php';

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

        .add_form_container{
            margin: 1rem;
            padding: 1rem;
        }

        .form-group-wrapper{
            display: flex;
            flex-direction: row;
            justify-content: space-between ;
            align-items: center;
            width: 100%;
            padding-top: 1rem;
            padding-left: 1rem;
            padding-right: 1rem;
            margin: 0.5rem 0.5rem 0.5rem 0;
            border: 1px solid #CFD4D8;
            border-radius: 8px;
        }

        .add_row_button{
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 2rem;
            font-size: 4rem;
            color: #ffffff;
            background-color: #CFD4D8; /* 按钮背景颜色 */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        .add_row_button:hover{
            background-color: #aaaaaa; /* 按钮背景颜色 */
        }
        .add_row_button::before,
        .add_row_button::after {
            content: '';
            position: absolute;
            background-color: #ffffff; /* 加号颜色 */

        }
        .add_row_button::before {
            width: 4px;
            height: 30px;
            left: 50%;
            top: 5px;
            transform: translateX(-50%)
        }
        .add_row_button::after {
            width: 30px;
            height: 4px;
            left: 5px;
            top: 50%;
            transform: translateY(-50%);
        }

        .del_row_button{
            margin: 1rem;
        }

        .del_row_button::after{
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2); /* 加号阴影效果 */
        }

        .del_row_button::before{
            width: 0;
            height: 0;
        }

        .flex_row{
            display: flex;
            flex-direction: row;
            width: 100%;
        }

        .btn_generic_form{
            background-color: #118bee;
            border: 2px solid #118bee;
            border-radius: 8px;
            width: 100%;
        }

        .btn_generic_form_cancel{
            background-color: white;
            color: #118bee;
            border: 2px solid #118bee;
        }

        .hidden{
            display:none !important;
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
        <form id="dynamic-form" class="login-form" method="post">
            <p style="color: dimgrey;">Fields marked with an <span style="color: red;">*</span> are required</p>
            <button id="add-row_button" class="add_row_button btn" onclick="add_vehicle_form_group()" type="button"> </button>
            <div style="margin: 1rem"></div>
            <div class="login-form-submit form group">
                <div class="password-change-row-wrapper">
                    <button id="password-change-confirm-button" class="btn btn-primary btn_generic_form" type="submit">Submit</button>
                    <div style="width: 2rem;"></div>
                    <button id="password-change-cancel-button" class="btn btn-primary btn_generic_form btn_generic_form_cancel" type="reset">Cancel</button>
                </div>
            </div>
            <script>
                let form_group_count = 0

                function form_input_html(label_text, required, input_name, checkmark_id, wrapper_id, placeholder="", input_type="text"){
                    const required_asterisk = required?"*":" ";
                    const input_row_html = `
                <div style="display: flex; flex-direction: row;" id=${wrapper_id}>
                    <label class="password-change-star-label">${required_asterisk}</label>
                    <label class="password-change-input-label">${label_text}</label>
                    <input type="${input_type}" name="${input_name}" class="form-control form-control-normal" placeholder="${placeholder}">
                    <label id="${checkmark_id}" class="password-change-mark-label" style="color: green;"></label>
                </div>`;
                    return input_row_html
                }
                function space_html(id=""){
                    const id_string = id == "" ? "" : `id='${id}'`;
                    const space_html = `<div style="margin: 1rem;" ${id_string}></div>`;
                    return space_html
                }
                function owner_input_radio_html(){
                    return `
        <div style="display: flex; flex-direction: row;">
            <label class = "password-change-star-label">*</label>
                            <label class = "password-change-input-label">Ownership</label>
            <label style="margin-right: 1rem;">
                <input type="radio" name="ownership_input" value="select_existing"> Select Existing
            </label>
            <label style="margin-right: 1rem;">
                <input type="radio" name="ownership_input" value="input_new"> Input New
            </label>
            <label style="margin-right: 1rem;">
                <input type="radio" name="ownership_input" value="leave_it_empty" checked> Leave it empty
            </label>
        </div>`
                }
                function display_new_owner_input(prefix, display = true){
                    var suffixes= ["owner_name","new_owner_space_1","owner_address","new_owner_space_2","owner_licence"];
                    for(var i = 0; i < suffixes.length; i++) {
                        const suffix = suffixes[i];
                        const owner_name = document.getElementById(`${prefix}${suffix}`);
                        if (owner_name) {
                            if(display && owner_name.classList.contains('hidden')) {
                                owner_name.classList.remove("hidden");
                            }
                            else{
                                owner_name.classList.add("hidden");
                            }
                        }
                    }
                }
                function display_select_owner_input(prefix, display = true){
                    const search_input = document.getElementById(`${prefix}owner_select`);
                    if(search_input) {
                        if(display && search_input.classList.contains('hidden')) {
                            search_input.classList.remove("hidden");
                        }
                        else {
                            search_input.classList.add("hidden");
                        }
                    }
                }

                function add_vehicle_form_group(){
                    const form = document.getElementById("dynamic-form");
                    const new_form_group_wrapper = document.createElement("div");
                    new_form_group_wrapper.className = "form-group-wrapper";
                    const new_form_group_wrapper_id = `vehicle_group_wrapper_${form_group_count}`;
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
                    new_form_group.innerHTML += owner_input_radio_html();
                    new_form_group.innerHTML += space_html();
                    new_form_group.innerHTML += form_input_html("Owner Select", false, `${prefix}owner`, "checkmark_owner",`${prefix}owner_select`);
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

                    form.addEventListener('change', function(event) {
                        if (event.target.type === 'radio' && event.target.name === 'ownership_input') {
                            const selected_ownership_input = document.querySelector('input[name="ownership_input"]:checked');
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
    <?php
    foreach ($_POST as $key => $value) {
        echo $key.": ".$value."<br>";
    }
    ?>
</div>
</body>
</html>
