<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php_utils.php';

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

$user_input = $_POST[USERNAME];
$pass_input = $_POST[PASSWORD];

$stmt = $conn->prepare("SELECT * FROM User WHERE Username = ?");
$stmt->bind_param("s", $user_input);
$stmt->execute();
$result = $stmt->get_result();
$filepath_relative_from_root = get_relative_path_from_root(__FILE__);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($pass_input === $row[PASSWORD]) {
        header("Location: ".get_relative_path($filepath_relative_from_root, NAVI_PAGE_PATH));
        session_start();
        $_SESSION[USERNAME] = $user_input;
        $_SESSION[USERID] = $row[USERID];
        $_SESSION[AUTHORITY] = $row[AUTHORITY];
    } else {
        header("Location: ".get_relative_path($filepath_relative_from_root, LOGIN_PAGE_PATH)."?".ALERT."=".ALERT_INVALID_LOGIN);
    }
} else {
    header("Location: ".get_relative_path($filepath_relative_from_root, LOGIN_PAGE_PATH)."?".ALERT."=".ALERT_INVALID_LOGIN);
}

$stmt->close();
$conn->close();

?>
