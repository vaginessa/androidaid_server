<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd"> 

<?php
require_once "..\constants.php";
$table = DB_TABLE_CATEGORY_MANAGER;
echo $table;
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




$category_serial =  $_POST['category_serial'];
$app_category =  $_POST['app_category'];
$category_dir = $_POST['category_dir'];
$category_status =  $_POST['category_status'];
$category_mapping = $_POST['category_mapping'];
$category_stamp =  $_POST['category_stamp'];

echo $category_serial;

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
$category_stamp =  date("Y-m-d H:i:s");

# insert new values into table
if(isset($action) && $action == 'i') {

	
	$query = "INSERT INTO $table ( app_category, category_dir, category_status, category_mapping, category_stamp) 
	VALUES ( '$app_category', '$category_dir', '$category_status', '$category_mapping', '$category_stamp')"; 
	echo $query;
	
	$result = mysql_query($query,$con)
	or die("Invalid query: " . mysql_error());
	
	$action = "q";
	$category_serial = "";
}


if(isset($action) && $action == 'u') {
	

	$query = "UPDATE $table SET app_category = '$app_category', category_dir = '$category_dir', category_status = '$category_status', category_mapping = '$category_mapping', category_stamp = '$category_stamp' WHERE category_serial = $category_serial"; 
	echo $query;
	
	$result = mysql_query($query,$con)
	or die("Invalid query: " . mysql_error());

	
	$action = "q";
	
}

if(isset($action) && $action == 'd') {
	$query = "DELETE FROM $table WHERE category_serial = $category_serial"; 
	echo $query;
	
#	$result = mysql_query($query,$con)
#	or die("Invalid query: " . mysql_error());
	
	$action = "q";
	$app_serial = "";
}


?>

<?php 


if($action=="q"){
	$order = DB_COLUMN_CATEGORY_SERIAL;
#	$category_serial =  $_POST['category_serial'];

	echo $app_category;
	echo " ";
	echo bin2hex($app_category);
	echo " ";
	
	if($category_serial=="")
		$query="SELECT * FROM $table ORDER BY $order DESC LIMIT 1";
	else{
		$query="SELECT * FROM $table WHERE category_serial = $category_serial";	
	}
	
	echo $query;

	$result=mysql_query($query,$con);
	$num=mysql_numrows($result);
}
mysql_close();


?>

<h3><?php echo $table; ?> table</h3>

    <form method="post" action="category_manager.php">
	
	<?php
		$i = 0;
		foreach($ArrayCategoryColumns as $columns){
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
