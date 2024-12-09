<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
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

function render_form_text_area($wrapper_id, $required_asterisk, $label_text, $input_name, $checkmark_id, $placeholder = '')
{

    $form_text_area_doc = <<<EOT
                <div style="display: flex; flex-direction: row;" id=${wrapper_id}>
                    <label class="password-change-star-label">${required_asterisk}</label>
                    <label class="password-change-input-label">${label_text}</label>
                    <textarea name="${input_name}" class="form-control form-control-normal form-control-textarea" placeholder="${placeholder}" rows="5"></textarea>
                    <label id="${checkmark_id}" class="password-change-mark-label" style="color: green;"></label>
                </div>
EOT;
    echo $form_text_area_doc;
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
                const ${modal_id}_id = "$modal_id";
                const ${openBtn_id}_id = '$openBtn_id';
                const $modal_id = document.getElementById(${modal_id}_id);
                const $openBtn_id = document.getElementById(${openBtn_id}_id);

                $openBtn_id.onclick = function(event) {
                    event.preventDefault();
                    $modal_id.style.display = "flex";
                }
            </script>
EOT;
    echo $bind_modal_open_button_doc;
}
?>
