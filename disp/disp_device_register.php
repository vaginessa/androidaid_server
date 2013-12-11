<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php
//header("content-type;text/html;charset=utf-8");
function getIPLoc($queryIP)
{  
    $url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$queryIP;  

    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_ENCODING ,'gb2312');
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
    $result = curl_exec($ch);  
    $result = mb_convert_encoding($result, "utf-8", "gb2312"); // 编码转换，否则乱码  
    curl_close($ch);  
    preg_match("@<span>(.*)</span></p>@iU",$result,$ipArray);  
    $loc = $ipArray[1];  
    return $loc;
/*    
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
    $city = "未知";
    foreach ($provinces as $province){
        if (strstr($loc, $province )){
            $city = $province;
            break;
        }
    }
    
    return $city;  
*/    
}

require_once "..\db_conn.php";
$table=DB_TABLE_DEVICE_REGISTER;
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Display <?php echo $table; ?></title>
</head>

<body>

<h1>Display <?php echo $table; ?> data!</h1>

<?php
mysql_select_db(DB_NAME,$con);




$where = 'device_regist_stamp';
$date = date("Y-m-d 00:00:00");

$order = DB_COLUMN_DEVICE_SERIAL;
#$query="SELECT * FROM $table WHERE $where > '$date' ORDER BY $order DESC LIMIT 3000";
$query="SELECT * FROM $table WHERE TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) <= 7 ORDER BY $order DESC LIMIT 500";
$result=mysql_query($query,$con);

$num=mysql_numrows($result);

mysql_close();
?>


<table border="1" cellspacing="2" cellpadding="2">

<tr>

<?php
foreach($ArrayDeviceColumns as $columns){
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
	foreach($ArrayDeviceColumns as $columns){
		$value=mysql_result($result,$i,$columns);
?>

<td>
	<font face="Arial, Helvetica, sans-serif">
<?php 

if($columns == 'device_ip'){
    $ip = $value;
}
if($columns == 'device_city'){
    if(!empty($ip)){
        $city = getIPLoc($ip);
        echo $city;
    }
    else{
        echo $value;
    }
}
else{
    echo $value; 
}

/*
if($columns == 'device_city'){
    $city = mb_convert_encoding($value, "utf-8", "gb2312");
    echo $city;
}
else{
    echo $value;
}
*/
?>
	</font>
</td>

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
