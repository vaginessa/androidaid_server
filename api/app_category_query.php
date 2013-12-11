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

$table = DB_TABLE_APP_REPOSITORY;
$app_category =  $_POST['app_category'];

$order = DB_COLUMN_APP_PRICE;

if($app_category==DB_VALUE_CATEGORY_ALL){
	$query="SELECT * FROM $table  WHERE app_state =  '$app_state_deploy' ORDER BY $order DESC";
}
else{
	$query="SELECT * FROM $table WHERE app_category = '$app_category' AND  app_state =  '$app_state_deploy' ORDER BY $order DESC";
}
$result=mysql_query($query);

$num=mysql_numrows($result);

mysql_close();

$i=0;

while ($i < $num) {

	$app_package=mysql_result($result,$i,DB_COLUMN_APP_PACKAGE);
	$app_label=mysql_result($result,$i,DB_COLUMN_APP_LABEL);
	$app_version_code=mysql_result($result,$i,DB_COLUMN_APP_VERSION_CODE);
	$app_file=mysql_result($result,$i,DB_COLUMN_APP_FILE);
	$app_url=mysql_result($result,$i,DB_COLUMN_APP_URL);
	$app_update_stamp = mysql_result($result,$i,DB_COLUMN_APP_UPDATE_STAMP);

	$array = Array(DB_COLUMN_APP_PACKAGE=>$app_package, DB_COLUMN_APP_LABEL=>$app_label,DB_COLUMN_APP_VERSION_CODE=>$app_version_code,
		DB_COLUMN_APP_FILE=>$app_file, DB_COLUMN_APP_URL=>$app_url,DB_COLUMN_APP_UPDATE_STAMP=>$app_update_stamp);

	$jsonstr[$i] =  json_encode($array);
	$jsonobj[$i] = json_decode($jsonstr[$i]);

	$i++;

}

$array = Array(API_JSON_RESPONSE=>API_JSON_RESPONSE_TRUE,DB_COLUMN_APP_CATEGORY=>$app_category,API_JSON_CATEGORY_ARRAY=>$jsonobj);
echo json_encode($array);




?>