<?php
// 应用公共文件

function show($data, $status)
{
    return json($data, $status);
}

function showNew($status, $message = "error", $data = [], $httpStatus = 200)
{
    $result = [
        'status' => $status,
        'message' => $message,
        'result' => $data
    ];
    return json($result, $httpStatus);
}

function d($str)
{
    if (is_array($str)) {
        echo "[Array]:<br/>";
        print_r($str);
        echo "<br/>";
    } else if (is_object($str)) {
        echo "[Object]:<br/>";
        print_r($str);
        echo "<br/>";
    } else if (is_bool($str)) {
        echo "[Boolean]: " . ($str ? "true" : "false") . "<br/>";
    } else {
        echo "[String]: " . $str . "<br/>";
    }
    echo "<br/>";
}




