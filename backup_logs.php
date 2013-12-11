<?php
$httpd_error = "C:/Apache2.2/logs/error.log";
$httpd_access = "C:/Apache2.2/logs/access.log";
$php_logs = "C:/php/tmp/php_logs.txt";
$log_path = "E:/3gstone/log/";
$date = date('Y-m-d');
/*
if(file_exists($httpd_access)){
    $result = copy($httpd_access, $log_path.$date."_access".".log");
//    if ($result == false){
        unlink($httpd_access);
//    }
}
if(file_exists($httpd_error)){
    $result = copy($httpd_error, $log_path.$date."_error".".log");
//    if ($result == false){
        unlink($httpd_error);
//    }
}
if(file_exists($php_logs)){
    $result = copy($php_logs, $log_path.$date."_php".".log");
//    if ($result == false){
        unlink($php_logs);
//    }
}
*/
?>