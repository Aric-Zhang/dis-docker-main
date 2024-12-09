<?php
// check_username.php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';

session_start();
if (!isset($_SESSION[USERNAME])) {
    $login_relative_path = get_relative_path(get_relative_path_from_root(__FILE__), LOGIN_PAGE_PATH);
    header("Location: ".$login_relative_path);
    die();
}

header('Content-Type: application/json');

$conn = start_mysql_connection();

if ($conn->connect_error) {
    die(json_encode(['exists' => false, 'error' => 'Connection failed: ' . $conn->connect_error]));
}

$inputUsername = isset($_POST['username']) ? $_POST['username'] : '';

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM User WHERE Username = ?");
$stmt->bind_param("s", $inputUsername);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();
end_mysql_connection($conn);

echo json_encode(['exists' => $row['count'] > 0]);
?>