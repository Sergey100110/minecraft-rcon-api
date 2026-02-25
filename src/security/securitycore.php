<?php

if($_SERVER['REQUEST_URI'] == '/security/securitycore.php'){
http_response_code(403);
echo 'Direct access not allowed';
exit;
}
include '../system/myjson.php';
include 'securityconfig.php';
ini_set('display_errors', 0);

function checkProfile($user,$object){
global $security_tokens_by_objects;
$data = parseJson($security_tokens_by_objects,$user);
if(!str_starts_with($data, 'JSON_ERROR_')){
if(strpos($data,$object) === false){
return false;
}
else{
return true;
}
}
else{
return $data;
}
}

function checkFilterMode($user){
global $security_tokens_filter_mode;
$data = parseJson($security_tokens_filter_mode,$user);
return $data;
}

function canAccess($user,$object){
$edit_object = "=" . $object . ";";	
$filter = checkFilterMode($user);
$access = checkProfile($user,$edit_object);
global $allow_if_not_defined;
if(($filter != "ignore")&&($filter != "blacklist")&&($filter != "whitelist")) {return $allow_if_not_defined;}
else if(($filter == "whitelist") && ($access == 0)){	
return false;
}
else if(($filter == "blacklist") && ($access == 1)){
return false;
}
else if($filter == "ignore"){
return true;
}
return true;
}
?>
