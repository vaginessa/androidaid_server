<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd"> 

<?php
require_once "..\constants.php";
$table = DB_TABLE_VERSION_REPOSITORY;
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $table; ?> data maintainer</title>
</head>

<body>


<?php

#action - q:query; i:insert; u:update; d:delete
$action = $_REQUEST['action'];

if ($_POST['action'] == 'query') { 
    $action = 'q';
} else if ($_POST['action'] == 'insert') { 
    $action = 'i'; 
} else if ($_POST['action'] == 'update') {
    $action = 'u';
} else if ($_POST['action'] == 'delete') {
    $action = 'd';
} else { 
    $action = 'q';
} 




$version_serial =  $_POST['version_serial'];
$version_code =  $_POST['version_code'];
$version_name = $_POST['version_name'];
$version_sdk =  $_POST['version_sdk'];
$version_customer = $_POST['version_customer'];
$version_brand =  $_POST['version_brand'];
$version_model =  $_POST['version_model'];
$version_file =  $_POST['version_file'];
$version_new = $_POST['version_new'];
$version_stamp =  $_POST['version_stamp'];

#echo $category_serial;

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



date_default_timezone_set('Asia/Shanghai'); //系统时间差8小时问题
$version_stamp =  date("Y-m-d H:i:s");

# insert new values into table
if(isset($action) && $action == 'i') {

	
	$query = "INSERT INTO $table ( version_code, version_name, version_sdk, version_customer,version_brand, version_model,version_file,version_new,version_stamp) 
	VALUES ( '$version_code', '$version_name', '$version_sdk', '$version_customer', '$version_brand','$version_model','$version_file','$version_new','$version_stamp')"; 
	echo $query;
	
	$result = mysql_query($query,$con)
	or die("Invalid query: " . mysql_error());
	
	$action = "q";	$version_serial = "";
}


if(isset($action) && $action == 'u') {

	$query = "UPDATE $table SET version_code = '$version_code', version_name = '$version_name', version_sdk = '$version_sdk', version_customer = '$version_customer', version_brand = '$version_brand',version_model = '$version_model', version_file = '$version_file', version_new = '$version_new', version_stamp = '$version_stamp' WHERE version_serial = $version_serial"; 
	echo $query;
	
	$result = mysql_query($query,$con)
	or die("Invalid query: " . mysql_error());
	
	$action = "q";
	
}

if(isset($action) && $action == 'd') {
	$query = "DELETE FROM $table WHERE version_serial = $version_serial"; 
	echo $query;
	
#	$result = mysql_query($query,$con)
#	or die("Invalid query: " . mysql_error());
	
	$action = "q";
	$app_serial = "";
}


?>

<?php 


if($action=="q"){
	$order = DB_COLUMN_VERSION_SERIAL;
#	$version_serial =  $_POST['version_serial'];

	if($version_serial=="")
		$query="SELECT * FROM $table ORDER BY $order DESC LIMIT 1";
	else{
		$query="SELECT * FROM $table WHERE version_serial = $version_serial";	
	}
	
	echo $query;

	$result=mysql_query($query,$con);
	$num=mysql_numrows($result);
}
mysql_close();


?>

<h3><?php echo $table; ?> table</h3>

    <form method="post" action="version_repo.php">
	
	<?php
		$i = 0;
		foreach($ArrayVersionColumns as $columns){
			$value=mysql_result($result,$i,$columns);
	?>
	<?php echo $columns.':'; ?><input type="text" size=128 name="<?php echo $columns;?>" value="<?php echo $value;?>"><br>
	<?php
		
		}
	?>
	
	
	<input type="submit" name="action" value="query">
    <input type="submit" name="action" value="insert">
    <input type="submit" name="action" value="update">
    <input type="submit" name="action" value="delete">
    </form>

</body>

</html>
