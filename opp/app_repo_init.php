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

	$filename = mb_convert_encoding($app_file_original,'UTF-8','GBK');

	$query = "SELECT * FROM $table WHERE app_file_original = '$filename'";
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


function insertNewFile($app_file_original,$table,$con){
	
	date_default_timezone_set('Asia/Shanghai'); //系统时间差8小时问题
	$app_deploy_date = Date("Y-m-d");
	$app_update_stamp =  date("Y-m-d H:i:s");

//	echo $app_file_original." ".bin2hex($app_file_original)."<br/>";
	$filename = mb_convert_encoding($app_file_original,'UTF-8','GBK');
//	echo $filename." ".bin2hex($filename)."<br/>";	
	
	$query = "INSERT INTO $table ( app_file_original,app_deploy_date, app_update_stamp) 
	VALUES ( '$filename','$app_deploy_date','$app_update_stamp')"; 	
	
	echo $query."<br/>";	
	
	$result=mysql_query($query,$con);
	if(!$result){
		echo mysql_error();
	}

	
}

?>


<?php 
function searchDir($dir,$arrayType,$table,$con){
	
	$filesDir = scandir($dir);
	foreach($filesDir as $key => $value){
		
		if($value == '.' || $value == '..'){
			continue;
		}
		
		if(filetype($dir."\\".$value)=='dir'){
				searchDir($dir."\\".$value,$arrayType);
		}
		else//(filetype($dir."\\".$value)=='file'){
		{	
			$arr = explode('.',$value);
			$type = end($arr);
			if(in_array(strtolower($type),$arrayType)){
//				echo "type:".$value."<br/>";
				if(isFileInDB($value,$table,$con)){
					$filename = mb_convert_encoding($value,'UTF-8','GBK');
					echo $filename." in ".$table."<br/>";
				}
				else {
//					echo "new apk";
					insertNewFile($value,$table,$con);
				}
				
			}
		
		}
	} 
}
?>


<?php

$dir = "..\\..\\download\\repository";
$arrayType = Array('apk');
searchDir($dir,$arrayType,$table,$con);

?>


</body>

</html>
