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
        .profile_block_title{
            width:100%;
            text-align: center;
            margin-bottom: 0;
            background-color: #118bee;
            color: white;
            font-size: 1.2rem;
        }

        .modal-background {
            display: none; /* 默认隐藏 */
            position: fixed; /* 固定位置 */
            z-index: 1; /* 确保在最上层 */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); /* 半透明背景 */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            padding: 1rem;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }

        /* Modal */
        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }
        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .text-center-label{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .password-change-input-label{
            display: flex;
            width: 12rem;
            align-items: center;
        }
        .password-change-star-label{
            display: flex;
            width: 1rem;
            align-items: center;
            color: red;
        }
        .password-change-row-wrapper{
            display: flex;
            flex-direction: row;
        }
        .password-change-mark-label{
            display: flex;
            width: 3rem;
            align-items: center;
            justify-content: end;
            font-size: 1.5rem;
            color: green;
        }
        .form-control-normal{
            height: 2.5rem;
            border-color: #999999;
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
                    <div class="profile_in_block_column_wrapper">
                        <span class = "profile_block_title">Account Security</span>
                        <div style="margin: 1rem; color: dimgrey;">We recommend changing your password at least every three months.</div>
                        <button id="change_password_button" class="btn btn-primary" style=" border-radius: 50px; padding-left: 5rem; padding-right: 5rem;">Change Password</button>
                        <div id="myModal" class="modal-background">
                            <div class="modal-content">
                                <span class="close-button">&times;</span>
                                <div style="margin: 2rem;">
                                <div style="height: 42px; font-size: 1.5rem; margin-bottom: 1rem;" class="text-center-label">
                                    Change Your Password
                                </div>
                                <form action="<?php echo get_relative_path_to_root(__FILE__).CHANGE_PASSWORD_PATH?>" class="login-form" method="post">
                                    <div class="login-form-password form-group">
                                        <div style="display: flex; flex-direction: row;">
                                            <label class="password-change-star-label">*</label>
                                            <label class="password-change-input-label">Old password:</label>
                                            <input type="password" name=<?php echo PASSWORD; ?> class="form-control form-control-lg form-control-normal" placeholder="Type in your old password">
                                            <label class="password-change-mark-label" style="color: green;"></label>
                                        </div>
                                    </div>
                                    <p style="color: dimgrey;">Your new password should be at least 6 digits long</p>
                                    <div class="login-form-password form-group">
                                        <div class="password-change-row-wrapper">
                                            <label class="password-change-star-label">*</label>
                                            <label class="password-change-input-label">New password:</label>
                                            <input type="password" id="new_password_input" name=<?php echo NEW_PASSWORD; ?> class="form-control form-control-lg form-control-normal" placeholder="Type in your new password">
                                            <label id="new_password_mark" class="password-change-mark-label"></label>
                                        </div>
                                    </div>
                                    <div class="login-form-password form-group">
                                        <div class="password-change-row-wrapper">
                                            <label class="password-change-star-label">*</label>
                                            <label class="password-change-input-label">Confirm new password:</label>
                                            <input type="password" id="confirm_new_password_input" name=<?php echo CONFIRM_PASSWORD; ?> class="form-control form-control-lg form-control-normal" placeholder="Type in your new password again">
                                            <label id="confirm_new_password_mark" class="password-change-mark-label"></label>
                                        </div>
                                    </div>
                                    <p style="color: dimgrey;">Fields marked with an <span style="color: red;">*</span> are required</p>
                                    <div class="login-form-submit form group">
                                        <div class="password-change-row-wrapper">
                                            <button id="password-change-confirm-button" class="btn btn-primary btn-lg" style="width: 100%" type="submit" disabled>Confirm</button>
                                            <div style="width: 2rem;"></div>
                                            <button id="password-change-cancel-button" class="btn btn-primary btn-lg" style="width: 100%; background-color: white; color: #16417C;" type="reset">Cancel</button>
                                        </div>
                                    </div>
                                    <script>
                                        var new_password_input = document.getElementById("new_password_input");
                                        var confirm_new_password_input = document.getElementById("confirm_new_password_input");
                                        var submit_change_password_button = document.getElementById("password-change-confirm-button");

                                        var new_password_mark = document.getElementById("new_password_mark");
                                        var confirm_new_password_mark = document.getElementById("confirm_new_password_mark");

                                        function validate_new_passwords(){
                                            var new_password = new_password_input.value.trim();
                                            if(new_password.length >= 6){
                                                new_password_mark.textContent="✓";
                                            }
                                            else{
                                                new_password_mark.textContent=" ";
                                            }
                                        }

                                        function validate_confirm_new_passwords(){
                                            var new_password = new_password_input.value.trim();
                                            var confirm_new_password = confirm_new_password_input.value.trim();

                                            if(new_password.length >= 6 && new_password === confirm_new_password){
                                                submit_change_password_button.disabled = false;
                                                confirm_new_password_mark.textContent="✓";
                                            }
                                            else{
                                                submit_change_password_button.disabled = true;
                                                confirm_new_password_mark.textContent=" ";
                                            }
                                        }

                                        new_password_input.addEventListener('input', validate_confirm_new_passwords);
                                        new_password_input.addEventListener('input', validate_new_passwords);
                                        confirm_new_password_input.addEventListener('input', validate_confirm_new_passwords);

                                        var modal = document.getElementById("myModal");
                                        var password_change_cancel_button = document.getElementById("password-change-cancel-button");
                                        password_change_cancel_button.onclick = function() {
                                            modal.style.display = "none";
                                        }

                                    </script>
                                </form>
                                </div>
                            </div>
                        </div>
                        <script>
                            var modal = document.getElementById("myModal");
                            var openBtn = document.getElementById("change_password_button");
                            var closeBtn = document.getElementsByClassName("close-button")[0];

                            openBtn.onclick = function(event) {
                                event.preventDefault();
                                modal.style.display = "flex";
                            }

                            closeBtn.onclick = function() {
                                modal.style.display = "none";
                            }

                            window.onclick = function(event) {
                                if (event.target == modal) {
                                    modal.style.display = "none";
                                }
                            }
                        </script>
                        <div style="margin: 0.5rem;"></div>
                    </div>
                </div>
            </div>
            <div class="right-column">
                <div class="profile_in_column_block">
                    <div class="profile_in_block_column_wrapper">
                        <span class = "profile_block_title">Operation History</span>
                        <ul>
                            <li>History</li>
                            <li>History</li>
                            <li>History</li>
                            <li>History</li>
                            <li>History</li>
                        </ul>
                        <div style="margin: 0.5rem;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>