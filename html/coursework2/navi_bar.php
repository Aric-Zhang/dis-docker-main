<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';

function render_navi_bar($username, $authority){
    $logo_image_path = get_relative_path_to_root().IMAGE_DIR."tvis_navi.png";
    $user_capital = '';
    if($username != "")
        $user_capital = strtoupper($username[0]);
    else{
        echo __FUNCTION__;
        echo "User name can't be empty";
        die();
    }
    $manage_dropmenu_item_inactive_class='inactive';
    if($authority == AUTHORITY_ADMIN){
        $manage_dropmenu_item_inactive_class = '';
    }

    $navi_bar_doc = <<<EOT
    <div>
        <nav class="navbar fixed-top navbar-expand">
            <a class="align-items-center display-flex">
                <img src=$logo_image_path class="logo" alt="Traffic Violation Inquiry System Icon">
            </a>
            <div class="navbar-nav ml-auto">
                <div class="display-flex align-items-stretch" style="height: 100%">
                    <div class="usermenu">
                        <div class="dropdown">
                            <a id="dropdown-button" role="button" class="btn dropdown-toggle">
                                <button class="userbutton">$user_capital</button>
                                <span class="arrow-down"></span>
                            </a>
                            <div id="dropdown-menu" class="dropdown-menu dropdown-menu-right">
                                <div style="position: relative;width: 100%;overflow: hidden;">
                                    <div class="carousel_item active">
                                        <a class="dropdown-item">Profile</a>
                                        <a class="dropdown-item $manage_dropmenu_item_inactive_class">Manage System</a>
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
EOT;
    echo $navi_bar_doc;
}
?>
