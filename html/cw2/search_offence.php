<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';

session_start();
if (!isset($_SESSION[USERNAME])) {
    $login_relative_path = get_relative_path(get_relative_path_from_root(__FILE__), LOGIN_PAGE_PATH);
    header("Location: ".$login_relative_path);
    die();
}
header('Content-Type: application/json');
?>
<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $data = json_decode(file_get_contents('php://input'), true);

    //Column name to shown name
    $id_column_name = 'Offence_ID';
    $conn = start_mysql_connection();
    $name_input = $data["search_offence_text"];
    $name_cond = "%".$name_input."%";
    $search_type = $data["search_offence_type"];
    if($search_type=="description") {
        $stmt = $conn->prepare("SELECT * FROM Offence WHERE Offence_description LIKE ?");
        $stmt->bind_param("s", $name_cond);
    }
    else if($search_type=="id"){
        $stmt = $conn->prepare("SELECT * FROM Offence WHERE Offence_ID = ?");
        $stmt->bind_param("i", $name_input);
    }
    else{
        $stmt = $conn->prepare("SELECT * FROM Offence WHERE Offence_description LIKE ?");
        $stmt->bind_param("s", $name_cond);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $caption = "Found ".$result->num_rows." matched results";
    }

    $offence = [];

    while ($row = $result->fetch_assoc()) {
        $offence[] = $row;
    }

    $stmt->close();
    end_mysql_connection($conn);
    echo json_encode(['error' => false, 'results' => $offence]);

}
else{
    echo json_encode(['error' => true]);
}

?>
