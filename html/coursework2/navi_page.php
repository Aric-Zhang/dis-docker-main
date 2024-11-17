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
                            <a id="dropdown-button" role="button" class="btn dropdown-toggle">
                                <button class="userbutton">ZZ</button>
                                <span class="arrow-down"></span>
                            </a>
                            <div id="dropdown-menu" class="dropdown-menu dropdown-menu-right">
                                <div style="position: relative;width: 100%;overflow: hidden;">
                                    <div class="carousel_item active">
                                        <a class="dropdown-item">Profile</a>
                                        <a class="dropdown-item inactive">Manage System</a>
                                    </div>
                                    <div class="carousel_item active">
                                        <a href="../logout.php" class="dropdown-item">Log out</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const dropdownButton = document.getElementById('dropdown-button');
                const dropdownMenu = document.getElementById('dropdown-menu');

                dropdownButton.addEventListener('click', function(event) {
                    event.stopPropagation(); // 防止点击事件冒泡到文档，导致立即关闭
                    if (dropdownMenu.classList.contains('show')) {
                        dropdownMenu.classList.remove('show');
                    } else {
                        dropdownMenu.classList.add('show');
                    }
                });

                document.addEventListener('click', function() {
                    if (dropdownMenu.classList.contains('show')) {
                        dropdownMenu.classList.remove('show');
                    }
                });
            });
        </script>
    </div>
</body>
</html>
