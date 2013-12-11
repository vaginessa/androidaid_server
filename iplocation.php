<?php
header("content-type;text/html;charset=utf-8");
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


function getIPLoc($queryIP)
{  
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
        $loc = "未知";  
    
    }  
    //return $loc;
    
    $provinces = Array(
        '北京',
        '上海',
        '天津',
        '重庆',
        '黑龙江',
        '吉林',
        '辽宁',
        '内蒙古',
        '河北',
        '山西',
        '陕西',
        '甘肃',
        '宁夏',
        '青海',
        '新疆',
        '西藏',
        '四川',
        '贵州',
        '云南',
        '河南',
        '山东',
        '江苏',
        '安徽',
        '浙江',
        '福建',
        '江西',
        '湖北',
        '湖南',
        '广东',
        '广西',
        '海南',
        '台湾',
        '香港',
        '澳门',
        '未知'
    );
    $result = "未知";
    foreach ($provinces as $province){
        if (strstr($loc, $province )){
            $result = $province;
            break;
        }
    }
    
    return $result;  
    
}

function getIPCity($queryIP)
{
	$url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$queryIP;

	$ch = curl_init($url);
	curl_setopt($ch,CURLOPT_ENCODING ,'gb2312');
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // ��ȡ��ݷ���
	$result = curl_exec($ch);
	$result = mb_convert_encoding($result, "utf-8", "gb2312"); // ����ת������������
	curl_close($ch);
	preg_match("@<span>(.*)</span></p>@iU",$result,$ipArray);
	$loc = "未知";
	if (isset($ipArray[1])){
		preg_match("中国(.*)&nbsp",$ipArray[1],$countryArray);
		if (isset($countryArray[1])){
			preg_match("中国(.*)[省区]",$countryArray[1],$provinceArray);
			preg_match("[省区](.*)(市|地区)",$countryArray[1],$cityArray);
			if(isset($provinceArray[1])){
				$province = $provinceArray[1];
				if(isset($cityArray[1])){
					$city = $cityArray[1];
				}
			}
			else {
				preg_match("中国(.*)市",$countryArray[1],$provinceArray);
				preg_match("中国(.*)市",$countryArray[1],$cityArray);
				if(isset($provinceArray[1])){
					$province = $provinceArray[1];
					if(isset($cityArray[1])){
						$city = $cityArray[1];
					}
				}
			}
			$loc = $province."=>".$city;
		}
	}
	return $loc;

}


require_once dirname(__FILE__).'/db_conn.php';
mysql_close($con);
require_once dirname(__FILE__).'/database/class.databaseproxy.php';

$db_aid = new DatabaseProxy();
$db_name_aid = 'androidaid';
$db_aid->connectDatabase($db_name_aid);
$pdo_aid = $db_aid->getPDO();

$db_namo = new DatabaseProxy();
$db_name_namo = 'namo';
$db_namo->connectDatabase($db_name_namo);
$pdo_namo = $db_namo->getPDO();

$table = $table=DB_TABLE_DEVICE_REGISTER;;
$order = DB_COLUMN_DEVICE_SERIAL;

/*
$sql = "SELECT * FROM $table WHERE TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) <= 1 ORDER BY $order DESC LIMIT 500";
$query = $pdo_aid->query($sql);
if ($query){
    $result=$query->fetchAll();
    $num = count($result);
}

$i = 0;
while ($i < $num) {
    $ip = $result[$i]['device_ip'];
    $serial = $result[$i]['device_serial'];
    $province = getIPLoc($ip);
//    $city = mb_convert_encoding($city, "gb2312", "utf-8"); // ����ת������������  
    
    $sql = "UPDATE $table SET device_province = '$province' WHERE device_serial = $serial";
    $pdo_aid->query($sql);
    $i++;
}
*/
$sql = "SELECT * FROM $table WHERE TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) <= 7 AND device_province = '未知' ORDER BY $order DESC LIMIT 500";
$query = $pdo_aid->query($sql);
if ($query){
    $result=$query->fetchAll();
    $num = count($result);
}

$i = 0;
while ($i < $num) {
    $ip = $result[$i]['device_ip'];
	$ipDecode = ipDecode($ip);
	if($ipDecode){
		$provinceDecode = getProvince($ipDecode);
		if($provinceDecode){
			$province = $provinceDecode[0];
			$city = $provinceDecode[1];				
		}
	}
    $update_time = date('Y-m-d H:i:s');
    $sql = "UPDATE $table SET device_province = '$province', device_city = '$city', device_update_time = '$update_time' WHERE device_serial = $serial";
    $pdo_aid->query($sql);
    $i++;
}
?>