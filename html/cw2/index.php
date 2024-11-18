<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
session_start();
if (!isset($_SESSION[USERNAME])) {
    header("Location: ".get_relative_path_to_root(__FILE__).LOGIN_PAGE_PATH);
}
else{
    header( "Location: ".get_relative_path_to_root(__FILE__).NAVI_PAGE_PATH);
}
?>
