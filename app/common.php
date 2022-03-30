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

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function convert($size)
{
    $unit = array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}


function getRows($file)
{
    $handle = fopen($file, 'rb');


    if (!$handle) {
        throw new Exception();
    }
    while (!feof($handle)) {
        yield fgetcsv($handle);
    }
    fclose($handle);
}











