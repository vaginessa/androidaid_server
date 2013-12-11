<?php
header("content-type;text/html;charset=utf-8");

require_once "..\db_conn.php";

if (!$con)
{
  die('Could not connect: ' . mysql_error());
}

$db_selected=mysql_select_db(DB_NAME,$con);
if (!$db_selected)
  {
  die ("Can\'t use ".DB_NAME.' : ' . mysql_error());
  }


$device_imei = $_REQUEST['device_imei'];
$query_stamp = $_REQUEST['category_stamp'];
$version_code = $_REQUEST['version_code'];
$api_version = $_REQUEST['api_version'];

$api_response = API_JSON_RESPONSE_FALSE;

$table = DB_TABLE_CATEGORY_MANAGER;
#$category_status = DB_VALUE_APP_STATE_DEPLOY;
#WHERE 
#$where = "app_state_deploy = '$app_state_deploy'";
$order = DB_COLUMN_CATEGORY_SERIAL;
#$category_status = DB_VALUE_CATEGORY_STATUS_ONLINE;
#$query="SELECT * FROM $table WHERE category_stamp > '$query_stamp' ORDER BY $order DESC ";
$query="SELECT * FROM $table ORDER BY $order";
$result=mysql_query($query);
$num=mysql_numrows($result);


$i=0;

while ($i < $num) {

	$category_serial=mysql_result($result,$i,DB_COLUMN_CATEGORY_SERIAL);
	$app_category=mysql_result($result,$i,DB_COLUMN_APP_CATEGORY);
	$category_dir=mysql_result($result,$i,DB_COLUMN_CATEGORY_DIR);
	$category_status=mysql_result($result,$i,DB_COLUMN_CATEGORY_STATUS);
	$category_mapping=mysql_result($result,$i,DB_COLUMN_CATEGORY_MAPPING);
	$category_stamp = mysql_result($result,$i,DB_COLUMN_CATEGORY_STAMP);

	$array = Array(DB_COLUMN_CATEGORY_SERIAL=>$category_serial,DB_COLUMN_APP_CATEGORY=>$app_category,DB_COLUMN_CATEGORY_DIR=>$category_dir, DB_COLUMN_CATEGORY_STATUS=>$category_status,DB_COLUMN_CATEGORY_MAPPING=>$category_mapping,DB_COLUMN_CATEGORY_STAMP=>$category_stamp);

	$jsonstr[$i] =  json_encode($array);
	$jsonobj[$i] = json_decode($jsonstr[$i]);

	$i++;
	
	$api_response = API_JSON_RESPONSE_TRUE;
}

$array = Array(API_JSON_RESPONSE=>$api_response,API_VERSION=>$api_version,DB_COLUMN_VERSION_CODE=>$version_code,DB_COLUMN_DEVICE_IMEI=>$device_imei,DB_COLUMN_CATEGORY_STAMP=>$query_stamp,API_JSON_CATEGORY_ARRAY=>$jsonobj);
echo json_encode($array);


mysql_close();

?>