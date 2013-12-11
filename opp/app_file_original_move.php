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



#function moveOriginalFile($table,$con){
	
	$app_state_online = DB_VALUE_APP_STATE_DEPLOY;

	$query = "SELECT app_file_original, app_package, app_version_code  FROM $table WHERE app_state = '$app_state_online'";
	$result=mysql_query($query,$con);
	$num=mysql_numrows($result);

//	echo $query."<br/>";
	$i=0;

	while ($i < $num) {
		
		$app_file_original = mysql_result($result,$i,DB_COLUMN_APP_FILE_ORIGINAL);
		$app_package = mysql_result($result,$i,DB_COLUMN_APP_PACKAGE);
		$app_version_code = mysql_result($result,$i,DB_COLUMN_APP_VERSION_CODE);
		
		copy("..\\..\\download\\repository\\".mb_convert_encoding($app_file_original,'GBK','UTF-8'),"..\\..\\download\\online\\".$app_package."_".$app_version_code.".apk");
		echo $app_file_original;
		echo "<br/>";
		$i++;
	}
	
#}

?>


</body>

</html>
