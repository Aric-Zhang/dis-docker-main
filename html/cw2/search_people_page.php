<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'navi_bar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'grid_container.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/'.GADGET_UTILS_DIR.'dropdown_menu.php';

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
        <form action="" class="general_form" method="get">
            <div class="form-group search_input_wrapper">

                <?php
                class SearchOptionRenderer{
                    var $search_input_id;
                    var $invisible_input_id;
                    var $invisible_input_name;
                    var $search_opt_button_id;
                    var $search_opt_button_text_id;

                    var $dropdown_menu_item_array;

                    var $search_type_array;

                    var $dropdown_menu_id;
                    var $dropdown_button_id;

                    function set_parameters($search_input_id,
                    $invisible_input_id,
                    $invisible_input_name,
                    $search_opt_button_id,
                    $search_opt_button_text_id,

                    $dropdown_menu_item_array,
                    //$dropdown_menu_item_type_placeholder_array,
                    $search_type_array,

                    $dropdown_menu_id,
                    $dropdown_button_id){
                        $this->search_input_id = $search_input_id;
                        $this->invisible_input_id = $invisible_input_id;
                        $this->invisible_input_name = $invisible_input_name;
                        $this->search_opt_button_id = $search_opt_button_id;
                        $this->search_opt_button_text_id = $search_opt_button_text_id;

                        //$dropdown_menu_item_array = array();
                        //$search_type_array = array();
                        //foreach ($dropdown_menu_item_type_placeholder_array as $dmitp){
                            //array_push($dropdown_menu_item_array, array('text'=> $dmitp['text'], 'href'=>'#','id'=>$dmitp['id']));
                            //array_push($search_type_array, array('id'=>$dmitp['id'],'type'=>$dmitp['type'],'func'=>$dmitp['func']));
                        //}

                        $this->dropdown_menu_item_array = $dropdown_menu_item_array;

                        $this->search_type_array = $search_type_array;

                        $this->dropdown_menu_id = $dropdown_menu_id;
                        $this->dropdown_button_id = $dropdown_button_id;
                    }
                    function render(){
                        $search_input_id = $this->search_input_id;
                        $invisible_input_id = $this->invisible_input_id;
                        $invisible_input_name = $this->invisible_input_name;
                        $search_opt_button_id = $this->search_opt_button_id;
                        $search_opt_button_text_id = $this->search_opt_button_text_id;

                        $dropdown_menu_item_array=$this->dropdown_menu_item_array;

                        $search_type_array=$this->search_type_array;

                        $dropdown_menu_id = $this->dropdown_menu_id;
                        $dropdown_button_id = $this->dropdown_button_id;

                        $links_string = "";
                        $links_additional_functions_string = "";
                        foreach ($search_type_array as $search_type) {
                            $links_string.="[document.getElementById('".$search_type['id']."'),'".$search_type['type']."'],";
                            $links_additional_functions_string.= $search_type['func'];
                        }

                        $search_default_type = "";
                        if(count($search_type_array)>0){
                            $search_default_type = $search_type_array[0]['type'];
                        }
                        $button_default_text = "";
                        if(count($dropdown_menu_item_array)>0){
                            $button_default_text = $dropdown_menu_item_array[0]['text'];
                        }
                        $start_search_opt_button_doc = <<<EOT
                <input id="$invisible_input_id" type="text" name="$invisible_input_name" style="display: none" value="$search_default_type">
                <button id="$search_opt_button_id" class="btn btn-primary form_control_search dropdown_button" type="button">
                    <span id="$search_opt_button_text_id">$button_default_text</span>
EOT;
                        $bind_function_script_doc=<<<EOT
                                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const links = [
                                $links_string
                            ];
                            
                            const link_function_map = new Map();
                            $links_additional_functions_string                    

                            var span = document.getElementById('$search_opt_button_text_id');
                            var input = document.getElementById('$invisible_input_id');

                            links.forEach((item)=>{
                                var link = item[0];
                                var type = item[1];
                                link.addEventListener('click', function(event) {
                                    event.preventDefault();
                                    span.innerHTML = link.textContent;
                                    input.value = type;
                                    var additional_func = link_function_map.get(link.id);
                                    if(additional_func){
                                        additional_func(event);
                                    }
                                });
                            });
                        });
                    </script>
EOT;

                        $end_search_opt_button_doc = <<<EOT
                </button>
EOT;
                        echo $start_search_opt_button_doc;

                        render_dropdown_menu($dropdown_menu_id, $dropdown_menu_item_array);
                        bind_dropdown_menu_to_button($dropdown_menu_id, $dropdown_button_id);

                        echo $bind_function_script_doc;
                        echo $end_search_opt_button_doc;
                    }
                }

                $search_people_renderer = new SearchOptionRenderer();

                function javascript_replace_placeholder_string($link_id, $input_id, $placeholder){
                    return "link_function_map.set('".$link_id."', function(event){                                        
                                    var search_input = document.getElementById('".$input_id."');
                                    search_input.placeholder = '".$placeholder."';
                                    });";
                }

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


                $search_people_renderer->set_parameters($search_input_id = "search_people_input",
                    $invisible_input_id = "search_people_type_input",
                    $invisible_input_name = "search_people_type",
                    $search_opt_button_id = "dropdown-button-search-type-people",
                    $search_opt_button_text_id = "dropdown-button-search-type-people-text",
                    $dropdown_menu_item_array=array(
                        array("text"=>$text_1, "href"=>"#", "id"=>$link_id_1),
                        array("text"=>$text_2, "href"=>"#", "id"=>$link_id_2),
                        array("text"=>$text_3, "href"=>"#", "id"=>$link_id_3),
                    ),
                    $search_type_array=array(
                        array("id"=>$link_id_1, "type"=>$type_1, "func"=>javascript_replace_placeholder_string($link_id_1, $search_input_id, $placeholder_1)),
                        array("id"=>$link_id_2, "type"=>$type_2, "func"=>javascript_replace_placeholder_string($link_id_2, $search_input_id, $placeholder_2)),
                        array("id"=>$link_id_3, "type"=>$type_3, "func"=>javascript_replace_placeholder_string($link_id_3, $search_input_id, $placeholder_3)),
                    ),
                    $dropdown_menu_id = "dropdown-menu-search-type-people",
                    $dropdown_button_id = "dropdown-button-search-type-people"
                );

                $search_people_renderer->render();

                ?>
                <input id="search_people_input" type="text" name="search_people_text" class="form-control form_control_search search_input" placeholder="Type in people's name or driving licence number">
                <button class="btn btn-primary form_control_search search_button" type="submit">Search</button>
            </div>
        </form>
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