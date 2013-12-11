<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd"> 
<?php
require_once "..\constants.php";
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update <?php echo DB_TABLE_APP_REPOSITORY; ?> category and label fields</title>
</head>

<body>


<?php

#action - q:query; i:insert; u:update; d:delete
$action = $_REQUEST['action'];

if ($_POST['action'] == 'next') { 
    $action = 'n';
} else if ($_POST['action'] == 'update') {
    $action = 'u';
} else { 
    $action = 'q';
} 

$app_serial =  $_POST['app_serial'];
$app_category =  $_POST['app_category'];
$app_label =  $_POST['app_label'];
$app_file_original = $_POST['app_file_original'];

//echo "action=".$action."<br/>";
//echo "app_serial=".$app_serial."<br/>";


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


if(isset($action) && $action == 'u') {
	date_default_timezone_set('Asia/Shanghai'); //系统时间差8小时问题
	$app_update_stamp =  date("Y-m-d H:i:s");

	$query = "UPDATE $table SET app_category = '$app_category', app_label = '$app_label', app_update_stamp = '$app_update_stamp'
	WHERE app_serial = $app_serial"; 

#	echo $query;
	
	$result = mysql_query($query,$con)
	or die("Invalid query: " . mysql_error());
	
	$action = "q";
	
}


#mysql_close($con);

?>

<?php 

if($action=="q" || $action=="n"){
	
	$order = DB_COLUMN_APP_SERIAL;
	
	if($app_serial=="")
		$query="SELECT app_serial,app_category,app_label,app_file_original FROM $table ORDER BY $order DESC LIMIT 1";
	else{

		if($action=="n"){
			$app_serial = $app_serial-1; 
		}
		if($app_serial == 0){
			$app_serial = 1; 
		}
		
		$query="SELECT app_serial,app_category,app_label,app_file_original FROM $table WHERE app_serial = $app_serial";	
	}
	
//	echo $query."<br/>";

	$result=mysql_query($query,$con);
	$num=mysql_numrows($result);
}
mysql_close();

?>

<h3><?php echo DB_TABLE_APP_REPOSITORY; ?> table</h3>

  <form method="post" action="app_repo_category.php">
	
	<?php
		$i = 0;
		foreach($ArrayRepoCategoryUpdateColumns as $columns){
			$value=mysql_result($result,$i,$columns);
	?>
	<?php echo $columns.':'; ?><input type="text" size=128 name="<?php echo $columns;?>" value="<?php echo $value;?>"><br>
	<?php
		
		}
	?>
	
	<input type="submit" name="action" value="query">

  <input type="submit" name="action" value="next">

  <input type="submit" name="action" value="update">

  </form>

</body>

</html>
