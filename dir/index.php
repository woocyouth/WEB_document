<?php
require_once "readFile.php";
require_once "file_action.php";
require_once "common.func.php";
require_once "../Upload.class.php";
$path = "file_base";
@$path = $_REQUEST['path'] ? $_REQUEST['path'] : $path;
@$act = $_REQUEST['act'];
@$filename = $_REQUEST['filename'];
@$dirname = $_REQUEST['dirname'];
$info = readDirectory($path);
if ($info == null) {
    echo "<script type='text/javascript'>alert('文件夹为空');location.href='index.php';</script>";
}
$redirect = "index.php?path={$path}";

if ($act == "创建文件") {
    echo "createFile";
    echo $path . '--' . $filename;
    $mes = createFile($path . '/' . $filename);
    alterMes($mes, $redirect);

} else if ($act == "showContent") {

//      高亮显示php代码,highlight_string | highlight_file | 文本域 不能同时使用与嵌套
    $content = file_get_contents($filename);
    if (!strlen($content)) {
        alterMes("文件内容为空", $redirect);
    } else {
        $newContent = highlight_string($content, true);
        $str = <<<EOF
    <table width="100%" bgcolor="#F3F9FF" cellspacing="5" cellpadding="0">
    <tr>
         <td>{$newContent}</td>
    </tr>
   </table>
EOF;
        echo $str;
    }

} else if ($act == "editContent") {
//     编辑文件
    $content = file_get_contents($filename);
    $str = <<<EOF
    <form action="index.php?act=doEdit" method="post">
    <textarea name="content" cols="200" rows="30">{$content}</textarea>
    <input type="hidden" name="filename" value="{$filename}">
    <input type="hidden" name="path" value="{$path}">
    <input type="submit" value="编辑文件">
</form>
EOF;
    echo $str;
} else if ($act == "doEdit") {
//    文件编辑
    $content = $_REQUEST["content"];
    if (file_put_contents($filename, $content)) {
        $mes = "文件修改成功";

    } else {
        $mes = "文件修改失败";
    }
    alterMes($mes, $redirect);

} else if ($act == "cutFile") {
    $str = <<<EOF
    <form action="index.php?act=doCutFile" method="post">
    将文件剪切到<input type="text" name="cfile" placeholder="剪切到">
    <input type="hidden" name="path" value="{$path}">
    <input type="hidden" name="filename" value="{$filename}">
    <input type="submit" value="剪切">
</form>
EOF;
    echo $str;
} else if ($act == "doCutFile") {
    $cFile = $_REQUEST['cfile'];
    $mes = cutFile($filename, $path . '/' . $cFile);
    alterMes($mes, $redirect);
} else if ($act == "renameFile") {
//    重命名文件
    $str = <<<EOF
     <form action="index.php?act=doRename" method="post">
     文件新名称:<input type="text" name="newRename" placeholder="重命名"><br>
     <input type="hidden" name="path" value="{$path}">
     <input type="hidden" name="filename" value="{$filename}">
     <input type="submit" value="文件重命名">
</form>
EOF;
    echo $str;
} else if ($act == "doRename") {
//    实现重命名文件操作
    $newName = $_REQUEST["newRename"];
    $mes = RenameFile($filename, $newName);
    alterMes($mes, $redirect);

} else if ($act == "copyFile") {
    $str = <<<EOF
     <form action="index.php?act=doCopyFile" method="post">
     复制文件到<input type="text" name="dstname" placeholder="文件复制到"><br>
     <input type="hidden" name="path" value="{$path}">
     <input type="hidden" name="filename" value="{$filename}">
     <input type="submit" value="复制文件">
     </form>
EOF;
    echo $str;
} else if ($act == "doCopyFile") {
    $dst = $_REQUEST['dstname'];
    $mes = copyFile($filename,$path.'/'.$dst);
    alterMes($mes,$redirect);
} else if ($act == "delFile") {
//    文件删除
    $mes = delFile($filename);
    alterMes($mes, $redirect);
} else if ($act == "downFile") {
    downFile($filename);
//    alterMes($mes,$redirect);
} else if ($act == "上传文件") {
    $up = new Upload();
    $up->uploadFile();
} else if ($act == "创建文件夹") {
    $mes = createFolder($path . "/" . $dirname);
    alterMes($mes, $redirect);
} else if ($act == "copyFolder") {
    $str = <<<EOF
     <form action="index.php?act=doCopyFolder" method="post">
     复制文件夹到<input type="text" name="dstname" placeholder="文件夹复制到"><br>
     <input type="hidden" name="path" value="{$path}">
     <input type="hidden" name="dirname" value="{$dirname}">
     <input type="submit" value="复制文件夹">
     </form>
EOF;
    echo $str;
} else if ($act == "doCopyFolder") {
    @$dstname = $_REQUEST['dstname'];
    $mes = copyFolder($dirname, $path . '/' . $dstname . '/' . basename($dirname));
    @alterMes($mes, $redirect);
} else if ($act == "renameFolder") {
    $str = <<<EOF
    <form action="index.php?act=doRenameFolder" method="post">
    重命名文件夹<input type="text" name="newname" placeholder="重命名文件夹">
    <input type="hidden" name="path" value="{$path}">
    <input type="hidden" name="dirname" value="{$dirname}">
    <input type="submit" value="重命名">
</form>
EOF;
    echo $str;
} else if ($act == "doRenameFolder") {
    $newName = $_REQUEST['newname'];
    $mes = renameFolder($dirname, $path . '/' . $newName);
    alterMes($mes, $redirect);
} else if ($act == "cutFolder") {
    $str = <<<EOF
    <form action="index.php?act=doCutFolder" method="post">
    将文件夹剪切到<input type="text" name="dstname" placeholder="剪切到">
    <input type="hidden" name="path" value="{$path}">
    <input type="hidden" name="dirname" value="{$dirname}">
    <input type="submit" value="剪切">
</form>
EOF;
    echo $str;
} else if ($act == "doCutFolder") {
    $dst = $_REQUEST['dstname'];
    $mes = cutFolder($dirname, $path . '/' . $dst);
    alterMes($mes, $redirect);
} else if ($act == "delFolder") {
    $mes = delFolder($dirname);
    alterMes($mes, $redirect);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>dir</title>
    <link type="text/css" rel="stylesheet" href="CSS/cikonss.css">
    <link type="text/css" rel="stylesheet" href="CSS/index.css">
    <link rel="stylesheet" href="jQuery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css">
    <script type="text/javascript" src="jQuery-ui/jquery-1.7.js"></script>
    <script type="text/javascript" src="jQuery-ui/js/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="jQuery-ui/js/jquery-ui-1.10.4.custom.js"></script>
    <script type="text/javascript" src="jQuery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
    <script type="text/javascript">
        // top栏显示
        function show(dis) {
            document.getElementById(dis).style.display = 'block';
        }

        // 查看图片特效
        function showDetail(t, filename) {
            $("#showImg").attr("src", filename);
            $("#showDetail").dialog({
                height: "auto",
                width: "auto",
                position: {my: "center", at: "center", collision: "fit"},
                modal: false,//是否模式对话框
                draggable: true,//是否允许拖拽
                resizable: true,//是否允许拖动
                title: t,//对话框标题
                show: "slide",
                hide: "explode"
            });
        }

        // 删除文件
        function delFile(filename, path) {
            if (window.confirm("是否确定要删除文件，删除后文件无法恢复！")) {
                location.href = "index.php?act=delFile&filename=" + filename + "&path=" + path;
            }
        }

        // 返回上一级
        function goBack($back) {
            location.href = "index.php?path=" + $back;
        }

        // 删除文件夹
        function delFolder(dirname, path) {
            if (window.confirm("是否确认删除文件夹，文件夹删除后无法恢复！")) {
                location.href = "index.php?act=delFolder&dirname=" + dirname + "&path=" + path;
            }
        }

    </script>
</head>
<body>
<div id="showDetail" style="display:none"><img src="" id="showImg" alt=""/></div>
<div class="top">
    <ul id="navi">
        <li>
            <a href="index.php" title="主目录">
                <span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square">
                    <span class="icon-home"></span>
                </span>
            </a>
        </li>

        <li>
            <a href="#" onclick="show('createFile')" title="新建文件">
                <span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square">
                    <span class="icon-file"></span>
                </span>
            </a>
        </li>
        <li>
            <a href="#" onclick="show('createFolder')" title="新建文件夹">
                <span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square">
                    <span class="icon-folder"></span>
                </span>
            </a>
        </li>
        <li>
            <a href="#" onclick="show('uploadFile')" title="上传文件">
                <span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square">
                    <span class="icon-upload"></span>
                </span>
            </a>
        </li>
        <?php
        $back = ($path == "file") ? "file" : dirname($path);
        ?>
        <li>
            <a href="#" title="返回上一级目录" onclick="goBack('<?php echo $back; ?>')">
                <span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square">
                    <span class="icon-arrowLeft"></span>
                </span>
            </a>
        </li>

    </ul>
</div>

<form action="index.php" method="post" enctype="multipart/form-data" class="content">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="text-align: center;line-height: 25px;">
        <!--        创建文件-->
        <tr id="createFile" style="display:none;">
            <td>请输入文件名称</td>
            <td>
                <input type="text" name="filename"/>
                <input type="hidden" name="path" value="<?php echo $path; ?>"/>
                <input type="submit" name="act" value="创建文件"/>
            </td>
        </tr>
        <!--        创建文件夹-->
        <tr id="createFolder" style="display: none;">
            <td>请输入文件夹名称</td>
            <td>
                <input type="text" name="dirname">
                <input type="hidden" name="path" value="<?php echo $path; ?>">
                <input type="submit" name="act" value="创建文件夹">
            </td>
        </tr>
        <!--        上传文件-->
        <tr id="uploadFile" style="display:none;">
            <td>请选择要上传的文件</td>
            <td><input type="file" name="myFile"/>
                <input type="submit" name="act" value="上传文件"/>
            </td>
        </tr>
        <tr>
        <td>编号</td>
        <td>名称</td>
        <td>类型</td>
        <td>大小</td>
        <td>可读</td>
        <td>可写</td>
        <td>可执行</td>
        <td>创建时间</td>
        <td>修改时间</td>
        <td>访问时间</td>
        <td>操作</td>
        </tr>
        <!--        文件-->
        <?php

        if (@$info['file']) {
            $i = 1;
            foreach ($info['file'] as $val) {
                $p = $path . '/' . $val;
//                var_dump('p--'.$p);
                ?>
                <tr>

                    <td><?php echo $i; ?></td>
                    <td><?php echo $val; ?></td>
                    <td><?php $src = filetype($p) == "file" ? "./images/txt.png" : "./images/dir.png";
                        $title = filetype($p) == "file" ? "file" : "dir"; ?>
                        <img src=" <?php echo $src; ?> " title="<?php echo $title; ?>">
                    </td>
                    <td><?php echo transByte(filesize($p)); ?></td>
                    <td><?php $src = is_readable($p) ? "true.png" : "false.png"; ?>
                        <img src="./images/<?php echo $src; ?>">
                    </td>
                    <td><?php $src = is_writable($p) ? "true.png" : "false.png"; ?>
                        <img src="images/<?php echo $src; ?>">
                    </td>
                    <td><?php $src = is_executable($p) ? "true.png" : "false.png"; ?>
                        <img src="images/<?php echo $src; ?>">
                    </td>
                    <td><?php echo date("Y-m-d H:i:s", filectime($p)); ?></td>
                    <td><?php echo date("Y-m-d H:i:s", filemtime($p)); ?></td>
                    <td><?php echo date("Y-m-d H:i:s", fileatime($p)); ?></td>
                    <td style="letter-spacing: 13px;">
                        <!--                        查看文件-->
                        <?php
                        //                        检测文件扩展名
                        @$ext = strtolower(end(explode(".", $val)));
                        $allowExt = array('jpg', 'jpeg', 'gif', 'png');
                        if (in_array($ext, $allowExt)) {
                            ?>
                            <a href="#" onclick="showDetail('<?php echo $val ?>','<?php echo $p ?>')">
                                <img src="images/look_for.png" title="查看文件">
                            </a>
                            <?php
                        } else {
                            ?>
                            <a href="index.php?act=showContent&path=<?php echo $path; ?>&filename=<?php echo $p ?>">
                                <img src="images/look_for.png" title="查看文件">
                            </a>
                            <?php
                        }
                        ?>

                        <!--                        编辑文件-->
                        <a href="index.php?act=editContent&path=<?php echo $path; ?>&filename=<?php echo $p ?>">
                            <img src="images/pen.png" title="编辑文件">
                        </a>

                        <!--                        剪切文件-->
                        <a href="index.php?act=cutFile&path=<?php echo $path; ?>&filename=<?php echo $p; ?>">
                            <img src="images/cut.png" title="剪切文件">
                        </a>
                        <!--                        重命名文件-->
                        <a href="index.php?act=renameFile&path=<?php echo $path; ?>&filename=<?php echo $p; ?>">
                            <img src="images/rename.png" title="重命名文件">
                        </a>

                        <!--                        复制文件-->
                        <a href="index.php?act=copyFile&path=<?php echo $path; ?>&filename=<?php echo $p; ?>">
                            <img src="images/copy.png" title="复制文件">
                        </a>

                        <!--                        删除文件-->
                        <a href="#" onclick="delFile('<?php echo $p; ?>','<?php echo $path; ?>')">
                            <img src="images/delete.png" title="删除文件">
                        </a>

                        <!--                        下载文件-->
                        <a href="index.php?act=downFile&filename=<?php echo $p; ?>&path=<?php echo $path; ?>">
                            <img src="images/download.png" title="下载文件">
                        </a>

                    </td>
                </tr>
                <?php
                $i++;
            }
        }

        ?>

        <!--        文件夹-->
        <?php

        if (@$info['dir']) {

            @$k = ($i == null) ? 1 : $i;
            foreach ($info['dir'] as $val) {
                $p = $path . '/' . $val;
//                var_dump('p--'.p);
                ?>
                <tr>
                    <td><?php echo $k; ?></td>
                    <td><?php echo $val; ?></td>
                    <td><?php $src = filetype($p) == "file" ? "./images/txt.png" : "./images/dir.png";
                        $title = filetype($p) == "file" ? "file" : "dir"; ?>
                        <img src=" <?php echo $src; ?> " title="<?php echo $title; ?>">
                    </td>
                    <td><?php $sum = 0;
                        echo transByte(dirSize($p)); ?></td>
                    <td><?php $src = is_readable($p) ? "true.png" : "false.png"; ?>
                        <img src="./images/<?php echo $src; ?>">
                    </td>
                    <td><?php $src = is_writable($p) ? "true.png" : "false.png"; ?>
                        <img src="images/<?php echo $src; ?>">
                    </td>
                    <td><?php $src = is_executable($p) ? "true.png" : "false.png"; ?>
                        <img src="images/<?php echo $src; ?>">
                    </td>
                    <td><?php echo date("Y-m-d H:i:s", filectime($p)); ?></td>
                    <td><?php echo date("Y-m-d H:i:s", filemtime($p)); ?></td>
                    <td><?php echo date("Y-m-d H:i:s", fileatime($p)); ?></td>
                    <td style="letter-spacing: 13px;">
                        <!--                        查看文件-->

                        <a href="index.php?path=<?php echo $p; ?>">
                            <img src="images/look_for.png" title="查看文件夹">
                        </a>

                        <!--                        编辑文件-->
                        <a href="index.php?act=editContent&path=<?php echo $path; ?>&dirname=<?php echo $p ?>">
                            <img src="images/pen.png" title="编辑文件夹">
                        </a>

                        <!--                        剪切文件夹-->
                        <a href="index.php?act=cutFolder&path=<?php echo $path; ?>&dirname=<?php echo $p; ?>">
                            <img src="images/cut.png" title="剪切文件夹">
                        </a>

                        <!--                        重命名文件-->
                        <a href="index.php?act=renameFolder&path=<?php echo $path; ?>&dirname=<?php echo $p; ?>">
                            <img src="images/rename.png" title="重命名文件夹">
                        </a>

                        <!--                        复制文件夹-->
                        <a href="index.php?act=copyFolder&path=<?php echo $path; ?>&dirname=<?php echo $p; ?>">
                            <img src="images/copy.png" title="复制文件夹">
                        </a>

                        <!--                        删除文件-->
                        <a href="#" onclick="delFolder('<?php echo $p; ?>','<?php echo $path; ?>')">
                            <img src="images/delete.png" title="删除文件夹">
                        </a>

                        <!--                        下载文件-->
                        <a href="index.php?act=downFolder&dirname=<?php echo $p; ?>&path=<?php echo $path; ?>">
                            <img src="images/download.png" title="下载文件夹">
                        </a>

                    </td>
                </tr>
                <?php
                $k++;
            }
        }

        ?>
    </table>

</form>

</body>
</html>
