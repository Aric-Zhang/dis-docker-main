<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php_utils.php';

session_start();
if (!isset($_SESSION[USERNAME]) ||
    !isset($_POST[PASSWORD]) ||
    !isset($_POST[CONFIRM_PASSWORD]) ||
    !isset($_POST[NEW_PASSWORD])  ){
    $login_relative_path = get_relative_path(get_relative_path_from_root(__FILE__), LOGIN_PAGE_PATH);
    header("Location: ".$login_relative_path);
    die();
}

$user_input = $_SESSION[USERNAME];
$pass_input = $_POST[PASSWORD];
$new_pass_input = $_POST[NEW_PASSWORD];
$confirm_pass_input = $_POST[CONFIRM_PASSWORD];

// MySQL database information
$servername = "mariadb";
$username = "root";
$password = "rootpwd";
$dbname = "cw2-database";

$conn = mysqli_connect($servername, $username, $password, $dbname);
// other code here!
if(mysqli_connect_errno())
{
    echo "Failed to connect to  
          MySQL:".mysqli_connect_error();
    die();
}

$stmt = $conn->prepare("SELECT * FROM User WHERE Username = ?");
$stmt->bind_param("s", $user_input);
$stmt->execute();
$result = $stmt->get_result();
$filepath_relative_from_root = get_relative_path_from_root(__FILE__);

$success = false;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($pass_input === $row[PASSWORD] && $new_pass_input === $confirm_pass_input && strlen($new_pass_input >= 6)) {
        $stmt = $conn->prepare("UPDATE User SET Password = ? WHERE Username = ?");
        if($stmt !== false){
            $stmt->bind_param("ss", $new_pass_input, $user_input);
            $success = $stmt->execute();
            if($success) {
                header("Location: " . get_relative_path($filepath_relative_from_root, CHANGE_PASSWORD_PAGE_PATH) . "?" . ALERT . "=" . ALERT_CHANGE_PASSWORD_SUCCESS);
                session_unset();
                session_destroy();
            }
        }
    }
}
if(!$success){
    header("Location: ".get_relative_path($filepath_relative_from_root, CHANGE_PASSWORD_PAGE_PATH)."?".ALERT."=".ALERT_CHANGE_PASSWORD_FAIL);
}

$stmt->close();
$conn->close();

?>