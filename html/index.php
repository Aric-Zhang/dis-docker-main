<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
session_start();
if (!isset($_SESSION[USERNAME])) {
    header("Location: coursework2/login_page.php");
}
else{
    header( "Location: coursework2/navi_page.php");
}
?>
