<?php
include_once $_SERVER['DOCUMENT_ROOT']."/environment_constants.php";

session_start();
session_unset();
session_destroy();
header("Location: ".LOGIN_PAGE_PATH);
exit();
?>