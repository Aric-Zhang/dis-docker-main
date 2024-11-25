<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';

function start_nested_table($nested_table_caption){
    $start_nested_table_doc = <<<EOT
                                <tr class='selected_row'>
                                    <td colspan='5' style='user-select: text'  class='zero-padding'> 
                                    <table class='search_res_table_nested' style='user-select: text'>                
                                        <caption>$nested_table_caption</caption>
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
function render_nested_table($nested_table_caption, $nested_result, $nested_header_array, $no_result_placeholder) {
    start_nested_table( $nested_table_caption);
    $nested_table_content_array = array();
    while ($nested_row = $nested_result->fetch_assoc()){
        $nested_row_array = array();
        foreach ($nested_header_array as $nested_header_name=>$nested_header_alias) {
            array_push($nested_row_array, $nested_row[$nested_header_name]);
        }
        array_push($nested_table_content_array, $nested_row_array);
    }
    foreach ($nested_table_content_array as $nested_row_array) {
        echo "<tr>";
        foreach ($nested_row_array as $nested_td) {
            echo "<td>$nested_td</td>";
        }
        echo "<td style='width: 5rem'><a href='"."#"."'>Details</a></td>";
        echo "</tr>";
    }
    if($nested_result->num_rows < 1){
        echo "<tr><td>$no_result_placeholder<td></tr>";
    }
    end_nested_table();
}
function render_vertical_expand_row_nested_table($row, $nested_table_caption, $nested_header_array){
    start_nested_table($nested_table_caption);
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
?>