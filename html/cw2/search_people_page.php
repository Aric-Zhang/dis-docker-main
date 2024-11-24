<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'navi_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'grid_container.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'dropdown_menu.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'search_bar.php';

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
    <title>DIS Home Page</title>
    <style>
        @import "../css/dis_cw2_common.css";

        .general_form{
            margin: 1rem;
        }

        .form_control_search{
            height: calc(1.5em + 1rem + 2px);
            padding: .5rem 1rem;
            font-size: 1rem;
            border: 1px solid #CFD4D8;
        }

        .search_input_wrapper{
            display: flex;
            flex-direction: row;
        }

        .search_input_wrapper .search_input{
            border-radius: 0;
            border-right: 0;
            transition: none;
        }

        .search_input_wrapper .search_input:focus{
            border: 1px solid #CFD4D8;
            outline: none;
            box-shadow: none;
            border-right: 0;
        }

        .search_input_wrapper .search_button{
            border-radius: 0 50px 50px 0;
            border: 1px solid #16417C;
        }

        .search_input_wrapper .dropdown_button{
            border-radius:  50px 0 0 50px ;
            border: 1px solid #CFD4D8;
            border-right: 0;
            padding-right: 1.5rem;
            color: #516170;
            min-width: 13rem;
            position: relative;
            background-color: #FDFBF9;
        }

        .search_page_wrapper{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 0;
            color: #333333;
        }

        .search_res_table_container{
            width: 90%;
            max-width: 800px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            background-color: #ffffff;
        }

        .search_input_wrapper .dropdown_button:after {
            content: '';
            position: absolute;
            border: solid black;
            display: inline-block;
            padding: 3px;
            right: 0.5rem;
            top: calc(50% - 0.3rem);
            transform: rotate(45deg);
            border-width: 0 2px 2px 0;
        }


    </style>
</head>
<body>
<?php
render_navi_bar(__FILE__);
?>
<?php
?>
<div class="main_page_wrapper">
    <div>
        <?php

        $search_people_renderer = new SearchOptionRenderer();

        $link_id_1 = "search_type_general";
        $link_id_2 = "search_type_name";
        $link_id_3 = "search_type_driving_license";
        $text_1 = "General Search";
        $text_2 = "Search Name";
        $text_3 = "Search Driving Licence";
        $type_1 = "general";
        $type_2 = "name";
        $type_3 = "driving_license";
        $placeholder_1 = "Type in people\'s name or driving licence number";
        $placeholder_2 = "Type in people\'s name";
        $placeholder_3 = "Type in people\'s driving license number";

        $search_input_id = "search_people_input";

        $search_people_renderer->set_parameters(
            $invisible_input_id = "search_people_type_input",
            $invisible_input_name = "search_people_type",
            $search_opt_button_id = "dropdown-button-search-type-people",
            $search_opt_button_text_id = "dropdown-button-search-type-people-text",
            $dropdown_menu_item_type_placeholder_array = array(
                array('id'=>$link_id_1, 'text'=>$text_1, 'type'=>$type_1, 'func'=>javascript_replace_placeholder_string($link_id_1, $search_input_id, $placeholder_1)),
                array('id'=>$link_id_2, 'text'=>$text_2, 'type'=>$type_2, 'func'=>javascript_replace_placeholder_string($link_id_2, $search_input_id, $placeholder_2)),
                array('id'=>$link_id_3, 'text'=>$text_3, 'type'=>$type_3, 'func'=>javascript_replace_placeholder_string($link_id_3, $search_input_id, $placeholder_3))
            ),
            $dropdown_menu_id = "dropdown-menu-search-type-people",
            $dropdown_button_id = "dropdown-button-search-type-people"
        );
        start_search_bar();
        $search_people_renderer->render();
        render_search_input_and_button($search_input_id, "search_people_text", "Type in people's name or driving licence number");
        end_search_bar();
        ?>
    </div>
    <div class="search_page_wrapper">
        <hr>
        <div class="search_res_table_container">
            <table>
                <caption>员工信息表</caption>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>姓名</th>
                    <th>职位</th>
                    <th>部门</th>
                    <th>入职日期</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>张三</td>
                    <td>软件工程师</td>
                    <td>技术部</td>
                    <td>2023-01-15</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>李四</td>
                    <td>项目经理</td>
                    <td>管理部</td>
                    <td>2022-06-20</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>王五</td>
                    <td>市场专员</td>
                    <td>市场部</td>
                    <td>2023-09-10</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>赵六</td>
                    <td>设计师</td>
                    <td>设计部</td>
                    <td>2023-03-05</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php

    if(isset($_GET["search_people_text"]) && isset($_GET["search_people_type"]) && $_GET["search_people_text"]!=""){
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

        $name_input = $_GET["search_people_text"];
        $name_cond = "%".$name_input."%";

        $stmt = $conn->prepare("SELECT * FROM People WHERE People_name LIKE ?");
        $stmt->bind_param("s", $name_cond);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows < 1){
            echo "No results found";
        }
        else{
            $table_start = <<<EOT
            <table>
                <caption>Result</caption>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Driving Licence</th>
                </tr>
                </thead>
                <tbody>
EOT;
            $table_end = <<<EOT
                </tbody>
            </table>
EOT;
            echo $table_start;

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["People_ID"]."</td>";
                echo "<td>".$row["People_name"]."</td>";
                echo "<td>".$row["People_address"]."</td>";
                echo "<td>".$row["People_licence"]."</td>";
                echo "</tr>";
            }
            echo $table_end;
        }
        $stmt->close();
        $conn->close();
    }

    ?>
</div>
</body>
</html>