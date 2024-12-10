<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'navi_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'grid_container.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'modal.php';

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
    <title>DIS Account Page</title>
    <style>
        @import "../css/dis_cw2_common.css";
        .form-control-normal{
            height: 2.5rem;
            border-color: #999999;
            padding: .5rem 1rem;
        }

        .audit_trail_simple{
            width: 100%;
            border-collapse: collapse;
        }

        .audit_trail_simple th, .audit_trail_simple td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eaeaea;
            user-select: none;
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
<?php
$modal_id = "myModal";
$button_id_modal_close = "close_modal_button";
$button_id_modal_open = "change_password_button";
$button_id_modal_cancel = "password-change-cancel-button";
start_modal($modal_id , $button_id_modal_close, "Change Your Password");
?>
                        <form action="<?php echo get_relative_path_to_root(__FILE__).CHANGE_PASSWORD_PATH?>" class="login-form" method="post">
                            <div class="login-form-password form-group">
                                <div style="display: flex; flex-direction: row;">
                                    <label class="password-change-star-label">*</label>
                                    <label class="password-change-input-label">Old password:</label>
                                    <input type="password" name=<?php echo PASSWORD; ?> class="form-control form-control-normal" placeholder="Type in your old password">
                                    <label class="password-change-mark-label" style="color: green;"></label>
                                </div>
                            </div>
                            <p style="color: dimgrey;">Your new password should be at least 6 digits long</p>
                            <div class="login-form-password form-group">
                                <div class="password-change-row-wrapper">
                                    <label class="password-change-star-label">*</label>
                                    <label class="password-change-input-label">New password:</label>
                                    <input type="password" id="new_password_input" name=<?php echo NEW_PASSWORD; ?> class="form-control form-control-normal" placeholder="Type in your new password">
                                    <label id="new_password_mark" class="password-change-mark-label"></label>
                                </div>
                            </div>
                            <div class="login-form-password form-group">
                                <div class="password-change-row-wrapper">
                                    <label class="password-change-star-label">*</label>
                                    <label class="password-change-input-label">Confirm new password:</label>
                                    <input type="password" id="confirm_new_password_input" name=<?php echo CONFIRM_PASSWORD; ?> class="form-control form-control-normal" placeholder="Type in your new password again">
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

                            </script>
                        </form>
<?php
end_modal($modal_id, $button_id_modal_close);
bind_modal_to_cancel_button($modal_id, $button_id_modal_cancel);
bind_modal_to_open_button($modal_id, $button_id_modal_open);
?>
                        <div style="margin: 0.5rem;"></div>
                    </div>
                </div>
            </div>
            <div class="right-column">
                <div class="profile_in_column_block">
                    <div class="profile_in_block_column_wrapper">
                        <span class = "profile_block_title">Recent Operation History</span>
                        <?php

                        $conn = start_mysql_connection();
                        $user_id = get_current_user_id($conn);
                        $stmt = $conn->prepare("SELECT * FROM `Modification` WHERE `User_ID`=? ORDER BY Modification_ID DESC LIMIT 6");
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $modification_name_to_alias_array = array("Modification_datetime"=>"Time","Modification_description"=>"Description");
                        echo "<table class='audit_trail_simple'>";
                        echo "<thead>";
                        foreach ($modification_name_to_alias_array as $key => $value) {
                            echo "<th>".$value."</th>";
                        }
                        echo "</thead>";
                        echo "<tbody>";
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                foreach ($modification_name_to_alias_array as $key => $value) {
                                    echo "<td>" . $row[$key] . "</td>";
                                }
                                echo "</tr>";
                            }
                        }

                        echo "</tbody>";
                        echo "</table>";
                        $stmt->close();
                        end_mysql_connection($conn);
                        ?>
                        <div style="margin: 0.5rem;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>