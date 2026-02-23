<?php
# System block
ini_set('display_errors', 0);
include '../libraries/autoload.php';
include './system/config.php';
include './system/myjson.php';
include './system/handle.php';
include './security/securitycore.php';
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
else if(($_GET['device'] != null)&&($_GET['state'] != null)&&($tokens_enable == 1)){
$access = canAccess($token,$_GET['device']);
if($access == false){
$is_success = 0;
$ban_reason = "Not enough rights.";
}
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
$missed_val = 'none';
if($_GET['device'] == null){
$missed_val = 'device';
}
if($_GET['state'] == null){
$missed_val = 'state';
}
$html400 = <<<HTML
<html>
<h1 align="center">400 Bad Request</h1>
<h3 align="center">Check the correctness of the request.</h3>
<h4>Your IP: $ip</h4>
<h4>Your Access Token: $token</h4>
<h4>Reason: '$missed_val' is not declared.</h4>
</html>
HTML;
if(!($missed_val == 'none')){
http_response_code(400);	
if($is_json_send == 1){
$json_values = ['false','400','Bad Request',"Error: '" . $missed_val ."' is not declared."];
echo getMyJsonArray($json_names,$json_values);
}
else {
echo $html400;
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