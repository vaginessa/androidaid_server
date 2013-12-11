
<?php
function getRemoteIP(){

	$ip=false;
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
		if($ip){
			array_unshift($ips, $ip); $ip = FALSE;
		}
		for($i = 0; $i < count($ips); $i++){
			//if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])){
			if (!preg_match("/^(10|172\.16|192\.168)\./", $ips[$i])){
				$ip = $ips[$i];
				break;
			}
		}
	}
	return($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

function ipDecode($queryIP){
	$url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$queryIP;

	$ch = curl_init($url);
	curl_setopt($ch,CURLOPT_ENCODING ,'gb2312');
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // ��ȡ��ݷ���
	$result = curl_exec($ch);
	$result = mb_convert_encoding($result, "utf-8", "gb2312"); // ����ת������������
	curl_close($ch);
	preg_match("@<span>(.*)</span></p>@iU",$result,$ipArray);
	if (isset($ipArray[1])){
		$loc = $ipArray[1];
	}
	else{
		$loc = false;

	}
	return $loc;

}

function getProvince($ipDecoded){
	$result = false;
	if(strstr($ipDecoded,"中国")){
		$string = substr($ipDecoded, 6);
		$offset = stripos($string, "省");
		if(!$offset){
			$offset = stripos($string, "区");
		}
		if(!$offset){
			$offset = stripos($string, "市");
		}
		$result[0] = substr($string,0, $offset+3);
		$city = getCity(substr($string,$offset+3));
		if(!$city){
			$result[1] = $result[0];
		}
		else{
			$result[1] = $city;
		}
	}
	return $result;

}

function getCity($ipDecoded){
	$result = false;
	$string = $ipDecoded;
	$offset = stripos($string, "市");
	if(!$offset){
		$offset = stripos($string, "区");
	}
	if(!$offset){
		;
	}
	else{
		$result = substr($string,0,$offset+3);
	}
	return $result;

}


function getIPLoc($queryIP){  
  
  
$url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$queryIP;  
  
  
$ch = curl_init($url);  
  
  
curl_setopt($ch,CURLOPT_ENCODING ,'gb2312');  
  
  
curl_setopt($ch, CURLOPT_TIMEOUT, 10);  
  
  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
  
  
$result = curl_exec($ch);  
  
  
//$result = mb_convert_encoding($result, "utf-8", "gb2312"); // 编码转换，否则乱码  
  
  
   curl_close($ch);  
  
  
preg_match("@<span>(.*)</span></p>@iU",$result,$ipArray);  
$loc = $ipArray[1];  
return $loc;  
}

require_once "../constants.php";

$device_imei =  $_POST['device_imei'];
$device_brand =  $_POST['device_brand'];
$device_model =  $_POST['device_model'];
$device_sdk =  $_POST['device_sdk'];
$device_release =  $_POST['device_release'];
$device_app_verCode =  $_POST['version_code'];
$api_version = $_POST['api_version'];

date_default_timezone_set('Asia/Shanghai'); //系统时间差8小时问题
$device_regist_stamp =  date("Y-m-d H:i:s");

$api_response = false;

require_once "../db_conn.php";
if (!$con)
{
  die('Could not connect: ' . mysql_error());
#	echo json_encode(Array(API_JSON_RESPONSE=>$api_response));
#	die(json_encode(Array(API_JSON_RESPONSE=>$api_response)),254);
}

$db_selected=mysql_select_db(DB_NAME,$con);
if (!$db_selected)
{
  die ("Can not use ".DB_NAME.' : ' . mysql_error());
#	$device_serial = 0;
#	$api_response = false;		
#	$array = Array(API_JSON_RESPONSE=>$api_response,DB_COLUMN_DEVICE_SERIAL=>$device_serial);
#	echo json_encode($array);

#	mysql_close($con);
#	die(json_encode(Array(API_JSON_RESPONSE=>$api_response)),254);

}

$table = DB_TABLE_DEVICE_REGISTER;

#query imei from table
$query=" SELECT * FROM $table WHERE device_imei = '$device_imei' ";
$result=mysql_query($query,$con);
$num=mysql_numrows($result);

if($num<1){
	$ip = $province = $city = "";
	$ip = getRemoteIP();
	$ipDecode = ipDecode($ip);
	if($ipDecode){
		$provinceDecode = getProvince($ipDecode);
		if($provinceDecode){
			$province = $provinceDecode[0];
			$city = $provinceDecode[1];				
		}
	}
	    
	$query = "INSERT INTO $table ( device_imei, device_brand, device_model, device_sdk , device_release, device_app_verCode, device_regist_stamp, device_ip,device_province,device_city) 
	VALUES ( '$device_imei', '$device_brand', '$device_model', $device_sdk, '$device_release', $device_app_verCode, '$device_regist_stamp','$ip', '$province', '$city')"; 
	$result = mysql_query($query,$con);
    
}



//for activation reporter

require_once '../utilities/class.log.php';


$table = DB_TABLE_ACTIVATION_REPORTER;
$col_date = DB_COLUMN_ACTIVATION_DATE;
$col_count = DB_COLUMN_ACTIVATION_COUNT;
$col_stamp = DB_COLUMN_ACTIVATION_UPDATE_STAMP;
$date = date('Y-m-d');

#$query=" SELECT * FROM $table WHERE version_code > $device_app_verCode AND version_sdk <= $device_sdk ORDER BY $order DESC LIMIT 1";
$query=" SELECT * FROM $table WHERE $col_date = '$date' LIMIT 1";

//Log::i('function '.__FUNCTION__.'()'.' class::'.__CLASS__.' Line:'.__LINE__.' at '.__FILE__ 
//,$query);
		
$result=mysql_query($query,$con);
//Log::i("error:".mysql_error($con));


$num=mysql_numrows($result);
//Log::i("num:".$num);		
if($num>0){
	$count=mysql_result($result,0,DB_COLUMN_ACTIVATION_COUNT);
	$stamp = date('Y-m-d H:i:s');
	$count++;
	$query = " UPDATE $table SET $col_count = $count, $col_stamp = '$stamp' WHERE $col_date = '$date'";
}
else{
	$count=1;
	$stamp = date('Y-m-d H:i:s');
	$query = "INSERT INTO $table ($col_count, $col_date, $col_stamp) VALUES ($count,'$date','$stamp')";
	
}
$result=mysql_query($query,$con);


$table = DB_TABLE_VERSION_REPOSITORY;
$order = DB_COLUMN_VERSION_CODE;
#$query=" SELECT * FROM $table WHERE version_code > $device_app_verCode AND version_sdk <= $device_sdk ORDER BY $order DESC LIMIT 1";
$query=" SELECT * FROM $table ORDER BY $order DESC LIMIT 1";
$result=mysql_query($query,$con);
$num=mysql_numrows($result);
		
if($num>0){

	$version_code=mysql_result($result,0,DB_COLUMN_VERSION_CODE);
	$version_name=mysql_result($result,0,DB_COLUMN_VERSION_NAME);
	$version_sdk=mysql_result($result,0,DB_COLUMN_VERSION_SDK);
	$version_file=mysql_result($result,0,DB_COLUMN_VERSION_FILE);
	$version_new=mysql_result($result,0,DB_COLUMN_VERSION_NEW);
	
	$api_response = true;
	$array = Array(API_JSON_RESPONSE=>$api_response,DB_COLUMN_VERSION_CODE=>$version_code,DB_COLUMN_VERSION_NAME=>$version_name,DB_COLUMN_VERSION_SDK=>$version_sdk,DB_COLUMN_VERSION_FILE=>$version_file,DB_COLUMN_VERSION_NEW=>$version_new);

}
else{
	$array = Array(API_JSON_RESPONSE=>$api_response);
}

echo json_encode($array);

mysql_close($con);

?>
