<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd"> 

<?php
require_once "..\constants.php";
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Apk files auto insert into <?php echo DB_TABLE_APP_REPOSITORY; ?> ! </title>
</head>

<body>

<?php 

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

?>

<?php 

function isFileInDB($app_file_original,$table,$con){

	$query = "SELECT * FROM $table WHERE app_file_original = '$app_file_original'";
	$result=mysql_query($query,$con);
	$num=mysql_numrows($result);

//	echo $query."<br/>";

	if($num>0){
		return true;
	}
	else {
		return false;
	}

}


function getCategoryDir($app_file_original,$con){
	
	
	$query =  "SELECT app_category FROM app_repository WHERE app_file_original = '$app_file_original'"; 	
	
	$result=mysql_query($query,$con);

	$num = mysql_numrows($result);
	
	if($num>0){
		
		$app_category = mysql_result($result,0,'app_category');
		
		$query =  "SELECT category_dir FROM category_manager WHERE app_category = '$app_category'"; 	
		
		$result=mysql_query($query,$con);

		$num = mysql_numrows($result);
		
		if($num>0){
			$category_dir = mysql_result($result,0,'category_dir');
		}
		
		
	}	
	
	return $category_dir;
	
}

?>


<?php

$filename = "apkinfo.json";

$jsonArray = json_decode(file_get_contents($filename),true);//将json结构变量$content用json_decode()函数解析为对象,如果加第二个参数为true，则解析为数组
$num = count($jsonArray);
$i=0;
while($i<$num){
	$app_file_original = $jsonArray[$i]['app_file'];
	if(isFileInDB($app_file_original,$table,$con)){
		
		echo $app_file_original." in ".$table."<br/>";
	
		$app_package = $jsonArray[$i]['app_package'];
		$app_version_code = $jsonArray[$i]['app_version_code']; 
		$app_version_name = $jsonArray[$i]['app_version_name']; 
		$app_sdk_min = $jsonArray[$i]['app_sdk_min']; 
		date_default_timezone_set('Asia/Shanghai'); //系统时间差8小时问题
		$app_update_stamp =  date("Y-m-d H:i:s");
	 
		$app_file = $app_package."_".$app_version_code.".apk";
		
		$category_dir = getCategoryDir($app_file_original,$con);
		
		copy("..\\..\\download\\repository\\".mb_convert_encoding($app_file_original,'GBK','UTF-8'),"..\\..\\download\\online\\".$app_file);
			
		
	
		$query = "UPDATE $table SET app_file = '$app_file' ,app_package = '$app_package', app_version_code = $app_version_code,  app_version_name = '$app_version_name', app_sdk_min = $app_sdk_min,app_update_stamp = '$app_update_stamp'
			WHERE app_file_original = '$app_file_original'"; 	

		$result=mysql_query($query,$con);

//		echo $query."<br/>";	
	
	}
	else {
		echo "new apk ".$app_file_original."<br/>";
//		insertNewFile($value,$table,$con);	
	}
	$i++;

}



?>


</body>

</html>
