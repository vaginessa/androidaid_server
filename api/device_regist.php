
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

require_once "..\constants.php";

$device_imei =  $_POST['device_imei'];
$device_brand =  $_POST['device_brand'];
$device_model =  $_POST['device_model'];
$device_sdk =  $_POST['device_sdk'];
$device_release =  $_POST['device_release'];
$device_app_verCode =  $_POST['version_code'];
$api_vresion = $_POST['api_version'];

date_default_timezone_set('Asia/Shanghai'); //
$device_regist_stamp =  date("Y-m-d H:i:s");

require_once "..\db_conn.php";
if (!$con)
{
#  die('Could not connect: ' . mysql_error());
	$device_serial = 0;
	$api_response = false;		
	$array = Array(API_JSON_RESPONSE=>$api_response,DB_COLUMN_DEVICE_SERIAL=>$device_serial);
	echo json_encode($array);
	die(254);
}

$db_selected=mysql_select_db(DB_NAME,$con);
if (!$db_selected)
{
#  die ("Can\'t use ".DB_NAME.' : ' . mysql_error());
	$device_serial = 0;
	$api_response = false;		
	$array = Array(API_JSON_RESPONSE=>$api_response,DB_COLUMN_DEVICE_SERIAL=>$device_serial);
	echo json_encode($array);
	mysql_close($con);
	die(254);
}

//$iplocation = getIPLoc(getRemoteIP());
//$query=" INSERT INTO cities (city_decode) VALUES ('$iplocation')";
//$result=mysql_query($query,$con);

$table = DB_TABLE_DEVICE_REGISTER;

#query imei from table
$query=" SELECT * FROM $table WHERE device_imei = $device_imei ";
$result=mysql_query($query,$con);
$num=mysql_numrows($result);

if($num>0){
	$device_serial=mysql_result($result,0,DB_COLUMN_DEVICE_SERIAL);
	$api_response = true;
}

else{

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
#	or die("Invalid query: " . mysql_error());
	
	if($result==true){
		$query=" SELECT * FROM $table WHERE device_imei = $device_imei ";
		$result=mysql_query($query,$con);
		$num=mysql_numrows($result);
		
		if($num>0){
			$device_serial=mysql_result($result,0,DB_COLUMN_DEVICE_SERIAL);
			$api_response = true;
		}
		else{
			$device_serial = 0;
			$api_response = false;		
		}
	
	}
	else{
			$device_serial = 0;
			$api_response = false;		
	}
}


$array = Array(API_JSON_RESPONSE=>$api_response,DB_COLUMN_DEVICE_SERIAL=>$device_serial);
echo json_encode($array);

mysql_close($con);



?>
