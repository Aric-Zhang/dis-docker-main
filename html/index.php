<?php
include 'environment_constants.php';
session_start();
if (!isset($_SESSION[USERNAME])) {
    header("Location: login_page.php");
}
else{
    header( "Location: index_2.php");
}
?>
