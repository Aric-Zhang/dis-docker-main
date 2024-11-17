<?php
include 'environment_constants.php';
session_start();
if (!isset($_SESSION[USERNAME])) {
    header("Location: login_page.php");
    die();
}
?>
<a href="logout.php">Log out</a>
<h1>Form Test</h1>
<form method="get">
    Enter your name: <input type="text" name="yourname">
    <input type="submit" value="Say Hello">
</form>
<?php
if (isset($_GET['yourname']))
    echo "Hello <strong>".$_GET['yourname']."</strong>";
$my_array = array(1, 2, 3);
rsort($my_array);
echo __FILE__;
echo '<br/>'
?>
<?php
// MySQL database information
$servername = "mariadb";
$username = "root";
$password = "rootpwd";
$dbname = "exercise_8";
?>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #000;
        padding: 0;
        text-align: center;
        background-color: orange; /* 设置单元格背景颜色为橙色 */
    }
    th {
        background-color: #f2f2f2; /* 保持表头背景颜色不变 */
    }
    .orange-bg {
        background-color: orange; /* 橙色背景 */
    }
    .red-bg {
        background-color: red; /* 红色背景 */
    }
    .full-cell-button {
        width: 100%;
        height: 100%;
        border-radius: 0;
        font-size: 16px;
        cursor: pointer;
    }
    .full-cell-button:hover {
        background-color: #ffffff;
    }
</style>
<?php
$conn = mysqli_connect($servername, $username, $password, $dbname);
// other code here!
if(mysqli_connect_errno())
{
    echo "Failed to connect to  
          MySQL:".mysqli_connect_error();
    die();
}
else
    echo "MySQL connection OK<br/><br/>";
// construct the SELECT query
$sql = "SELECT * FROM contact WHERE 1 ORDER BY name;";
// send query to database
$result = mysqli_query($conn, $sql);
echo mysqli_num_rows($result)." rows<br/>";
if(mysqli_num_rows($result) == 0)
    echo "Database is empty";

$table_head=<<<EOT
<thead>

</thead>
EOT;
echo "<table>";
echo $table_head;
echo "<tbody>";
while($row = mysqli_fetch_assoc($result))
{
    echo "<tr>";
    echo "<td>".$row["name"]."</td>";
    echo "<td> (phone: ".$row["phone_number"].") </td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
?>
<h1>Phone List</h1>
<form method="POST">
    Name: <input type="text" name="name"><br/>
    Phone: <input type="text" name="phone"><br/>
    <input type="submit" value="Add Record">
</form>
<hr/>
<script>
    // Function to remove the link from the page
    function deleteLink(linkId) {
        var link = document.getElementById(linkId);
        link.style.display = 'none'; // Hide the link
    }
</script>
<?php
if(isset($_POST['name'] )) {
    if ($_POST['name'] != "" && $_POST['phone'] != "") {
        echo "name: " . $_POST['name'] . " phone:              
         " . $_POST['phone'] . "<br/>";
        $sql = "INSERT INTO contact(name, phone_number) 
                    VALUES ('".$_POST['name']."',".$_POST['phone'].");";
        $result = mysqli_query($conn, $sql);
    }
}
$sql = "SELECT * FROM contact WHERE 1 ORDER BY name;";
// send query to database
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) == 0)
    echo "Database is empty";
echo "<table>";
echo "<tbody>";
while($row = mysqli_fetch_assoc($result))
{
    $id = $row["id"];
    $element_id = 'row'.$id;
    echo "<tr id='$element_id'>";
    echo "<td>".$row["name"]."</td>";
    echo "<td> (phone: ".$row["phone_number"].") </td>";
    //echo "<a href='?del=$id' onclick='deleteLink(\"$element_id\"); return false;'>delete</a>";
    echo "<td class='red-bg'><a href='?del=$id'><button class='full-cell-button'>delete</button></a></td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
if (isset($_GET['del']) && $_GET['del']!="")
{
    $id = $_GET['del'];
    $sql = "DELETE FROM contact 
            WHERE id=".$id.";";
    $result = mysqli_query($conn, $sql);
    $element_id = 'row'.$id;
    echo"<script> deleteLink(\"$element_id\"); </script>";
}
mysqli_close($conn);
?>
