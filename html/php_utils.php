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
function getBaseUrl() {
    // 获取协议（http 或 https）
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
    } elseif (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $protocol = "https";
    } else {
        $protocol = "http";
    }

    // 获取主机名
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    } elseif (isset($_SERVER['HTTP_HOST'])) {
        $host = $_SERVER['HTTP_HOST'];
    } else {
        $host = $_SERVER['SERVER_NAME'];
    }

    // 获取请求 URI 的路径部分（去掉查询字符串）
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // 构建基础 URL
    $baseUrl = "$protocol://$host$uri";

    return $baseUrl;
}
function start_mysql_connection(){
    $servername = "mariadb";
    $username = "root";
    $password = "rootpwd";
    $dbname = "cw2-database";

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // other code here!
    if(mysqli_connect_errno())
    {
        echo "Failed to connect to  
          MySQL:".mysqli_connect_error();
        die();
    }
    return $conn;
}
function end_mysql_connection($conn){
    mysqli_close($conn);
}
?>