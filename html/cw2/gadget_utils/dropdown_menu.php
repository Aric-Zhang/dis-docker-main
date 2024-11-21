<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';

function render_dropdown_menu_item($dropdown_menu_item_info)
{
    $href = $dropdown_menu_item_info['href'];
    $text = $dropdown_menu_item_info['text'];
    $id = $dropdown_menu_item_info['id'];
    $dropdown_menu_item_doc = <<<EOT
                                <a href="$href" class="dropdown-item" id="$id">$text</a>
EOT;
    echo $dropdown_menu_item_doc;
}
function render_dropdown_menu($dropdown_menu_id, $dropdown_menu_item_info_array){
    $drop_down_menu_doc_start = <<<EOT
                    <div id="$dropdown_menu_id" class="dropdown-menu dropdown-menu-right">
                        <div style="position: relative;width: 100%;overflow: hidden;">
                            <div class="carousel_item active">
EOT;
    $drop_down_menu_doc_end = <<<EOT
                            </div>
                        </div>
                    </div>
EOT;

    echo $drop_down_menu_doc_start;
    foreach($dropdown_menu_item_info_array as $dropdown_menu_item_info){
        render_dropdown_menu_item($dropdown_menu_item_info);
    }
    echo $drop_down_menu_doc_end;
}
function bind_dropdown_menu_to_button($dropdown_menu_id, $button_id){
    $bind_menu_doc = <<<EOT
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const dropdownButton = document.getElementById('$button_id');
                            const dropdownMenu = document.getElementById('$dropdown_menu_id');

                            dropdownButton.addEventListener('click', function(event) {
                                event.stopPropagation(); // 防止点击事件冒泡到文档，导致立即关闭
                                if (dropdownMenu.classList.contains('show')) {
                                    dropdownMenu.classList.remove('show');
                                } else {
                                    dropdownMenu.classList.add('show');
                                }
                            });

                            document.addEventListener('click', function() {
                                if (dropdownMenu.classList.contains('show')) {
                                    dropdownMenu.classList.remove('show');
                                }
                            });
                        });
                    </script>
EOT;
    echo $bind_menu_doc;

}
?>