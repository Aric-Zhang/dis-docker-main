<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';

$filepath_relative_from_root = get_relative_path_from_root(__FILE__);
$target_relative_path = get_relative_path($filepath_relative_from_root, LOGIN_PAGE_PATH);

$password_change_title = "";
$password_change_info = "";
if(isset($_GET[ALERT]) && $_GET[ALERT] == ALERT_CHANGE_PASSWORD_SUCCESS){
    $password_change_title = "Password changed successfully.";
    $password_change_info = "You will be redirected to login page ";
}
else{
    $password_change_title = "Failed to change password.";
    $password_change_info = "Please check that your entries are correct and compliant. You will jump back to home page ";
}
?>
<html lang="en-GB">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIS Entrance Page</title>
    <style>
        @import "../css/dis_cw2_common.css";

        .full_screen{
            style="width: 100%;
            height=100%;
            display: flex;
            align-items: center;
            justify-content: center;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            position: fixed;
        }
    </style>
</head>
<body>
    <div class="full_screen">
        <div class="login-container" style="flex-direction: column">
            <div style="display: flex; flex-direction: row;">
                <img class="img-fluid" src=" <?php echo get_relative_path($filepath_relative_from_root, IMAGE_DIR)."traffic_icon.png";?> " alt = "alert">
                <h1> <?php echo $password_change_title?> </h1>
            </div>
            <div style="display: flex; flex-direction: column;">
                <div style="font-size: 1.2rem; color: dimgrey;"><?php echo $password_change_info?> in <span id="countdown">5</span> seconds.</div>
                <a style="font-size: 1.2rem;" href="<?php echo $target_relative_path ?>">JUMP NOW</a>
            </div>
        </div>
        <script>
            let countdownTime = 5;

            const countdownElement = document.getElementById('countdown');

            function updateCountdown() {
                countdownElement.textContent = countdownTime;
                countdownTime--;

                if (countdownTime < 0) {
                    window.location.href = '<?php echo $target_relative_path ?>';
                } else {
                    setTimeout(updateCountdown, 1000);
                }
            }
            updateCountdown();
        </script>
    </div>
</body>
</html>