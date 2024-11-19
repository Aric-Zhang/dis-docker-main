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
        @import "../css/dis_cw2_common.css";

        .profile-container{
            display: flex;
            width: 100%;
            max-width: 60em;
            padding: 10px; /* 内边距 */

        }
        .profile-wrapper{
            display: flex;
            flex-direction: row;
            width: 100%;
            justify-content: center;
        }
        .left-column {
            flex: 2; /* 左侧列的宽度为总宽度的三分之二 */
            padding: 0;
            box-sizing: border-box; /* 包括内边距和边框在内的宽度计算 */
        }

        .right-column {
            flex: 3; /* 右侧列的宽度为总宽度的三分之一 */
            padding: 0;
            box-sizing: border-box; /* 包括内边距和边框在内的宽度计算 */
        }
        .profile_in_column_block{
            margin: 1rem 0.5rem;
            border: 1px solid #ccc;
        }
        .profile_in_block_column_wrapper{
            display: flex;
            flex-direction: column;
            align-items: center
        }
        .profile_in_block_row_wrapper{
            flex-direction: row;
            width: 100%
        }
        .profile_user_icon{
            width: 7rem;
            height: 7rem;
            font-size: 2rem;
            border-radius: 50%;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0;
        }
        .profile_in_block_spacing{
            margin: 1.5rem;
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
    <div class="profile-wrapper">
        <div class = 'profile-container'>
            <div class="left-column">
                <div class="profile_in_column_block">
                    <div class="profile_in_block_spacing"></div>
                    <div class="profile_in_block_column_wrapper">
                        <div class="profile_in_block_row_wrapper">
                        </div>
                        <span class="profile_user_icon"><?php echo strtoupper($_SESSION[USERNAME][0]) ?></span>
                        <div style="font-size: 2rem; margin-top: 1rem;">
                            <?php echo $_SESSION['Username'] ?>
                        </div>
                        <div style="font-size: 1.2rem; color: dimgrey;">
                            <?php
                                $authority = $_SESSION[AUTHORITY];
                                echo $authority;
                            ?>
                        </div>
                        <div style="font-size: 1.2rem; color: dimgrey;">
                            Other descriptions
                        </div>
                    </div>
                    <div class="profile_in_block_spacing"></div>
                </div>
                <div class="profile_in_column_block">
                    Left content
                </div>
            </div>
            <div class="right-column">
                <div class="profile_in_column_block">
                    Right content
                </div>
            </div>
        </div>
    </div>

</div>
</body>
</html>