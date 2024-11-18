<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'navi_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'grid_container.php';

session_start();
if (!isset($_SESSION[USERNAME])) {
    $login_relative_path = get_relative_path(get_relative_path_from_root(__FILE__), LOGIN_PAGE_PATH);
    header("Location: ".$login_relative_path);
    die();
}
?>
<html lang="en-GB">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIS Entrance Page</title>
    <style>
        @import "../../css/dis_cw2_common.css";
    </style>
</head>
<body>
<?php
render_navi_bar(__FILE__);
?>
<?php
?>
<div class="main_page_wrapper">
    <?php
    $grid_item_info_array = array(
        array("icon_file_name"=>'person_icon.png',"text"=>'Search Person',"href"=>"#"),
        array("icon_file_name"=>'person_icon.png',"text"=>'Search Vehicle',"href"=>"#"),
        array("icon_file_name"=>'person_icon.png',"text"=>'Add Person',"href"=>"#"),
        array("icon_file_name"=>'person_icon.png',"text"=>'Add Vehicle',"href"=>"#"),
    ) ;
    render_grid(__FILE__, $grid_item_info_array);
    ?>
</div>
</body>
</html>