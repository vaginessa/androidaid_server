<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd"> 

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Apk files auto insert into <?php echo DB_TABLE_APP_REPOSITORY; ?> ! </title>
</head>

<body>
<?php
#header("content-type;text/html;charset=utf-8");

#require('../../Data.php'); 

require('../../php/opp/apk_parser.php');
require('../../php/opp/db_manager.php');



$db = new DBManager();
$db->connect();
$db->select("androidaid");

$fileAry=glob("*.apk",GLOB_BRACE);
echo "ary".json_encode($fileAry);
echo "<br/>";

$p = new ApkParser();

foreach($fileAry as $file)
{
#	echo $file."<br/>";
	$res = $p->open($file);

	$p->getXML();

	$app_package = $p->getPackage();
#		echo $app_package;
	$app_version_code = $p->getVersionCode();
#		echo $app_version_code;
	$app_version_name = $p->getVersionName();
#		echo $app_version_name;
	$app_sdk_min = $p->getminSdkVersion();
#		echo $app_sdk_min;
	
	if($db->isApkInRepo($file)){
#		echo $file." in repository!"."<br/>";
		$result = $db->updateApkInfo($file, $app_package, intval($app_version_code), $app_version_name, intval($app_sdk_min));
		
	}
	else{

		
		$result = $db->insertNewApk($file, $app_package, intval($app_version_code), $app_version_name, intval($app_sdk_min));
		
		
		
	}
	
	//copy(mb_convert_encoding($file,'GBK','UTF-8'),"..\\online\\".$app_package."_".$app_version_code.".apk");
		
	//copy(mb_convert_encoding($file,'GBK','UTF-8'),"..\\repository\\".$file);
	if($result){	
		copy(mb_convert_encoding($file,'GBK','UTF-8'),"..\\online\\".$app_package."_".$app_version_code.".apk");
		
		copy(mb_convert_encoding($file,'GBK','UTF-8'),"..\\repository\\".$file);
	
		unlink(mb_convert_encoding($file,'GBK','UTF-8'));
	}
	
	
}

$db->close();

?>

</body>

</html>

