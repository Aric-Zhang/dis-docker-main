<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/environment_constants.php";
include_once $_SERVER['DOCUMENT_ROOT'] ."/php_utils.php";

    session_start();
session_unset();
session_destroy();
header("Location: ".get_relative_path(__FILE__, LOGIN_PAGE_PATH));
exit();
?>