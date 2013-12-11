<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php
require_once "..\db_conn.php";
$table=DB_TABLE_VERSION_REPOSITORY;
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Display <?php echo $table; ?></title>
</head>

<body>

<h1>Display <?php echo $table; ?> data!</h1>

<?php
mysql_select_db(DB_NAME,$con);



$order = DB_COLUMN_VERSION_SERIAL;
$query="SELECT * FROM $table ORDER BY $order DESC";
$result=mysql_query($query,$con);

$num=mysql_numrows($result);

mysql_close();
?>


<table border="1" cellspacing="2" cellpadding="2">

<tr>

<?php
foreach($ArrayVersionColumns as $columns){
?>

<td><font face="Arial, Helvetica, sans-serif"><?php echo $columns; ?></font></td>

<?php
	}
?>
</tr>

<?php

$i=0;
while ($i < $num) {

?>
<tr>
<?php
	foreach($ArrayVersionColumns as $columns){
		$value=mysql_result($result,$i,$columns);
?>

<td><font face="Arial, Helvetica, sans-serif"><?php echo $value; ?></font></td>

<?php
	}
?>
</tr>

<?php
	$i++;
}
?>

</body>
</html>
