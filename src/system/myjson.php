<?php
function getMyJsonSingle($param,$value){
$str = '{"';
$str = $str . $param;
$str = $str . '":"';
$str = $str . $value;
$str = $str . '"}';
return $str;
}
function getMyJsonArray($keys, $values) {
    $data = array_combine($keys, $values);
    
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    
    return $json;
}
function parseJsonArray($json_string) {
    $data = json_decode($json_string, true);
    
    if (json_last_error() === JSON_ERROR_NONE) {
        return $data;
    } else {
        return 'JSON_ERROR_' . json_last_error();
    }
}
function parseJson($json_string,$param) {
    $data = json_decode($json_string, true);
    
    if (json_last_error() === JSON_ERROR_NONE) {
        return $data[$param];
    } else {
        return 'JSON_ERROR_' . json_last_error();
    }
}
?>
