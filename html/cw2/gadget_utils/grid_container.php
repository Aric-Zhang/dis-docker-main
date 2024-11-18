<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';

function render_grid_item($file_path, $icon_file_name, $text, $href){
    $person_icon_path = get_relative_path_to_root($file_path).IMAGE_DIR.$icon_file_name;
    $logout_path = get_relative_path_to_root($file_path).LOGOUT_PATH;
    $grid_item_doc = <<<EOT
    <a href= $logout_path class="grid-item btn">
        <img src= $person_icon_path class="grid-item-icon" alt="icon">
    $text</a>
EOT;
    echo $grid_item_doc;
}
function render_grid($file_path, $grid_item_info_array){

    echo '<div class="grid-container">';
        foreach ($grid_item_info_array as $grid_item_info){
            render_grid_item($file_path, $grid_item_info['icon_file_name'], $grid_item_info['text'], $grid_item_info['href']);
        }
    echo '<div>';
}
?>