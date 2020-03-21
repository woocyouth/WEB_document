<?php
//require_once "file_action.php";
/*
 * 获取文件
 */
function readDirectory($path){

//    打开目录
    $handle = opendir($path);

//    遍历目录文件
    $arr = array();
    while(($item = readdir($handle)) !== false){
//        var_dump($item);exit();
//        区分文件与目录
        if($item != "." && $item != ".."){
            if(is_file($path."/".$item)){
                $arr['file'][] = $item;

            }
            if (is_dir($path.'/'.$item)){
                $arr['dir'][] = $item;

            }
        }
    }
//    完成遍历
    closedir($handle);
    return $arr;
}

//获取文件夹大小
function dirSize($path){
    $sum = 0;
    global $sum;
    $handle = opendir($path);
    while (($item = readdir($handle)) !== false){
        if($item != '.' && $item != '..'){
            if(is_file($path.'/'.$item)){
                $sum += filesize($path.'/'.$item);
            }
            if(is_dir($path.'/'.$item)){
                $func = __FUNCTION__;
                $func($path.'/'.$item);
            }
        }
    }
    closedir($handle);
    return $sum;
}

//创建文件夹
function createFolder($dirname){

    if(checkFileName(basename($dirname))){

        if(!file_exists($dirname)){
            if(mkdir($dirname,0777,true)){
                $mes = "文件夹创建成功";
            }else{
                $mes = "文件夹创建失败";
            }
        }else{
            $mes = "文件夹重名";
        }
    }else{
        $mes = "文件名内存有非法字符";
    }
    return $mes;
}

//复制文件夹
function copyFolder($src,$dst){
     if(!file_exists($dst)) {
         mkdir($dst, 0777, true);
         chmod($dst, 0777);
     }
         $handle = opendir($src);
         while(($item = @readdir($handle)) !== false){
             if($item != "." && $item != ".."){
                 if(is_file($src.'/'.$item)){
                     copy($src.'/'.$item,$dst.'/'.$item);
                 }
                 if(is_dir($src.'/'.$item)){
                     $func = __FUNCTION__;
                     $func($src.'/'.$item,$dst.'/'.$item);
                 }
             }
         }

     closedir($handle);
     return "复制成功";
}

//重命名文件夹
function renameFolder($oldName,$newName){
    if(checkFileName(basename($newName))){
        if(!file_exists($newName)){
            if(rename($oldName,$newName)){
                $mes = "文件夹重命名成功";
            }else{
                $mes = "文件夹重命名失败";
            }
        }else{
            $mes = "文件夹重名";
        }
    }else{
        $mes = "名称存在非法字符";
    }
    return $mes;
}

//剪切文件夹
function cutFolder($src,$dst){
      if(file_exists($dst)){
          if(is_dir($dst)){
              if(!file_exists($dst.'/'.basename($src))){
                  if(rename($src,$dst.'/'.basename($src))){
                      $mes = "文件夹剪切成功";
                  }else{
                      $mes = "文件夹剪切失败";
                  }
              }else{
                  $mes = "文件夹重名";
              }
          }else{
              $mes = "不是一个文件夹";
          }
      }else{
          $mes = "文件夹不存在";
      }
      return $mes;
}

//删除文件夹
function delFolder($path){
    $handle = opendir($path);
    while(($item = readdir($handle)) !== false){
        if($item != "." && $item != ".."){
            if(is_file($path.'/'.$item)){
                unlink($path.'/'.$item);
            }
            if(is_dir($path.'/'.$item)){
                $func = __FUNCTION__;
                $func($path.'/'.$item);
            }
        }
    }
    closedir($handle);
    rmdir($path);
    return "文件夹删除成功";
}