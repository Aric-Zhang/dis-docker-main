<?php
function get_relative_path_to_root()
{
    $curr_path_to_root = ltrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__), '/');
    $folders = explode('/', $curr_path_to_root);
    $relative_path_to_root = '';
    foreach ($folders as $folder) {
        $relative_path_to_root .= '../';
    }
    return $relative_path_to_root;
}
?>