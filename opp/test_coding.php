<?php
require('apk_parser.php');
$p = new ApkParser();
$fileAry=glob("../../download/upload/*.apk",GLOB_BRACE);

foreach($fileAry as $file)
{
	echo $file."<br>";

	$res = $p->open($file);

	$p->getXML();

	$package = $p->getPackage();
	$br = "<br/>";
	echo $br.$package.$br;
	$vrsionCode = $p->getVersionCode();
	echo $br.$vrsionCode.$br;
	$versionName = $p->getVersionName();
	echo $br.$versionName.$br;
}

?>