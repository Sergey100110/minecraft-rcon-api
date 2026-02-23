<?php
if($_SERVER['REQUEST_URI'] == '/system/handle.php'){
http_response_code(403);
echo 'Direct access not allowed';
exit;
}
function handle($_rcon){
$_device = $_GET['device'];
$_state = $_GET['state'];
if($_device == "world_time"){
$_rcon->sendCommand('/time set ' . $_state);
}
}
?>