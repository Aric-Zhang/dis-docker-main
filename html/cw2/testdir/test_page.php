<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
include_once '../gadget_utils/navi_bar.php';
include_once  '../gadget_utils/grid_container.php';

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
        @import "../../css/dis_cw2_common.css";
        .grid-container {
            display: grid;
            gap: 10px;
            padding: 10px;
            /* 初始网格模板 */
            grid-template-columns: repeat(auto-fill, 260px);
            justify-content: start; /* 左对齐 */
        }
        .grid-item {
            background-color: #16417C;
            border: 1px solid #16417C;
            color: white;
            text-align: end;
            white-space: nowrap;
            font-size: 20px;
            width: 240px; /* 固定宽度 */
            height: 200px; /* 固定高度 */
            margin-top: 20px;
            padding: 25px;
            display: flex;
            flex-flow: column;
            align-items: start;
            justify-content: space-between; /* 左对齐 */
        }
        .grid-item-icon{
            max-height: 65px;
            margin-bottom: 10px
        }
        .main_page_wrapper{
            display: flex;
            flex-direction: column;
            margin-left: 0;
            margin-right: 0;
            margin-top: 60px;
            padding-left: 1rem;
        }

    </style>
</head>
<body>
<?php
render_navi_bar(__FILE__);
?>
<?php
?>
<div class="main_page_wrapper">
    <?php render_grid(__FILE__); ?>
</div>
</body>
</html>
