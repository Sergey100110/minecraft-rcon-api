<?php
if($_SERVER['REQUEST_URI'] == '/system/myjson.php'){
http_response_code(403);
echo 'Direct access not allowed';
exit;
}
function getMyJsonSingle($param,$value){
$str = '{"';
$str = $str . $param;
$str = $str . '":"';
$str = $str . $value;
$str = $str . '"}';
return $str;
}
function getMyJsonArray($keys, $values) {
    // Создаём ассоциативный массив из двух массивов
    $data = array_combine($keys, $values);
    
    // Преобразуем в JSON-строку
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    
    // Возвращаем результат
    return $json;
}
function parseJsonArray($json_string) {
    // Декодируем JSON в ассоциативный массив
    $data = json_decode($json_string, true);  // true = ассоциативный массив
    
    // Проверяем, успешно ли распарсилось
    if (json_last_error() === JSON_ERROR_NONE) {
        return $data;
    } else {
        return 'JSON_ERROR_' . json_last_error();
    }
}
function parseJson($json_string,$param) {
    // Декодируем JSON в ассоциативный массив
    $data = json_decode($json_string, true);  // true = ассоциативный массив
    
    // Проверяем, успешно ли распарсилось
    if (json_last_error() === JSON_ERROR_NONE) {
        return $data[$param];
    } else {
        return 'JSON_ERROR_' . json_last_error();
    }
}
?>