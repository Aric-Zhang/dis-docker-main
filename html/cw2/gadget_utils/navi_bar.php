<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';

function render_navi_bar($file_path){
    $username = $_SESSION[USERNAME];
    $authority = $_SESSION[AUTHORITY];
    $logo_image_path = get_relative_path_to_root($file_path).IMAGE_DIR."tvis_navi.png";
    $logout_path = get_relative_path_to_root($file_path).LOGOUT_PATH;
    $navi_page_path = get_relative_path_to_root($file_path).NAVI_PAGE_PATH;
    $profile_path = get_relative_path_to_root($file_path).PROFILE_PAGE_PATH;
    $user_capital = '';
    if($username != "")
        $user_capital = strtoupper($username[0]);
    else{
        echo __FUNCTION__;
        echo "Username can't be empty";
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
            <div style="background-color: #FDFBF9; width: 100%">
                <ul class="navbar-nav" style="margin-top: 0; margin-left: 10px; flex-direction: row;"> 
                    <li class="nav-item">
                        <a href="$navi_page_path" class="nav-item-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-item-link">More</a>
                    </li>
                </ul>
            </div>
            <div class="navbar-nav ml-auto">
                <div class="display-flex align-items-stretch" style="height: 100%">
                    <div class="usermenu">
                        <div class="dropdown">
                            <a id="dropdown-button" role="button" class="btn dropdown-toggle">
                                <span class="userbutton">$user_capital</span>
                                <span class="arrow-down"></span>
                            </a>
                            <div id="dropdown-menu" class="dropdown-menu dropdown-menu-right">
                                <div style="position: relative;width: 100%;overflow: hidden;">
                                    <div class="carousel_item active">
                                        <a href="$profile_path" class="dropdown-item">Profile</a>
                                        <a class="dropdown-item $manage_dropmenu_item_inactive_class">Manage System</a>
                                    </div>
                                    <div class="carousel_item active">
                                        <a href="$logout_path" class="dropdown-item">Log out</a>
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
