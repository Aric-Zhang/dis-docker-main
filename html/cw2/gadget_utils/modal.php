<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/environment_constants.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/php_utils.php';

function start_modal($modal_id, $button_id_modal_close, $title_text){
    $start_modal_doc = <<<EOT
                        <div id="$modal_id" class="modal-background">
                            <div class="modal-content">
                                <span id="$button_id_modal_close" class="close-button">&times;</span>
                                <div style="margin: 2rem;">
                                    <div style="height: 42px; font-size: 1.5rem; margin-bottom: 1rem;" class="text-center-label">
                                        $title_text
                                    </div>
EOT;
    echo $start_modal_doc;
}
function end_modal($modal_id, $button_id_modal_close){
    $end_modal_doc = <<<EOT
                                </div>
                            </div>
                        </div>
                        <script>
                        function set_${modal_id}_close(){
                            const ${modal_id} = document.getElementById("$modal_id");
                            const ${button_id_modal_close} = document.getElementById("$button_id_modal_close");

                            $button_id_modal_close.onclick = function() {
                                $modal_id.style.display = "none";
                            }

                            window.onclick = function(event) {
                                if (event.target == $modal_id) {
                                    $modal_id.style.display = "none";
                                }
                            }
                        }
                        set_${modal_id}_close()
                        </script>

EOT;
    echo $end_modal_doc;
}
function end_modal_no_script(){
    $end_modal_doc = <<<EOT
                                </div>
                            </div>
                        </div>

EOT;
    echo $end_modal_doc;
}
function bind_modal_to_open_button($modal_id, $button_id)
{
    $bind_modal_doc = <<<EOT
                        <script>
                            var modal = document.getElementById("$modal_id");
                            var openBtn = document.getElementById("$button_id");

                            openBtn.onclick = function(event) {
                                event.preventDefault();
                                modal.style.display = "flex";
                            }
                        </script>
EOT;
    echo $bind_modal_doc;
}
function bind_modal_to_cancel_button($modal_id, $button_id){
    $bind_modal_cancel_doc = <<<EOT
                            <script>
                                var modal = document.getElementById("$modal_id");
                                var password_change_cancel_button = document.getElementById("$button_id");
                                password_change_cancel_button.onclick = function() {
                                    modal.style.display = "none";
                                }

                            </script>
EOT;
    echo $bind_modal_cancel_doc;
}
function start_modal_in_script($modal_id, $button_id_modal_close, $title_text){
    $start_modal_doc = <<<EOT
                        <div id= "$modal_id" class="modal-background">
                            <div class="modal-content">
                                <span id="$button_id_modal_close" class="close-button">&times;</span>
                                <div style="margin: 2rem;">
                                    <div style="height: 42px; font-size: 1.5rem; margin-bottom: 1rem; color:#10263B;" class="text-center-label">
                                        $title_text
                                    </div>
EOT;
    echo $start_modal_doc;
}
function bind_modal_to_open_button_in_script($modal_id, $button_id)
{
    $bind_modal_doc = <<<EOT

                            var modal = document.getElementById(`$modal_id`);
                            var openBtn = document.getElementById(`$button_id`);

                            openBtn.onclick = function(event) {
                                event.preventDefault();
                                modal.style.display = "flex";
                                console.log("Open Modal");
                            }

EOT;
    echo $bind_modal_doc;
}
function bind_modal_to_close_button_in_script($modal_id, $button_id_modal_close){
    $end_modal_doc = <<<EOT
                            var modal = document.getElementById(`$modal_id`);
                            var closeBtn = document.getElementById(`$button_id_modal_close`);

                            closeBtn.onclick = function() {
                                event.stopPropagation();
                                modal.style.display = "none";
                            }

                            window.onclick = function(event) {
                                if (event.target == modal) {
                                    modal.style.display = "none";
                                }
                            }
EOT;
    echo $end_modal_doc;
}

?>