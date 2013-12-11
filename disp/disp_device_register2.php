<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN">

<?php
//header("content-type;text/html;charset=utf-8");
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
    $city = "δ֪";
    foreach ($provinces as $province){
        if (strstr($loc, $province )){
            $city = $province;
            break;
        }
    }
    
    return $city;  
*/    
}

require_once '../db_conn.php';
mysql_close($con);
$table = $table=DB_TABLE_DEVICE_REGISTER;
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Display <?php echo $table; ?></title>
</head>

<body>

<h1>Display <?php echo $table; ?> data!</h1>

<?php
/*
mysql_select_db(DB_NAME,$con);

$where = 'device_regist_stamp';
$date = date("Y-m-d 00:00:00");

$order = DB_COLUMN_DEVICE_SERIAL;
#$query="SELECT * FROM $table WHERE $where > '$date' ORDER BY $order DESC LIMIT 3000";
$query="SELECT * FROM $table WHERE TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) <= 7 ORDER BY $order DESC LIMIT 500";
$result=mysql_query($query,$con);

$num=mysql_numrows($result);

mysql_close();
*/

require_once '../database/class.databaseproxy.php';
$db_aid = new DatabaseProxy();
$db_name_aid = 'androidaid';
$db_aid->connectDatabase($db_name_aid);
$pdo_aid = $db_aid->getPDO();

$db_namo = new DatabaseProxy();
$db_name_namo = 'namo';
$db_namo->connectDatabase($db_name_namo);
$pdo_namo = $db_namo->getPDO();

$table = $table=DB_TABLE_DEVICE_REGISTER;
$order = DB_COLUMN_DEVICE_SERIAL;
$sql = "SELECT * FROM $table WHERE TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) <= 7 ORDER BY $order DESC LIMIT 500";
$query = $pdo_aid->query($sql);
if ($query){
    $result=$query->fetchAll();
    $num = count($result);
}
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
while ($i < $num) 
{

?>
<tr>
<?php
    foreach($ArrayDeviceColumns as $columns){
        $value=$result[$i]["$columns"];
?>

<td>
<?php 
//	<font face="Arial, Helvetica, sans-serif">

/*
        if ($columns == "device_imei"){
            $imei = $value;
        }

        if($columns == 'device_city'){
            $dealer = "无数据";
    
            if(!empty($imei) && $pdo_namo != NULL){
                
                $table= 'shipment';
                $col_new_imei = 'new_imei';
                $col_dealer = 'dealer';
                $sql="SELECT $col_dealer FROM $table WHERE $col_new_imei = '$imei' LIMIT 1";
                $query=$pdo_namo->query($sql);

                if($query){
                    $row = $query->fetch();
                    $dealer = $row["$col_dealer"];
//                    $dealer = mb_convert_encoding($dealer, "gb2312", "utf-8");
                }
               $imei = '';  
            }
    
            echo $dealer;
        }
        else{
            echo $value; 
        }
*/
echo $value;
//	</font>

?>
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
