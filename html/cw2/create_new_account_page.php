<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'navi_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'grid_container.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'modal.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'search_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'form_elements.php';

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
    <title>Create New User Account</title>
    <style>
        @import "../css/dis_cw2_common.css";

    </style>
</head>
<body>
<?php
render_navi_bar(__FILE__);
?>
<div class="main_page_wrapper">
    <div class = "add_form_container">
        <h2>Create New User Account</h2>
        <script src="../js/dynamic_form_elements.js">
        </script>
        <form id="dynamic-form" class="login-form" method="post">
            <p style="color: dimgrey;">Fields marked with an <span style="color: red;">*</span> are required</p>
            <div style="margin: 1rem"></div>
            <div class="alert alert-danger hidden" id="usernameWarning">
                Username already exists
            </div>
            <?php

            $input_name = 'username';
            $username_wrapper_id = 'username_wrapper';
            $username_checkmark_id = 'username_checkmark';
            render_form_input($username_wrapper_id, '*', 'User Name', $input_name, 'text', $username_checkmark_id, 'Input new username');
            render_space_html();
            ?>
            <p style="color: dimgrey;">Password must be at least 6 digits long</p>
            <div style="margin: 1rem"></div>
            <?php
            $password_input_name = 'password';
            $password_input_wrapper_id = 'password_wrapper';
            $password_checkmark_id = 'password_checkmark';
            render_form_input($password_input_wrapper_id, '*', 'Password', $password_input_name, 'password', $password_checkmark_id, 'Input password');
            render_space_html();
            $confirm_password_input_name = 'confirm_password';
            $confirm_password_input_wrapper_id = 'confirm_password_wrapper';
            $confirm_password_checkmark_id = 'confirm_password_checkmark';
            render_form_input($confirm_password_input_wrapper_id, '*', 'Confirm Password', $confirm_password_input_name, 'password', $confirm_password_checkmark_id, 'Input password again');
            render_space_html();
            ?>
            <script>
                function first_child_input(element){
                    for (let i = 0; i < element.children.length; i++) {
                        child = element.children[i];
                        if(child.tagName.toLowerCase() === 'input'){
                            return child
                        }
                    }
                    return null
                }
            </script>
            <div class="login-form-submit form group">
                <div class="password-change-row-wrapper">
                    <button id="password-change-confirm-button" class="btn btn-primary btn_generic_form" type="submit" disabled>Create</button>
                    <div style="width: 2rem;"></div>
                    <button id="password-change-cancel-button" class="btn btn-primary btn_generic_form btn_generic_form_cancel" type="reset">Cancel</button>
                </div>
            </div>
            <?php
            $confirm_password_doc = <<<EOT
            <script>
                const username_input = first_child_input(document.getElementById('$username_wrapper_id'));
                const username_warning = document.getElementById('usernameWarning');
                var new_password_input = first_child_input(document.getElementById("$password_input_wrapper_id"));
                var confirm_new_password_input = first_child_input(document.getElementById("$confirm_password_input_wrapper_id"));
                var submit_change_password_button = document.getElementById("password-change-confirm-button");

                const username_checkmark = document.getElementById('$username_checkmark_id');
                var new_password_mark = document.getElementById("$password_checkmark_id");
                var confirm_new_password_mark = document.getElementById("$confirm_password_checkmark_id");
                
                var username_valid = false
                
                function set_username_valid(valid){
                    if(!valid){
                        if(username_warning.classList.contains('hidden')){
                            username_warning.classList.remove('hidden');
                        }
                        username_checkmark.textContent = " ";
                    }
                    else{
                        if(!username_warning.classList.contains('hidden')){
                            username_warning.classList.add('hidden');
                        }
                        username_checkmark.textContent = "✓";
                    }
                    username_valid = valid;
                }

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
                        confirm_new_password_mark.textContent="✓";
                    }
                    else{
                        confirm_new_password_mark.textContent=" ";
                    }
                    submit_change_password_button.disabled = !(new_password.length >= 6 && new_password === confirm_new_password && username_valid); 
                }
                
                function validate_username_string(username){
                    if (username === '') {
                        if(!username_warning.classList.contains('hidden')){
                            username_warning.classList.add('hidden');
                        }
                        username_checkmark.textContent = " ";
                        username_valid = false;
                        validate_confirm_new_passwords()
                        return;
                    }

                    fetch('check_username.php', {
                        method: 'POST',
                        headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'username=' + encodeURIComponent(username)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            set_username_valid(false)
                        } 
                        else {
                            set_username_valid(true)
                        }
                        validate_confirm_new_passwords()
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
                
                function validate_username(){
                    const username = this.value.trim();
                    validate_username_string(username);
                }
                
                username_input.addEventListener('input', validate_username);
                new_password_input.addEventListener('input', validate_confirm_new_passwords);
                new_password_input.addEventListener('input', validate_new_passwords);
                confirm_new_password_input.addEventListener('input', validate_confirm_new_passwords);
            </script>
EOT;
            echo $confirm_password_doc;

            ?>
        </form>
    </div>
</div>
<?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_SESSION[AUTHORITY]) && $_SESSION[AUTHORITY] == AUTHORITY_ADMIN){
        $conn = $conn = start_mysql_connection();
        $username = $_POST[$input_name];
        $password = $_POST[$password_input_name];
        $confirm_password = $_POST[$confirm_password_input_name];

        $password_valid = strlen($password) >= 6 && $password == $confirm_password;
        $username_valid = false;
        if($password_valid && $username != '') {
            $stmt = $conn->prepare("SELECT * FROM User WHERE Username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if(!$result->num_rows > 0){
                $username_valid = true;
            }
            else{
                $stmt->close();
            }
        }
        $authority = "PoliceOfficer";
        if($password_valid && $username_valid){
            $stmt = $conn->prepare("INSERT INTO User (Username, Password, Authority) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password, $authority);
            $stmt->execute();
            $id = $conn->insert_id;
            $stmt->close();
        }

        end_mysql_connection($conn);
    }
}
?>
</body>
</html>
