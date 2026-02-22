<?php
# System block
include './vendor/autoload.php';
include './system/config.php';
include './system/myjson.php';
include './system/handle.php';
ini_set('display_errors', 0);
use Thedudeguy\Rcon;
$ip = $_SERVER['REMOTE_ADDR'];
$token = $_GET['token'];
$json_names = ['status','code','error_message','message'];
$is_success = 1;
$ban_reason = "";
$is_json_send = 0;
# Code
if(($tokens_enable == 1)&&($token == null)){
$is_success = 0;
$ban_reason = "Access Token required, but not found.";
$token = "null";	
}
else if(($tokens_enable == 1)&&(!in_array($token, $tokens))){
$is_success = 0;
$ban_reason = "Invalid Access Token";	
}
else if (($ban_mode==1)&&(in_array($ip,$ips))) {
$is_success = 0;
$ban_reason = "IP was banned.";	
}
else if (($ban_mode==2)&&(!in_array($ip,$ips))){
$is_success = 0;
$ban_reason = "IP was banned.";	
}
$html403 = <<<HTML
<html>
<h1 align="center">403 Forbidden</h1>
<h3 align="center">Your actions have triggered the security system. Please contact the administrator.</h3>
<h4>Your IP: $ip</h4>
<h4>Your Access Token: $token</h4>
<h4>Reason: $ban_reason</h4>
</html>
HTML;
if($_GET['json'] == 'true'){
$is_json_send = 1;	
}
if($is_success == 0){
http_response_code(403);	
if($is_json_send == 0){
echo $html403;
}
else {
$json_values = ['false','403','Forbidden',$ban_reason];
echo getMyJsonArray($json_names,$json_values);	
}
exit;	
}
if($_GET['device'] == null){
http_response_code(400);	
if($is_json_send == 1){
$json_values = ['false','400','Bad Request',"Error: 'device' is not declared."];
echo getMyJsonArray($json_names,$json_values);
}
else {
echo "Error: 'device' is not declared.";
}
exit;	
}
if($_GET['state'] == null){
http_response_code(400);	
if($is_json_send == 1){
$json_values = ['false','400','Bad Request',"Error: 'state' is not declared."];
echo getMyJsonArray($json_names,$json_values);
}
else {
echo "Error: 'state' is not declared.";
}
exit;	
}

$rcon = new Rcon($rcon_host,$rcon_port,$rcon_password,$rcon_connection_timeout);
if($rcon->connect()){
if($is_json_send == 1){
$json_values = ['true','200','OK','Request sended.'];
echo getMyJsonArray($json_names,$json_values);
}
else {
echo "OK";
}
handle($rcon);
$rcon->disconnect();
}
else{
http_response_code(500);
$html500_rcon = <<<HTML
<html>
<h1 align="center">500 Internal Server Error</h1>
<h3 align="center">Please contact the administrator.</h3>
<h4>Your IP: $ip</h4>
<h4>Your Access Token: $token</h4>
<h4>Reason: RCON connection failed.</h4>
</html>
HTML;
if($is_json_send == 1){
$json_values = ['false','500','Internal Server Error','RCON connection failed.'];
echo getMyJsonArray($json_names,$json_values);
}
else {
echo $html500_rcon;
}	
exit;	
}
?>
