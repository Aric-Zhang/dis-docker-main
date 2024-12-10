<?php

function get_relative_path_from_root($file_path)
{
    return ltrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', $file_path), '/');
}
function get_relative_path_to_root($file_path)
{
    $curr_path_to_root = ltrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', $file_path), '/');
    $folders = explode('/', $curr_path_to_root);
    array_pop($folders);
    $relative_path_to_root = '';
    foreach ($folders as $folder) {
        $relative_path_to_root .= '../';
    }
    return $relative_path_to_root;
}
function get_relative_path($from_file_path, $to_file_path){
    $from_path_folders = explode('/', $from_file_path);
    $to_path_folders = explode('/', $to_file_path);

    if(count($from_path_folders) > 0 ){
        array_pop($from_path_folders);
    }
    $min_depth = min(count($from_path_folders), count($to_path_folders));
    $same_count = 0;
    for($i=0; $i<$min_depth; $i++){
        if($from_path_folders[$i] == $to_path_folders[$i]){
            $same_count++;
        }
    }
    $relative_path = "";
    for($i = $same_count; $i < count($from_path_folders); $i++){
        $relative_path .= "../";
    }
    for($i = $same_count; $i < count($to_path_folders); $i++){
        $relative_path .= $to_path_folders[$i];
        if($i < count($to_path_folders)-1){
            $relative_path .= "/";
        }
    }
    return $relative_path;
}
function getBaseUrl() {
    // 获取协议（http 或 https）
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
    } elseif (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $protocol = "https";
    } else {
        $protocol = "http";
    }

    // 获取主机名
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    } elseif (isset($_SERVER['HTTP_HOST'])) {
        $host = $_SERVER['HTTP_HOST'];
    } else {
        $host = $_SERVER['SERVER_NAME'];
    }

    // 获取请求 URI 的路径部分（去掉查询字符串）
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // 构建基础 URL
    $baseUrl = "$protocol://$host$uri";

    return $baseUrl;
}
function start_mysql_connection(){
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
    return $conn;
}
function end_mysql_connection($conn){
    mysqli_close($conn);
}
function store_alert_message($message){
    if(isset($_SESSION["message"])){
        $_SESSION["message"] = $_SESSION["message"].$message."<br>";
    }
    else {
        $_SESSION['message'] = $message."<br>";
    }
}
function fetch_alert_message(){
    if(isset($_SESSION['message'])){
        $message = $_SESSION['message'];
        unset($_SESSION['message']); // 清除会话中的消息
        return $message;
    }
    return null;
}
function get_current_user_id($conn){
    if(isset($_SESSION[USERNAME])){
        $username = $_SESSION[USERNAME];
        $stmt = $conn->prepare("SELECT ID FROM User WHERE Username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user_id = $row["ID"];
                $stmt->close();
                return $user_id;
            }
        }
        $stmt->close();
    }
    return null;
}
function record_insert_people($conn, $people_id, $people_name, $people_licence){
    $user_id = get_current_user_id($conn);
    $modification_type = 'Insert';
    $current_time = date('Y-m-d H:i:s');
    $modification_table = 'People';
    $modification_description = "Inserted People ".$people_name." ".$people_licence;

    $stmt = $conn->prepare("INSERT INTO Modification (User_ID, Modification_type, Modification_datetime, Modification_table, Modification_description, Modification_ref_ID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $user_id, $modification_type, $current_time, $modification_table, $modification_description, $people_id);
    $stmt->execute();
    $operation_audit_trail_id = $conn->insert_id;
    $stmt->close();
    store_alert_message($modification_description);
}
function record_insert_vehicle($conn, $vehicle_id, $vehicle_make, $vehicle_model, $vehicle_plate){
    $user_id = get_current_user_id($conn);
    $modification_type = 'Insert';
    $current_time = date('Y-m-d H:i:s');
    $modification_table = 'Vehicle';
    $modification_description = "Inserted Vehicle ".$vehicle_make." ".$vehicle_model." ".$vehicle_plate;

    $stmt = $conn->prepare("INSERT INTO Modification (User_ID, Modification_type, Modification_datetime, Modification_table, Modification_description, Modification_ref_ID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $user_id, $modification_type, $current_time, $modification_table, $modification_description, $vehicle_id);
    $stmt->execute();
    $operation_audit_trail_id = $conn->insert_id;
    $stmt->close();
    store_alert_message($modification_description);
}
function record_insert_incident($conn, $incident_id, $vehicle_id, $people_id, $offence_id, $date){
    $user_id = get_current_user_id($conn);
    $modification_type = 'Insert';
    $current_time = date('Y-m-d H:i:s');
    $modification_table = 'Incident';

    $vehicle_plate = '';
    $people_name = '';
    $offence_description = '';

    $stmt = $conn->prepare("SELECT Vehicle_plate FROM Vehicle WHERE Vehicle_ID = ?");
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $vehicle_plate = $row["Vehicle_plate"];
        }
    }

    $stmt = $conn->prepare("SELECT People_name FROM People WHERE People_ID = ?");
    $stmt->bind_param("i", $people_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $people_name = $row["People_name"];
        }
    }

    $stmt = $conn->prepare("SELECT Offence_description FROM Offence WHERE Offence_ID = ?");
    $stmt->bind_param("i", $offence_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $offence_description = $row["Offence_description"];
        }
    }

    $modification_description = 'Inserted Incident '.$people_name.' '.$offence_description.' for vehicle '.$vehicle_plate.' on '.$date;

    $stmt = $conn->prepare("INSERT INTO Modification (User_ID, Modification_type, Modification_datetime, Modification_table, Modification_description,Modification_ref_ID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $user_id, $modification_type, $current_time, $modification_table, $modification_description, $incident_id);
    $stmt->execute();
    $operation_audit_trail_id = $conn->insert_id;
    $stmt->close();
    store_alert_message($modification_description);
}
function record_update_fine($conn, $fine_id, $col_name, $value){
    $user_id = get_current_user_id($conn);
    $modification_type = 'Update';
    $current_time = date('Y-m-d H:i:s');
    $modification_table = 'Fines';
    $offence_description = '';

    $stmt = $conn->prepare("SELECT Offence_description FROM Offence WHERE Offence_ID = (SELECT Offence_ID FROM Incident WHERE Incident_ID = (SELECT Incident_ID FROM Fines WHERE Fine_ID = ?))");
    $stmt->bind_param("i", $fine_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $offence_description = $row["Offence_description"];
        }
    }

    $modification_description = "Updated fine ";
    if($col_name == "Fine_Amount"){
        $modification_description.="amount for ".$offence_description." to ".$value;
    }
    elseif ($col_name == "Fine_Points"){
        $modification_description.="points for ".$offence_description." to ".$value;;
    }

    $stmt = $conn->prepare("INSERT INTO Modification (User_ID, Modification_type, Modification_datetime, Modification_table, Modification_description, Modification_ref_ID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $user_id, $modification_type, $current_time, $modification_table, $modification_description, $fine_id);
    $stmt->execute();
    $operation_audit_trail_id = $conn->insert_id;
    $stmt->close();
    store_alert_message($modification_description);
}
function record_insert_fine($conn, $fine_id, $col_name, $value){
    $user_id = get_current_user_id($conn);
    $modification_type = 'Insert';
    $current_time = date('Y-m-d H:i:s');
    $modification_table = 'Fines';
    $offence_description = '';

    $stmt = $conn->prepare("SELECT Offence_description FROM Offence WHERE Offence_ID = (SELECT Offence_ID FROM Incident WHERE Incident_ID = (SELECT Incident_ID FROM Fines WHERE Fine_ID = ?))");
    $stmt->bind_param("i", $fine_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $offence_description = $row["Offence_description"];
        }
    }

    $modification_description = "Associate fine for ".$offence_description;

    $stmt = $conn->prepare("INSERT INTO Modification (User_ID, Modification_type, Modification_datetime, Modification_table, Modification_description,Modification_ref_ID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $user_id, $modification_type, $current_time, $modification_table, $modification_description, $fine_id);
    $stmt->execute();
    $operation_audit_trail_id = $conn->insert_id;
    store_alert_message($modification_description);

    $modification_type = 'Update';
    if($col_name == "Fine_Amount"){
        $modification_description="Update fine amount for ".$offence_description." to ".$value;
    }
    elseif ($col_name == "Fine_Points"){
        $modification_description="Update points for ".$offence_description." to ".$value;;
    }
    $stmt = $conn->prepare("INSERT INTO Modification (User_ID, Modification_type, Modification_datetime, Modification_table, Modification_description,Modification_ref_ID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $user_id, $modification_type, $current_time, $modification_table, $modification_description, $fine_id);
    $stmt->execute();

    $stmt->close();
    store_alert_message($modification_description);

}
function record_search($conn, $modification_table, $search_type, $search_text){
    $user_id = get_current_user_id($conn);
    $modification_type = 'Other';
    $current_time = date('Y-m-d H:i:s');
    $modification_description = "Searched ".$modification_table." by ".$search_type." using input \"".$search_text."\"";

    $stmt = $conn->prepare("INSERT INTO Modification (User_ID, Modification_type, Modification_datetime, Modification_table, Modification_description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $modification_type, $current_time, $modification_table, $modification_description);
    $stmt->execute();
    $operation_audit_trail_id = $conn->insert_id;
    $stmt->close();
}

?>