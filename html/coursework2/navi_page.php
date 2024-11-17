<?php
include '../environment_constants.php';
session_start();
if (!isset($_SESSION[USERNAME])) {
    header("Location: login_page.php");
    die();
}
?>
<html lang="en-GB">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIS Entrance Page</title>
    <style>
        @import "../css/dis_cw2_common.css";
    </style>
</head>
<body>
    <div>
        <nav class="navbar fixed-top navbar-expand">
            <a class="align-items-center display-flex">
                <img src="../images/tvis_navi.png" class="logo" alt="Traffic Violation Inquiry System">
            </a>
            <div class="navbar-nav ml-auto">
                <div class="display-flex align-items-stretch" style="height: 100%">
                    <div class="usermenu">
                        <div class="dropdown">
                            <a href="#" role="button" class="btn dropdown-toggle">
                                <button class="userbutton">ZZ</button>
                                <span class="arrow-down"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</body>
</html>
