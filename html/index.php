<?php
include 'environment_constants.php';
session_start();
if (!isset($_SESSION[USERNAME])) {
    header("Location: login_page.php");
}
else{
    header( "Location: coursework2/navi_page.php");
}
?>
