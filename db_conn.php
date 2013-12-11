<?php
header("content-type;text/html;charset=utf-8");
require_once "constants.php";

$con = mysql_connect("localhost",DB_USER,DB_PWD)or die('Could not connect: ' . mysql_error());
mysql_set_charset('uft8', $con);
#mysqli_query($con, "SET NAMES 'UTF8'");
#mysqli_query($con, "SET character_set_client 'UTF8'");
#mysqli_query($con, "SET character_set_connection 'UTF8'");
#mysqli_query($con, "SET charracter_set_results 'UTF8'");

?>

