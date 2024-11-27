<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'dropdown_menu.php';

class SearchOptionRenderer{

    var $invisible_input_id;
    var $invisible_input_name;
    var $search_opt_button_id;
    var $search_opt_button_text_id;
    var $dropdown_menu_item_type_placeholder_array;
    var $dropdown_menu_item_array;

    var $search_type_array;

    var $dropdown_menu_id;
    var $dropdown_button_id;

    function set_parameters(
        $invisible_input_id,
        $invisible_input_name,
        $search_opt_button_id,
        $search_opt_button_text_id,

        $dropdown_menu_item_type_placeholder_array,

        $dropdown_menu_id,
        $dropdown_button_id){

        $this->invisible_input_id = $invisible_input_id;
        $this->invisible_input_name = $invisible_input_name;
        $this->search_opt_button_id = $search_opt_button_id;
        $this->search_opt_button_text_id = $search_opt_button_text_id;

        $dropdown_menu_item_array = array();
        $search_type_array = array();
        foreach ($dropdown_menu_item_type_placeholder_array as $dmitp){
            array_push($dropdown_menu_item_array, array('text'=> $dmitp['text'], 'href'=>'#','id'=>$dmitp['id']));
            array_push($search_type_array, array('id'=>$dmitp['id'],'type'=>$dmitp['type'],'func'=>$dmitp['func']));
        }
        $this->dropdown_menu_item_type_placeholder_array = $dropdown_menu_item_type_placeholder_array;
        $this->dropdown_menu_item_array = $dropdown_menu_item_array;

        $this->search_type_array = $search_type_array;

        $this->dropdown_menu_id = $dropdown_menu_id;
        $this->dropdown_button_id = $dropdown_button_id;
    }
    function render(){

        $invisible_input_id = $this->invisible_input_id;
        $invisible_input_name = $this->invisible_input_name;
        $search_opt_button_id = $this->search_opt_button_id;
        $search_opt_button_text_id = $this->search_opt_button_text_id;
        $dropdown_menu_item_type_placeholder_array = $this->dropdown_menu_item_type_placeholder_array;
        $dropdown_menu_item_array=$this->dropdown_menu_item_array;

        $search_type_array=$this->search_type_array;

        $dropdown_menu_id = $this->dropdown_menu_id;
        $dropdown_button_id = $this->dropdown_button_id;

        $links_string = "";
        $links_additional_functions_string = "";
        foreach ($search_type_array as $search_type) {
            $links_string.="[document.getElementById('".$search_type['id']."'),'".$search_type['type']."'],";
            $links_additional_functions_string.= $search_type['func'];
        }

        $search_default_type = "";
        $button_default_text = "";
        if(count($dropdown_menu_item_type_placeholder_array) > 0){
            $found_match = false;
            if(isset($_GET[$invisible_input_name])){
                $got_type = $_GET[$invisible_input_name];
                foreach ($dropdown_menu_item_type_placeholder_array as $dmitp){
                    if($dmitp['type'] == $got_type){
                        $search_default_type = $dmitp['type'];
                        $button_default_text = $dmitp['text'];
                        $found_match = true;
                        break;
                    }
                }
            }
            if(! $found_match) {
                $search_default_type = $dropdown_menu_item_type_placeholder_array[0]['type'];
                $button_default_text = $dropdown_menu_item_type_placeholder_array[0]['text'];
            }
        }

        $start_search_opt_button_doc = <<<EOT
                <input id="$invisible_input_id" type="text" name="$invisible_input_name" style="display: none" value="$search_default_type">
                <button id="$search_opt_button_id" class="btn btn-primary form_control_search dropdown_button" type="button">
                    <span id="$search_opt_button_text_id">$button_default_text</span>
EOT;
        $bind_function_script_doc=<<<EOT
                                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const links = [
                                $links_string
                            ];
                            
                            const link_function_map = new Map();
                            $links_additional_functions_string                    

                            var span = document.getElementById('$search_opt_button_text_id');
                            var input = document.getElementById('$invisible_input_id');

                            links.forEach((item)=>{
                                var link = item[0];
                                var type = item[1];
                                link.addEventListener('click', function(event) {
                                    event.preventDefault();
                                    span.innerHTML = link.textContent;
                                    input.value = type;
                                    var additional_func = link_function_map.get(link.id);
                                    if(additional_func){
                                        additional_func(event);
                                    }
                                });
                            });
                        });
                    </script>
EOT;

        $end_search_opt_button_doc = <<<EOT
                </button>
EOT;
        echo $start_search_opt_button_doc;

        render_dropdown_menu($dropdown_menu_id, $dropdown_menu_item_array);
        bind_dropdown_menu_to_button($dropdown_menu_id, $dropdown_button_id);

        echo $bind_function_script_doc;
        echo $end_search_opt_button_doc;
    }
}
function start_search_bar(){
    $start_search_bar_doc = <<<EOT
        <form action="" class="general_form" method="get">
            <div class="form-group search_input_wrapper">
EOT;
    echo $start_search_bar_doc;
}
function end_search_bar(){
    $end_search_bar_doc = <<<EOT
            </div>
        </form>
EOT;
    echo $end_search_bar_doc;
}
function render_search_input_and_button($input_id,$input_name,$placeholder)
{
    $input_init_value = "";
    if(isset($_GET[$input_name])){
        $input_init_value = $_GET[$input_name];
    }
    $search_input_doc = <<<EOT
                    
                    <input id='$input_id' type="text" name="$input_name" class="form-control form_control_search search_input" placeholder="$placeholder" value="$input_init_value">
EOT;
    $search_button_doc = <<<EOT
                    <button class="btn btn-primary form_control_search search_button" type="submit">Search</button>
EOT;

    echo $search_input_doc;
    echo $search_button_doc;
}
function javascript_replace_placeholder_string($link_id, $input_id, $placeholder){
    return "link_function_map.set('".$link_id."', function(event){                                        
                                    var search_input = document.getElementById('".$input_id."');
                                    search_input.placeholder = '".$placeholder."';
                                    });";
}

?>
