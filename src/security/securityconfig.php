<?php
if($_SERVER['REQUEST_URI'] == '/security/securityconfig.php'){
http_response_code(403);
echo 'Direct access not allowed';
exit;
}
$allow_if_not_defined = false;
$security_tokens_by_objects =<<<SECURITY_TOKENS

SECURITY_TOKENS;
$security_tokens_filter_mode =<<<SECURITY

SECURITY;
?>