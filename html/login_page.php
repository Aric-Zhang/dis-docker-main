<html lang="en-GB">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIS Entrance Page</title>
    <style>
        @import "css/dis_cw2_common.css";
        .login-container .login-logo {
            justify-content: left;
            margin-bottom: 3rem;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-form">
                <div class="login-logo">
                    <img class="img-fluid" src="images/UoN-Nottingham-Blue.png" alt="Logo Image">
                </div>
                <form action="login.php" class="login-form" method="post">

<?php
include 'environment_constants.php';
$invalid_login_alert_message = <<<EOT
                    <div class="alert alert-danger">
                        Wrong username or password, please try again
                    </div>
EOT;
if (isset($_GET[ALERT])){
    $alert_type = $_GET[ALERT];
    if($alert_type == ALERT_INVALID_LOGIN){
        echo $invalid_login_alert_message;
    }
}
?>

                    <div class="login-form-username form-group">
                        <input type="text" name=<?php echo USERNAME; ?> class="form-control form-control-lg" placeholder="Username">
                    </div>
                    <div class="login-form-password form-group">
                        <input type="password" name=<?php echo PASSWORD; ?> class="form-control form-control-lg" placeholder="Password">
                    </div>
                    <div class="login-form-submit form group">
                        <button class="btn btn-primary btn-lg" type="submit">Log in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>