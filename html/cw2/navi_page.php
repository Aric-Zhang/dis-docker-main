<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
include_once 'navi_bar.php';

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
$username = $_SESSION[USERNAME];
$authority = $_SESSION[AUTHORITY];
render_navi_bar($username, $authority);
?>
<div class="main_page_wrapper">
    <div class="grid-container">
        <a href="logout.php" class="grid-item btn">
            <img src="../images/person_icon.png" class="grid-item-icon" alt="icon">
            Search People</a>
        <div class="grid-item">
            <img src="../images/person_icon.png" class="grid-item-icon" alt="icon">
            Search Vehicle</div>
        <div class="grid-item">
            <img src="../images/traffic_icon.png" class="grid-item-icon" alt="icon">
            Add Vehicle</div>
        <div class="grid-item">
            <img src="../images/traffic_icon.png" class="grid-item-icon" alt="icon">
            Update Vehicle Info</div>
        <div class="grid-item">
            <img src="../images/traffic_icon.png" class="grid-item-icon" alt="icon">
            Report an Incident</div>
        <div class="grid-item">
            <img src="../images/traffic_icon.png" class="grid-item-icon" alt="icon">
            Search an Incident</div>
        <div class="grid-item">
            <img src="../images/traffic_icon.png" class="grid-item-icon" alt="icon">
            Manage Accounts</div>
        <div class="grid-item">
            <img src="../images/traffic_icon.png" class="grid-item-icon" alt="icon">
            Audit Trail</div>
    </div>
</div>
</body>
</html>
