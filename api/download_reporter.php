<?php
require_once "..\constants.php";

$device_imei =  $_POST['device_imei'];
$version_code =  $_POST['version_code'];
$api_version = $_POST['api_version'];

$app_package =  $_POST['app_package'];
$app_label =  $_POST['app_label'];
$app_label = str_replace("'", "\\'", $app_label);

$app_version_code =  $_POST['app_version_code'];
$app_url =  $_POST['app_url'];
$app_download_start = $_POST['app_download_start'];
$app_download_stop = $_POST['app_download_stop'];

date_default_timezone_set('Asia/Shanghai'); //系统时间差8小时问题
$download_reporter_stamp =  date("Y-m-d H:i:s");

$api_entry = 'download_reporter.php';
$api_response = false;

require_once "..\db_conn.php";
if (!$con)
{
  die(json_encode(Array(API_ENTRY=>$api_entry,API_JSON_RESPONSE=>$api_response,'DB connect failed'=>mysql_error())));
}

$db_selected=mysql_select_db(DB_NAME,$con);
if (!$db_selected)
{
  die (json_encode(Array(API_ENTRY=>$api_entry,API_JSON_RESPONSE=>$api_response,'Table select failed'=>mysql_error())));

}

$table = DB_TABLE_DOWNLOAD_REPORTER;

$query = "INSERT INTO $table ( device_imei, version_code, app_package,app_label, app_version_code,
			app_url,app_download_start,app_download_stop,download_reporter_stamp) 
	VALUES ( '$device_imei',$version_code, '$app_package', '$app_label', $app_version_code,
		 '$app_url','$app_download_start','$app_download_stop','$download_reporter_stamp')"; 

mysql_query($query,$con) 
	or die(json_encode(Array(API_ENTRY=>$api_entry,API_JSON_RESPONSE=>$api_response,'Invalid query'=>mysql_error())));

$api_response = true;

$array = Array(API_ENTRY=>$api_entry,API_JSON_RESPONSE=>$api_response);

echo json_encode($array);

mysql_close($con);

?>
