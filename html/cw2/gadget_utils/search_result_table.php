<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';

function caption_right_button($text, $onclick_function){
    $button_html = "<button class='caption_button' onclick='${onclick_function} '>$text</button>";
    return $button_html;
}
function start_nested_table($nested_table_caption, $colspan, $table_id = '' ,$caption_button_html = ''){
    $start_nested_table_doc = <<<EOT
                                <tr class='selected_row'>
                                    <td colspan='$colspan' style='user-select: text'  class='zero-padding'> 
                                    <table class='search_res_table_nested' style='user-select: text;' id="$table_id">                
                                        <caption style="position: relative">
                                            <span>$nested_table_caption</span> 
                                            $caption_button_html 
                                        </caption>
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
function render_nested_table($nested_table_caption, $nested_result, $nested_header_array, $no_result_placeholder, $make_url_data = null, $colspan = 5) {
    start_nested_table( $nested_table_caption, $colspan);
    if($nested_result->num_rows > 0) {
        echo "<thead>";
        foreach ($nested_header_array as $nested_header_name => $nested_header_alias) {
            echo "<td>";
            echo $nested_header_alias;
            echo "</td>";
        }
        if ($make_url_data != null) {
            echo "<td></td>";
        }
        echo "</thead>";
    }

    while ($nested_row = $nested_result->fetch_assoc()){
        echo "<tr>";
        foreach ($nested_header_array as $nested_header_name=>$nested_header_alias) {
            echo "<td>";
            echo $nested_row[$nested_header_name];
            echo "</td>";
        }
        if($make_url_data != null){
            $href = make_nested_table_detail_url($nested_row, $make_url_data);
            echo "<td style='width: 5rem'><a href='".$href."'>Details</a></td>";
        }
        echo "</tr>";
    }

    if($nested_result->num_rows < 1){
        echo "<tr><td>$no_result_placeholder</td></tr>";
    }
    end_nested_table();
}
function render_vertical_expand_row_nested_table($row, $nested_table_caption, $nested_header_array, $colspan = 5) {
    start_nested_table($nested_table_caption, $colspan);
    foreach ($nested_header_array as $nested_header_name=>$nested_header_alias) {
        echo "<tr>";
        echo "<td>$nested_header_alias</td>";
        echo "<td> ".$row[$nested_header_name]."</td>";
        echo "</tr>";
    }
    end_nested_table();
}

function make_nested_table_detail_url($nested_sql_result_row, $nested_table_make_url_data) {
    $base_url = $nested_table_make_url_data["base_url"];
    $search_type_name = $nested_table_make_url_data["search_type_name"];
    $search_type_value = $nested_table_make_url_data["search_type_value"];
    $search_text_name = $nested_table_make_url_data["search_text_name"];
    $search_text_column_name = $nested_table_make_url_data["search_text_column_name"];
    $expand_id_name = $nested_table_make_url_data["expand_id_name"];
    $id_column_name = $nested_table_make_url_data["id_column_name"];

    $search_text_value = $nested_sql_result_row[$search_text_column_name];
    $expand_id_value = $nested_sql_result_row[$id_column_name];
    $nested_query_array = array($search_type_name=>$search_type_value, $search_text_name=>$search_text_value, $expand_id_name=>$expand_id_value);
    $nested_url = $base_url."?".http_build_query($nested_query_array);
    return $nested_url;
}
function render_search_table_row($row, $id_column_name, $table_headings_array)
{
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
}
function start_search_table($table_id, $caption, $table_headings_array){
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
    echo $table_start;
}
function end_search_table_and_bind_expand_url($table_id){
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
    echo $table_end;
    echo $bind_tr_url_script_doc;
}

function render_nested_editable_form($input_name, $input_value, $invisible_input_name, $invisible_input_value, $input_type='text')
{
    $editable_form_doc = <<<EOT
                                <form method="POST" style="max-height: 1rem; margin: 0; padding: 0; width: 100%; display: flex;">
                                    <input type="hidden" name="$invisible_input_name" value="$invisible_input_value">
                                    <input type="$input_type" name="$input_name" value="$input_value" style="border: none; flex-grow: 1; margin-right: 1rem;">
                                    <button type="submit" style="height: 1rem; margin: 0; padding:0; color: #16417C" class="link-button">Submit Modification</button>
                                </form>
EOT;
    echo $editable_form_doc;
}
function gen_nested_table_id($nested_table_caption, $id_name, $id){
    return str_replace(" ","_",$nested_table_caption)."_".$id_name."_".$id;
}
function render_editable_vertical_expand_row_nested_table($row, $nested_table_caption, $nested_header_array, $id_name, $id, $colspan = 5, $nested_input_type_array = null, $caption_button_html = '') {
    $nested_table_id = gen_nested_table_id($nested_table_caption, $id_name, $id);
    start_nested_table($nested_table_caption, $colspan, $nested_table_id, $caption_button_html);

    foreach ($nested_header_array as $nested_header_name=>$nested_header_alias) {
        echo "<tr>";
        echo "<td>$nested_header_alias</td>";
        echo "<td>";
        if(isset($_SESSION[AUTHORITY]) && $_SESSION[AUTHORITY] == AUTHORITY_ADMIN){
            $input_type = 'text';
            if($nested_input_type_array != null && isset($nested_input_type_array[$nested_header_name])){
                $input_type = $nested_input_type_array[$nested_header_name];
            }
            render_nested_editable_form($nested_header_alias, $row[$nested_header_name], $id_name, $id, $input_type);
        }
        else {
            echo $row[$nested_header_name];
        }
        echo "</td>";
        echo "</tr>";
    }
    end_nested_table();


}

?>