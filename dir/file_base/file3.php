<?php
header('content-type:text/html;charset=utf-8');
//$fileInfo = $_FILES['myFile'];
function uploaded($fileInfo,$uploads = 'uploads',$allowExt=array('jpg','jpeg','png','gif'),$flag=true,$max_size=2097152){
    if($fileInfo['error'] > 0){
        switch ($fileInfo['error']){
            case 1:
                $message = "文件超出upload_max_fileSize的限制";
                break;
            case 2:
                $message = "文件超出max_file_size的HTML表单限制";
                break;
            case 3:
                $message = "部分文件上传成功";
                break;
            case 4:
                $message = "上传文件未找到";
                break;
            case 6:
                $message = "找不到临时文件夹";
                break;
            case 7:
                $message = "上传文件写入失败";
                break;
            case 8:
                $message = "上传文件被PHP扩展程序中断";
                break;
        }
        exit(@$message);

    }

//        判断上传文件类型是否符合上传标准
    if (!is_array($allowExt)){
        exit("图片的扩展名需要使用数组返回");
    }
    $ext = pathinfo($fileInfo['name'],PATHINFO_EXTENSION);
    if(!in_array($ext,$allowExt)){
        exit("上传文件不符合上传类型");
    }

//        判断上传文件是否过大
    if($fileInfo['size'] > $max_size){
        exit("上传文件过大");
    }

//        判断上传文件是否通过HTTP POST传输
    if(!is_uploaded_file($fileInfo['tmp_name'])){
        exit("上传文件不是通过HTT[ POST进行数据传输");
    }

//        判断上传文件是否为图片
    if($flag){
        if(!@getimagesize($fileInfo['tmp_name'])){
            exit("上传文件不是图片类型");
        }
    }


//        判断上传文件目录是否存在
    if(!file_exists($uploads)){
        mkdir($uploads,0777,true);
        chmod($uploads,0777);
    }

//        判断上传文件能否移动

    $uniName = md5(uniqid(microtime(true),true)).'.'.$ext;
    $destination = $uploads.'/'.$uniName;
    if(!@move_uploaded_file($fileInfo['tmp_name'],$destination)){
        exit("文件上传失败");
    }else{
        return $destination;
    }
}