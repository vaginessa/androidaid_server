<?php
require_once dirname(dirname(__FILE__))."/constants.php";

$device_imei = $_REQUEST['device_imei'];
$device_app_repo_stamp = $_REQUEST['app_update_stamp'];
$version_code = $_REQUEST['version_code'];
$api_version = $_REQUEST['api_version'];
$api_response = API_JSON_RESPONSE_FALSE;

require_once dirname(dirname(dirname(dirname(__FILE__)))).'/ApkStore/utilities/class.groupmanager.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/ApkStore/utilities/class.categorymanager.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/ApkStore/utilities/class.applicationmanager.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/ApkStore/utilities/class.apkfilemanager.php';

$groupMgr = new GroupManager();
$groups = $groupMgr->getGroups();
$categoryMgr = new CategoryManager();
$appMgr = new ApplicationManager();
$apkMgr = new ApkfileManager();
$i = 0;
foreach ($groups as $group){
    $categories = $categoryMgr->getCategoriesByGroup($group['group_serial']);
    foreach ($categories as $category){
        $applications = $appMgr->getApplicationsByCategory($category['category_serial']);
        if($applications){
	        foreach ($applications as $application){
	            $apkfile = $apkMgr->getApkfileByApplication($application['application_serial']);
	            if ($apkfile){
	                
	                $app_category = $group['group_name'];
	                $app_package = $application['package'];
	                $app_label = $application['application'];
	                $app_version_code = $apkfile['version_code'];
	                $app_file = $apkfile['apkfile'];
	                $app_url = 'NA';
	                $app_state = DB_VALUE_APP_STATE_DEPLOY;
	                $app_price = 0;
	                $app_update_stamp = $apkfile['update_time'];
	
	                $array = Array(DB_COLUMN_APP_CATEGORY=>$app_category,DB_COLUMN_APP_PACKAGE=>$app_package, DB_COLUMN_APP_LABEL=>$app_label,DB_COLUMN_APP_VERSION_CODE=>$app_version_code,DB_COLUMN_APP_STATE=>$app_state,
			                DB_COLUMN_APP_PRICE=>$app_price,DB_COLUMN_APP_FILE=>$app_file, DB_COLUMN_APP_URL=>$app_url,DB_COLUMN_APP_UPDATE_STAMP=>$app_update_stamp);
	
	                $jsonstr[$i] =  json_encode($array);
	                $jsonobj[$i] = json_decode($jsonstr[$i]);
	
	                $i++;
		
	                $api_response = API_JSON_RESPONSE_TRUE;                
	            }
	        }
        }
    }
}

/*
$table = DB_TABLE_APP_REPOSITORY;
$app_state_deploy = DB_VALUE_APP_STATE_DEPLOY;
#WHERE 
$where = "app_state = '$app_state_deploy'";
#$where = "app_state = '$app_state_deploy' AND app_update_stamp > '$device_app_repo_stamp'";
#$where = "app_update_stamp > '$device_app_repo_stamp'";
#$order = DB_COLUMN_APP_STATE." ASC, ".DB_COLUMN_APP_UPDATE_STAMP." DESC";
$order = DB_COLUMN_APP_CATEGORY." ASC, ".DB_COLUMN_APP_UPDATE_STAMP." DESC";
$query="SELECT * FROM $table WHERE $where ORDER BY $order";
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
	$app_price=mysql_result($result,$i,DB_COLUMN_APP_PRICE);
	$app_update_stamp = mysql_result($result,$i,DB_COLUMN_APP_UPDATE_STAMP);

	$array = Array(DB_COLUMN_APP_CATEGORY=>$app_category,DB_COLUMN_APP_PACKAGE=>$app_package, DB_COLUMN_APP_LABEL=>$app_label,DB_COLUMN_APP_VERSION_CODE=>$app_version_code,DB_COLUMN_APP_STATE=>$app_state,
		DB_COLUMN_APP_PRICE=>$app_price,DB_COLUMN_APP_FILE=>$app_file, DB_COLUMN_APP_URL=>$app_url,DB_COLUMN_APP_UPDATE_STAMP=>$app_update_stamp);

	$jsonstr[$i] =  json_encode($array);
	$jsonobj[$i] = json_decode($jsonstr[$i]);

	$i++;
	
	$api_response = API_JSON_RESPONSE_TRUE;
}

$array = Array(API_JSON_RESPONSE=>$api_response,API_VERSION=>$api_version,DB_COLUMN_APP_UPDATE_STAMP=> $device_app_repo_stamp ,API_JSON_RESPONSE_NUM=>$num,API_JSON_APP_ARRAY=>$jsonobj);
echo json_encode($array);


mysql_close();
*/

$num = $i;
$array = Array(API_JSON_RESPONSE=>$api_response,API_VERSION=>$api_version,DB_COLUMN_APP_UPDATE_STAMP=> $device_app_repo_stamp ,API_JSON_RESPONSE_NUM=>$num,API_JSON_APP_ARRAY=>$jsonobj);
echo json_encode($array);
file_put_contents("app_repo_query_apks.json",json_encode($array));
?>