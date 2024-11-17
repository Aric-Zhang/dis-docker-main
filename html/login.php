<?php
include 'environment_constants.php';

// MySQL database information
$servername = "mariadb";
$username = "root";
$password = "rootpwd";
$dbname = "coursework2";

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

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($pass_input === $row[PASSWORD]) {
        header("Location: coursework2/navi_page.php");
        session_start();
        $_SESSION[USERNAME] = $user_input;
        $_SESSION[USERID] = $row[USERID];
    } else {
        header("Location: login_page.php?".ALERT."=".ALERT_INVALID_LOGIN);
    }
} else {
    header("Location: login_page.php?".ALERT."=".ALERT_INVALID_LOGIN);
}

$stmt->close();
$conn->close();

?>
