<?php
/*
*    通过SSH登录到远程主机，实现传递文件的功能
*/
//define('EXT_DIR',realpath(dirname(__FILE__)));  //当前目录
define('SSH_USER','root');
define('SSH_PASSWORD','1.23456');
define('SSH_PORT',22);
define('SSH_HOST','112.14.180.118');//远程主机
define('REMOTE_DIR_EXT','/var/lib/collectd');  //要操作的远程根目录
//实现传输图片到远程主机,返回bool,参数分别为本地的图片路径，远程图片路径,以及要新建的目录
$local="/root/upload.gif";//本地图片
$remote="/attachments/success.jpg";
imageSend($local,$remote);
function imageSend($local,$remote,$new_dir_arr = array())
{
   
 if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
    $connection = ssh2_connect(SSH_HOST, SSH_PORT);
   
 if (!$connection) die('SSH Connection failed');
   
 if (ssh2_auth_password($connection, SSH_USER, SSH_PASSWORD))
    {
        $sftp = ssh2_sftp($connection);
      
        $dir = explode('/',$remote);
        $path = REMOTE_DIR_EXT;
        for($i = 0; $i<count($dir)-1;$i++)  //建立目录
        {
            if($dir[$i])
            {
                $path .= '/'.$dir[$i];
                ssh2_sftp_mchkdir($sftp, $path);
            }
        }
        if(substr($remote,0,1) != '/') $remote = '/'.$remote;
        $remote = REMOTE_DIR_EXT.$remote;
  //echo ($remote);
        ssh2_scp_send($connection, $local, $remote, 0777); 
    }
    else
    {
        die('SSH Connection failed');
    }
}

function ssh2_sftp_mchkdir($sftp,$path)
{
 ssh2_sftp_mkdir($sftp, $path);
}
?>
