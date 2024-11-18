<?php

function get_relative_path_from_root($file_path)
{
    return ltrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', $file_path), '/');
}
function get_relative_path_to_root($file_path)
{
    $curr_path_to_root = ltrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', $file_path), '/');
    $folders = explode('/', $curr_path_to_root);
    array_pop($folders);
    $relative_path_to_root = '';
    foreach ($folders as $folder) {
        $relative_path_to_root .= '../';
    }
    return $relative_path_to_root;
}

function get_relative_path($from_file_path, $to_file_path){
    $from_path_folders = explode('/', $from_file_path);
    $to_path_folders = explode('/', $to_file_path);

    if(count($from_path_folders) > 0 ){
        array_pop($from_path_folders);
    }
    $min_depth = min(count($from_path_folders), count($to_path_folders));
    $same_count = 0;
    for($i=0; $i<$min_depth; $i++){
        if($from_path_folders[$i] == $to_path_folders[$i]){
            $same_count++;
        }
    }
    $relative_path = "";
    for($i = $same_count; $i < count($from_path_folders); $i++){
        $relative_path .= "../";
    }
    for($i = $same_count; $i < count($to_path_folders); $i++){
        $relative_path .= $to_path_folders[$i];
        if($i < count($to_path_folders)-1){
            $relative_path .= "/";
        }
    }
    return $relative_path;
}
?>