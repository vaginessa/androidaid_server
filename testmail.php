<?php

$last_mon_array = array(
    1   =>    31,
    
    2    =>    31,
    3    =>    28,
    4    =>    31,
    5    =>    30,
    6    =>    31,
    7    =>    30,
    8    =>    31,
    9    =>    31,
    10    =>    30,
    11   =>    31,
    12   =>    30
);

date_default_timezone_set('Asia/Shanghai'); 
$date = getdate();
$year = $date['year'];
$mon = $date['mon'];
$mday = $date['mday'];
$wday = $date['wday'];


require_once dirname(__FILE__).'/db_conn.php';

$table=DB_TABLE_DEVICE_REGISTER;
mysql_select_db(DB_NAME,$con);

//require_once dirname(__FILE__).'/utilities/Utilities.php';
require_once dirname(__FILE__).'/utilities/class.utilities.php';
require_once dirname(__FILE__).'/utilities/class.log.php';

$message = "N880激活量统计报告";

$message .= Utilities::getNewLine().Utilities::getNewLine()
                ."激活量时间趋势图：".Utilities::getNewLine()
                ."http://www.namo.com.cn:8088/androidaid/php/disp/disp_device_statistics.php";

$query="SELECT device_regist_stamp FROM $table WHERE TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) = 1";
$result=mysql_query($query,$con);
$day_num=mysql_numrows($result);
$message .= Utilities::getNewLine()."最新单日激活量：".$day_num;
$subject = "N880激活日报：最新日激活量-$day_num ！";
$report_days = 1;


if($wday==0){
    $query="SELECT device_regist_stamp FROM $table WHERE TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) >= 1 AND TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) <= 7";
    $result=mysql_query($query,$con);
    $week_num=mysql_numrows($result);
    $message .= Utilities::getNewLine().'周激活量：'.$week_num;    
    $subject = "N880激活周报：最新周激活量-$week_num ！";
    $report_days = 7;
}

if($mday==1){
    $mon_days = $last_mon_array[$mon];
    if($mon==3){
        if($year%4 == 0){
            $mon_days = 29;
        }
    }
    $query="SELECT device_regist_stamp FROM $table WHERE TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) >= 1 AND TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) <= $mon_days";
    $result=mysql_query($query,$con);
    $mon_num=mysql_numrows($result);
    $message .= Utilities::getNewLine().'月激活量：'.$mon_num;    
    $subject = "N880激活月报：最新月激活量-$mon_num ！";
    $report_days = $mon_days;
    
}

$query="SELECT device_regist_stamp FROM $table WHERE TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) >= 1";
$result=mysql_query($query,$con);
$total=mysql_numrows($result);
$message .= Utilities::getNewLine()."机型累计激活量：$total";

$query="SELECT device_regist_stamp FROM $table WHERE TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) >= 1 AND TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) <= 7";
$result=mysql_query($query,$con);
$_7days_num = mysql_numrows($result);
$message .= Utilities::getNewLine()." 7天滚动激活量：".$_7days_num;

$query="SELECT device_regist_stamp FROM $table WHERE TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) >= 1 AND TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) <= 30";
$result=mysql_query($query,$con);
$_30days_num=mysql_numrows($result);
$message .= Utilities::getNewLine()."30天滚动激活量：".$_30days_num;


require_once dirname(__FILE__).'/database/class.databaseproxy.php';
$db = new DatabaseProxy();
$db->connectDatabase('androidaid');
$pdo = $db->getPDO();
$sql="SELECT device_province FROM $table WHERE TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) >= 1 AND TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) <= $report_days";
$query = $pdo->query($sql);

if ($query){
//    $message .= Utilities::getNewLine().Utilities::getNewLine()."分省分布情况：";
    $total = $query->fetchAll();
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
    foreach ($provinces as $province){
        $num_array["$province"] = 0;
    }
    foreach ($total as $row){
        $province = $row['device_province'];
        $num_array["$province"] = $num_array["$province"] + 1;
    }
    
    $message .= Utilities::getNewLine().Utilities::getNewLine()
                ."激活量分省分布图：".Utilities::getNewLine()
                ."http://www.namo.com.cn:8088/androidaid/php/disp/disp_device_statistics_province.php";
    
    foreach ($provinces as $province){
        if ($num_array["$province"]>0){
            
            $message .= Utilities::getNewLine().$province."：".$num_array["$province"]."台";
        }
    }
    
                
}

$to_array =  array(
   
		'lisen@namo.com.cn',
    'zhangguotian@namo.com.cn',
    'kangyuan@namo.com.cn',
    'luoyongxing@namo.com.cn',
    'cuijibin@namo.com.cn',
    'patrick@namo.com.cn',

		'wangpeifeng@namo.com.cn'
		);

//$subject .= $year.'年'.$mon.'月'.$mday.'日 ';
$subject .= date('Y年m月d日',time()-24*60*60);		
//Log::i($subject);
//Log::i($message);

require_once dirname(__FILE__).'/proxy/class.mailproxy.php';
MailProxy::sendMail($to_array, $subject, $message);

mysql_close();
	
?>