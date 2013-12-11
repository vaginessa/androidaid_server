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

$api_response = API_JSON_RESPONSE_FALSE;

$table = DB_TABLE_APP_REPOSITORY;
$app_state_deploy = DB_VALUE_APP_STATE_DEPLOY;
#WHERE app_state_deploy = '$app_state_deploy'
$order = DB_COLUMN_APP_SERIAL;
$query="SELECT * FROM $table  ORDER BY $order DESC ";
$result=mysql_query($query);
$num=mysql_numrows($result);


$i=0;

while ($i < $num) {

	$app_category=mysql_result($result,$i,DB_COLUMN_APP_CATEGORY);
	$app_package=mysql_result($result,$i,DB_COLUMN_APP_PACKAGE);
	$app_label=mysql_result($result,$i,DB_COLUMN_APP_LABEL);
	$app_version_code=mysql_result($result,$i,DB_COLUMN_APP_VERSION_CODE);
	$app_file=mysql_result($result,$i,DB_COLUMN_APP_FILE);
	$app_url=mysql_result($result,$i,DB_COLUMN_APP_URL);
	$app_state=mysql_result($result,$i,DB_COLUMN_APP_STATE);
	$app_update_stamp = mysql_result($result,$i,DB_COLUMN_APP_UPDATE_STAMP);

	$array = Array(DB_COLUMN_APP_CATEGORY=>$app_category,DB_COLUMN_APP_PACKAGE=>$app_package, DB_COLUMN_APP_LABEL=>$app_label,					DB_COLUMN_APP_VERSION_CODE=>$app_version_code,DB_COLUMN_APP_STATE=>$app_state,
		DB_COLUMN_APP_FILE=>$app_file, DB_COLUMN_APP_URL=>$app_url,DB_COLUMN_APP_UPDATE_STAMP=>$app_update_stamp);

	$jsonstr[$i] =  json_encode($array);
	$jsonobj[$i] = json_decode($jsonstr[$i]);

	$i++;
	
	$api_response = API_JSON_RESPONSE_TRUE;
}

$array = Array(API_JSON_RESPONSE=>$api_response,API_JSON_CATEGORY_ARRAY=>$jsonobj);
echo json_encode($array);


mysql_close();

?>