<?php

header('content-type:text/html;charset=utf-8');
require_once "common_function.php";
require_once "upload_function.php";

$files = getFiles();
foreach ($files as $fileInfo){
    $res = uploadFile($fileInfo);
    echo $res['mes'].'<br>';
    @$uploadFiles[] = $res['dest'];
}
$uploadFiles = array_values(array_filter($uploadFiles));
print_r($uploadFiles);
