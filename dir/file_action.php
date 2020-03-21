<?php
/*
 * 字节大小单位转换
 */
function transByte($size)
{
    $arr = array("Bytes", "KB", "MB", "GB", "TB", "EB");
    $i = 0;
    while ($size >= 1024) {
        $size = $size / 1024;
        $i++;
    }
    return round($size, 2) . $arr[$i];
}

//创建文件
function createFile($filename)
{
//    判断文件的合法性: *,?,<>,|,/
    $pattern = "/[\/,\?,\|,\*,<>]/";
    if (!preg_match($pattern, basename($filename))) {
//        判断文件名是否重名
        if (!file_exists($filename)) {
//            创建文件
            if (touch($filename)) {
                return "文件创建成功";
            } else {
                return "文件创建失败";
            }
        } else {
            return "文件名重复";
        }
    } else {
        return "文件名不能包含特殊字符";
    }
}


//验证文件名合法性
function checkFileName($filename)
{
    $pattern = "[\/,\?,\|.\*,<>]";
    if (preg_match($pattern, $filename)) {
        return false;
    } else {
        return true;
    }
}

//重命名文件
function RenameFile($oldName, $newName)
{
//获取文件后缀
    @$ext = strtolower(end(explode('.', $oldName)));

//    检测文件名合法性
    if (checkFileName($newName)) {
//        检测目录下是否存在同名文件
        $path = dirname($oldName);
        if (file_exists($path . '/' . $newName)) {
            return "文件名重复";
        } else {
//            重命名文件
            if (rename($oldName, $path . '/' . $newName . '.' . $ext)) {
                return "文件重命名成功";

            } else {
                return "文件重命名失败";
            }

        }
    } else {
        return "文件名内有非法字符";
    }
}

//剪切文件
function cutFile($filename, $cut)
{
    if (file_exists($cut)) {
        if (!file_exists($cut . '/' . basename($filename))) {
            if (rename($filename, $cut . '/' . basename($filename))) {
                $mes = "文件剪切成功";
            } else {
                $mes = "文件剪切失败";
            }
        } else {
            $mes = "文件重名";
        }
    } else {
        $mes = "文件夹不存在";
    }
    return $mes;
}

//删除文件
function delFile($filename)
{
    if (unlink($filename)) {
        $mes = "文件删除成功";
    } else {
        $mes = "文件删除失败";
    }
    return $mes;
}

//下载文件
function downFile($filename)
{
    ob_clean();
    header("content-disposition:attachment;filename=" . basename($filename));
    header("content-length:" . filesize($filename));
    readfile($filename);
}

//复制文件
function copyFile($filename, $dst)
{
    if (file_exists($dst)) {
        if (!file_exists($dst . '/' . basename($filename))) {
            if (copy($filename, $dst . '/' . basename($filename))) {
                $mes = "文件复制成功";
            } else {
                $mes = "文件复制失败";
            }
        } else {
            $mes = "文件重名";
        }
    } else {
        $mes = "文件夹不存在";
    }
    return $mes;
}