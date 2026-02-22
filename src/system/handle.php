<?php
function handle($_rcon){
$_device = $_GET['device'];
$_state = $_GET['state'];
if($_device == "world_time"){
$_rcon->sendCommand('/time set ' . $_state); # Изменяем время в мире майнкрафта. (6000 - день, 18000 - полночь).
}
}
?>
