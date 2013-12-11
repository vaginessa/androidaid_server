<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd"> 
<?php
require_once "..\constants.php";
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo DB_TABLE_APP_REPOSITORY; ?> data maintainer</title>
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




$app_serial =  $_POST['app_serial'];
$app_category =  $_POST['app_category'];
$app_package = $_POST['app_package'];
$app_label =  $_POST['app_label'];
$app_file = $_POST['app_file'];
$app_url =  $_POST['app_url'];
$app_version_name = $_POST['app_version_name'];
$app_version_code = $_POST['app_version_code'];
$app_icon_file = $_POST['app_icon_file'];
$app_icon_url =  $_POST['app_icon_url'];
$app_owner =  $_POST['app_owner'];
$app_vendor = $_POST['app_vendor'];
$app_agent =  $_POST['app_agent'];
$app_state = $_POST['app_state'];
$app_price =  $_POST['app_price'];
$app_pricing_policy = $_POST['app_pricing_policy'];
$app_file_original = $_POST['app_file_original'];
$app_deploy_date =  $_POST['app_deploy_date'];
$app_update_stamp = $_POST['app_update_stamp'];

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

# insert new values into table
if(isset($action) && $action == 'i') {
	date_default_timezone_set('Asia/Shanghai'); //系统时间差8小时问题
	$app_deploy_date = Date("Y-m-d");
	$app_update_stamp =  date("Y-m-d H:i:s");

	
	$query = "INSERT INTO $table ( app_category, app_package, app_label, app_file, app_url, app_icon_file, app_icon_url,
	app_version_name,app_version_code,app_owner,app_vendor,app_agent,app_state,app_price,app_pricing_policy,app_file_original,app_deploy_date, app_update_stamp) 
	VALUES ( '$app_category', '$app_package', '$app_label', '$app_file', '$app_url', '$app_icon_file', '$app_icon_url','$app_version_name',$app_version_code,'$app_owner','$app_vendor','$app_agent','$app_state',$app_price,'$app_pricing_policy','$app_file_original','$app_deploy_date','$app_update_stamp')"; 

#	echo $query;
	
	$result = mysql_query($query,$con)
	or die("Invalid query: " . mysql_error());
	
	$action = "q";
	$app_serial = "";
}


if(isset($action) && $action == 'u') {
	date_default_timezone_set('Asia/Shanghai'); //系统时间差8小时问题
	$app_update_stamp =  date("Y-m-d H:i:s");

	$query = "UPDATE $table SET app_category = '$app_category', app_package = '$app_package', app_label = '$app_label', app_file = '$app_file', app_url = '$app_url', app_icon_file = '$app_icon_file', app_icon_url = '$app_icon_url',app_version_code = $app_version_code,
	app_version_name = '$app_version_name',app_owner = '$app_owner',app_vendor = '$app_vendor',app_agent = '$app_agent',app_price = $app_price,app_pricing_policy = '$app_pricing_policy',app_file_original = '$app_file_original',app_state = '$app_state' ,app_update_stamp = '$app_update_stamp', app_file_original = '$app_file_original' 
	WHERE app_serial = $app_serial"; 

#	echo $query;
	
	$result = mysql_query($query,$con)
	or die("Invalid query: " . mysql_error());
	
	$action = "q";
	
}

if(isset($action) && $action == 'd') {
	$query = "DELETE FROM $table WHERE app_serial = $app_serial"; 

#	echo $query;
	
#	$result = mysql_query($query,$con)
#	or die("Invalid query: " . mysql_error());
	
	$action = "q";
	$app_serial = "";
}

#mysql_close($con);

?>

<?php 
#require_once "..\db_conn.php";
#mysql_select_db(DB_NAME,$con);
#$table=DB_TABLE_APP_REPOSITORY;

if($action=="q"){
	$order = DB_COLUMN_APP_SERIAL;
#	$app_serial =  $_POST['app_serial'];

	if($app_serial=="")
		$query="SELECT * FROM $table ORDER BY $order DESC LIMIT 1";
	else{
		$query="SELECT * FROM $table WHERE app_serial = $app_serial";	
	}
	
#	echo $query;

	$result=mysql_query($query,$con);
	$num=mysql_numrows($result);
}
mysql_close();


?>

<h3><?php echo DB_TABLE_APP_REPOSITORY; ?> table</h3>

    <form method="post" action="app_repo.html">
	
	<?php
		$i = 0;
		foreach($ArrayRepoColumns as $columns){
			$value=mysql_result($result,$i,$columns);
	?>
	<?php echo $columns.':'; ?><input type="text" size=128 name="<?php echo $columns;?>" value="<?php echo $value;?>"><br>
	<?php
		
		}
	?>
	
<!--	
    <?php echo DB_COLUMN_APP_SERIAL.':'; ?><input type="text" size =40 name="app_serial" value="<?php echo $app_serial;?>"><br>
    <?php echo DB_COLUMN_APP_CATEGORY.':'; ?><input type="text" size =40 name="app_category"><br>
    <?php echo DB_COLUMN_APP_PACKAGE.':'; ?><input type="text" size =40 name="app_package" ><br>
    <?php echo DB_COLUMN_APP_LABEL.':'; ?><input type="text" size =40 name="app_label"><br>
    <?php echo DB_COLUMN_APP_FILE.':'; ?><input type="text" size =40 name="app_file" ><br>
    <?php echo DB_COLUMN_APP_URL.':'; ?><input type="text" size =512 name="app_url"><br>
    <?php echo DB_COLUMN_APP_ICON_FILE.':'; ?><input type="text" size =40 name="app_icon_file" ><br>
    <?php echo DB_COLUMN_APP_ICON_URL.':'; ?><input type="text" size =512 name="app_icon_url"><br>
    <?php echo DB_COLUMN_APP_VERSION.':'; ?><input type="text" size =40 name="app_version" ><br>
    <?php echo DB_COLUMN_APP_OWNER.':'; ?><input type="text" size =40 name="app_owner"><br>
    <?php echo DB_COLUMN_APP_VENDOR.':'; ?><input type="text" size =40 name="app_vendor" ><br>
    <?php echo DB_COLUMN_APP_AGENT.':'; ?><input type="text" size =40 name="app_agent"><br>
    <?php echo DB_COLUMN_APP_STATE.':'; ?><input type="text" size =40 name="app_state" ><br>
    <?php echo DB_COLUMN_APP_PRICE.':'; ?><input type="text" size =40 name="app_price"><br>
    <?php echo DB_COLUMN_APP_PRICING_POLICY.':'; ?><input type="text" size =40 name="app_pricing_policy" ><br>
    <?php echo DB_COLUMN_APP_DEPLOY_DATE.':'; ?><input type="text" size =40 name="app_deploy_date"><br>
    <?php echo DB_COLUMN_APP_UPDATE_STAMP.':'; ?><input type="text" size =40 name="app_update_stamp" ><br>
-->
	
	<input type="submit" name="action" value="query">
    <input type="submit" name="action" value="insert">
    <input type="submit" name="action" value="update">
    <input type="submit" name="action" value="delete">
    </form>

</body>

</html>
