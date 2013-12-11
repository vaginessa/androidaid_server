<?php 

$page_title = "激活量统计报表——时间趋势图!"; 
$link = "disp_device_statistics_province.php";
$link_name = '查询"激活量分省分布图"';
require_once '../res/header.php';
//require_once '../res/navigator.php';
//echo '<hr />';
//require_once '../res/righttop_link.php';
?>
<?php 

function draw_bar_graph_num($weidth, $height, $data, $max_value,$average_value, $filename,  $left,$top, $right, $bottom)
{
    $image = imagecreate($weidth+$left+$right, $height+$top+$bottom);
    $x0 = $left;
    $y0 = $top + $height;
    $MAX = 255;
    $LIGHT = 192;
    $DARK = 128;
    $MIN = 0;
    $bg_color = imagecolorallocate($image, $MAX, $MAX, $MAX);
    $text_color = imagecolorallocate($image, $MAX, $MIN, $MIN);
    $bar_color = imagecolorallocate($image, $MIN, $DARK, $DARK);
    $border_color = imagecolorallocate($image, $DARK, $DARK, $DARK);
    $scale_color = imagecolorallocate($image, $MIN, $MIN, $MIN);
    
    imagefilledrectangle($image, $x0, $y0, $x0+$left, $top, $bg_color);
    imageline($image, $x0, $y0, $x0+$weidth+$right, $y0, $border_color);
    imageline($image, $x0, $y0, $x0, $y0-$height-$top, $border_color);
    
    imageline($image, $x0, $y0-$height, $x0+$weidth+$right, $y0-$height, $border_color);
    imagettftext($image, 16, 
                    0, 
                    0  , 
                    $y0-$height, 
                    $text_color, 
                    "../res/huawen.ttf", 
                    $max_value);
    
    imageline($image, $x0, $y0-$average_value/$max_value*$height, $x0+$weidth+$right, $y0-$average_value/$max_value*$height, $border_color);
    imagettftext($image, 16, 
                    0, 
                    0  , 
                    $y0-$average_value/$max_value*$height, 
                    $text_color, 
                    "../res/huawen.ttf", 
                    $average_value);
    
    
    $bar_weidth = $weidth / (count($data)*2 + 1);
    $font_size = 24;
    if ($font_size > $bar_weidth -4 ){
        $font_size = $bar_weidth -4 ;
    }
    $l = count($data);
    for ($i=0; $i<$l; $i++){
        $j = $l-1-$i;
        imagefilledrectangle($image, $x0 + ($i*$bar_weidth*2)+$bar_weidth, 
                    $y0, 
                    $x0 + (($i+1)*$bar_weidth*2), 
                    $y0 - ($height/$max_value*($data[$j]['num'])), $bar_color);

        imagettftext($image, $font_size, 
                    90, 
                    $x0 + ($i*$bar_weidth*2)+$bar_weidth*2 -2 , 
                    $y0 - 5, 
                    $text_color, 
                    "../res/huawen.ttf", 
                    $data[$j]['name']);
                    
        imagettftext($image, 24, 
                    0, 
                    $x0 + ($i*$bar_weidth*2)+$bar_weidth , 
                    $y0 + 5 +24, 
                    $text_color, 
                    "../res/huawen.ttf", 
                    $data[$j]['scale']);
    }
    
    imagepng($image,$filename,5);
    imagedestroy($image);
    
}

function draw_bar_graph_sum($weidth, $height, $data, $filename,  $left,$top, $right, $bottom)
{
    $max_sum = $data[0]['sum'];
    $image = imagecreate($weidth+$left+$right, $height+$top+$bottom);
    $x0 = $left;
    $y0 = $top + $height;
    $MAX = 255;
    $LIGHT = 192;
    $DARK = 128;
    $MIN = 0;
    $bg_color = imagecolorallocate($image, $MAX, $MAX, $MAX);
    $text_color = imagecolorallocate($image, $MAX, $MIN, $MIN);
    $bar_color = imagecolorallocate($image, $MIN, $DARK, $DARK);
    $border_color = imagecolorallocate($image, $DARK, $DARK, $DARK);
    $scale_color = imagecolorallocate($image, $MIN, $MIN, $MIN);
    
    imagefilledrectangle($image, $x0, $y0, $x0+$left, $top, $bg_color);
    imageline($image, $x0, $y0, $x0+$weidth+$right, $y0, $border_color);
    imageline($image, $x0, $y0, $x0, $y0-$height-$top, $border_color);
    
    imageline($image, $x0, $y0-$height, $x0+$weidth+$right, $y0-$height, $border_color);
    imagettftext($image, 16, 
                    0, 
                    0  , 
                    $y0-$height, 
                    $text_color, 
                    "../res/huawen.ttf", 
                    $max_sum);
    
    
    
    $bar_weidth = $weidth / (count($data)*2 + 1);
    $font_size = 24;
    if ($font_size > $bar_weidth -4 ){
        $font_size = $bar_weidth -4 ;
    }
    $l = count($data);
    if($l > 0){
        for ($i=0; $i<$l; $i++){
            $j = $l-1-$i;
            imagefilledrectangle($image, $x0 + ($i*$bar_weidth*2)+$bar_weidth, 
                        $y0, 
                        $x0 + (($i+1)*$bar_weidth*2), 
                        $y0 - ($height/$max_sum*($data[$j]['sum'])), $bar_color);
    
            imagettftext($image, $font_size, 
                        90, 
                        $x0 + ($i*$bar_weidth*2)+$bar_weidth*2 -2 , 
                        $y0 - 5, 
                        $text_color, 
                        "../res/huawen.ttf", 
                        $data[$j]['name']);
                        
            imagettftext($image, 24, 
                        0, 
                        $x0 + ($i*$bar_weidth*2)+$bar_weidth , 
                        $y0 + 5 +24, 
                        $text_color, 
                        "../res/huawen.ttf", 
                        $data[$j]['scale']);
        }
    }
    imagepng($image,$filename,5);
    imagedestroy($image);
    
}

?>
<hr />
<?php 
require_once '../utilities/class.devicemanager.php';
require_once '../utilities/Utilities.php';

$mgr = new DevicedManager();
$left = 50;
$top = 50;
$right = 50;
$bottom = 100;
$sale_start = '2012-09-23 00:00:00';
?>
<h3 align="center">日激活量趋势图</h3>
<h4>滚动日激活量</h4>
<?php 
$day = -1;
$day_max = 0;
$i=0;
$step = 0;
$total_day = 0;
do{
    $day++;
    $num_day = $mgr->fetchStatisticsDay($day,$sale_start);
    if ($num_day > 0){
        $array_day[$i]['num'] = $num_day;
        $total_day += $num_day;
        $array_day[$i]['name'] = "";
        $array_day[$i]['scale'] = Utilities::getDateDayBefore($day)->mon."月";
        if($i>0){
            if ($array_day[$i-1]['scale'] == $array_day[$i]['scale']){
                $array_day[$i-1]['scale'] = "";
                $step++;
            }
            else{
                $step = 0;
            }
        }
        if($num_day>$day_max){
            $day_max = $num_day;
        }
        $i++;
    }
}while($day<365);
if ($step < 15){
    $array_day[$i-1]['scale'] = "";
}
$average_day = intval(round(($total_day-$array_day[0]['num'])/(count($array_day)-1)));

$filename = '../images/bar_day.png';
unlink($filename);
$bar_width = 30;
$weidth = $bar_width*$i*2;
if ($weidth > 1024){
    $weidth = 1024;
}

draw_bar_graph_num($weidth, 300, $array_day, $day_max,$average_day, $filename, $left,$top,$right,$bottom);
echo '<img src="'.$filename.'"/>';
?>
<h4>累计日激活量</h4>
<?php 
$filename = '../images/bar_day_sum.png';
unlink($filename);
$l = count($array_day);
$array_day[$l-1]['sum'] = $array_day[$l-1]['num'];
for ($m=$l-2; $m>=0; $m--){
    $array_day[$m]['sum'] = $array_day[$m]['num'] + $array_day[$m+1]['sum'];
}
if ($step<3){
    $array_day[$m-1]['scale'] = "";
}
draw_bar_graph_sum($weidth, 300, $array_day, $filename, $left,$top,$right,$bottom);
echo '<img src="'.$filename.'"/>';
?>

<h3 align="center">周激活量趋势图</h3>
<h4>滚动周激活量</h4>

<?php 
$week = -1;
$week_max = 0;
$i=0;
$step = 0;
$total_week = 0;
do{
    $week++;
    $num_week = $mgr->fetchStatisticsWeek($week,$sale_start);
    if ($num_week > 0){
        $week_index = Utilities::getWeekWeekBefore($week);
        $array_week[$i]['num'] = $num_week;
        $total_week += $num_week;
        $array_week[$i]['name'] = $week_index."周";;
        $array_week[$i]['scale'] = Utilities::getDateWeekBefore($week)->mon."月";
        if($i>0){
            if ($array_week[$i-1]['scale'] == $array_week[$i]['scale']){
                $array_week[$i-1]['scale'] = "";
                $step ++;
            }
            else{
                $step = 0;
            }
        }
        if($num_week>$week_max){
            $week_max = $num_week;
        }
        $i++;
    }
}while($week<53);

$average_week = intval(round(($total_week-$array_week[0]['num'])/(count($array_week)-1)));

$filename = '../images/bar_week.png';
unlink($filename);
$bar_width = 30;
$weidth = $bar_width*$i*2;
if ($weidth > 1024){
    $weidth = 1024;
}

draw_bar_graph_num($weidth, 300, $array_week, $week_max,$average_week, $filename, $left,$top,$right,$bottom);
echo '<img src="'.$filename.'"/><br />';
?>
<h4>累计周激活量</h4>

<?php 
$filename = '../images/bar_week_sum.png';
unlink($filename);
$l = count($array_week);
$array_week[$l-1]['sum'] = $array_week[$l-1]['num'];
for ($m=$l-2; $m>=0; $m--){
    $array_week[$m]['sum'] = $array_week[$m]['num'] + $array_week[$m+1]['sum'];
}
if ($step<3){
    $array_week[$m-1]['scale'] = "";
}
draw_bar_graph_sum($weidth, 300, $array_week, $filename, $left,$top,$right,$bottom);
echo '<img src="'.$filename.'"/>';

?>

<h3 align="center">月度激活量趋势图</h3>
<h4>滚动月激活量</h4>
<?php 
$mon = -1;
$i =0 ;
$mon_max = 0;
$step = 0;
$total_mon = 0;

do{
    $mon++;
    $num_mon = $mgr->fetchStatisticsMon($mon,$sale_start);
    if ($num_mon > 0){
        
        $year_mon = Utilities::getDateMonthBefore($mon);
        $array_mon[$i]['num'] = $num_mon;
        $total_mon += $num_mon;
        $array_mon[$i]['name'] = $year_mon->mon."月";
        $array_mon[$i]['scale'] = $year_mon->year."年";
        if($i>0){
            if ($array_mon[$i-1]['scale'] == $array_mon[$i]['scale']){
                $array_mon[$i-1]['scale'] = "";
                $step ++ ;
            }
            else{
                $step = 0;
            }
        }
        if($num_mon>$mon_max){
            $mon_max = $num_mon;
        }
        $i++;
    }
}while($num_mon>0);
if ($step < 3){
    $array_mon[$i-1]['scale'] = "";
}

$average_mon = intval(round(($total_mon-$array_mon[0]['num']-$array_mon[count($array_mon)-1]['num'])/(count($array_mon)-2)));
//$average_mon = intval(round($total_day/(count($array_day)/30)));

$filename = '../images/bar_mon.png';
unlink($filename);
$bar_width = 30;
$weidth = $bar_width*$i*2;
if ($weidth > 1024){
    $weidth = 1024;
}

draw_bar_graph_num($weidth, 300, $array_mon, $mon_max, $average_mon, $filename, $left,$top,$right,$bottom);
echo '<img src="'.$filename.'"/>';
?>
<h4>累计月激活量</h4>

<?php 
$filename = '../images/bar_mon_sum.png';
unlink($filename);
$l = count($array_mon);
$array_mon[$l-1]['sum'] = $array_mon[$l-1]['num'];
for ($m=$l-2; $m>=0; $m--){
    $array_mon[$m]['sum'] = $array_mon[$m]['num'] + $array_mon[$m+1]['sum'];
}
if ($step<3){
    $array_mon[$m-1]['scale'] = "";
}
draw_bar_graph_sum($weidth, 300, $array_mon, $filename, $left,$top,$right,$bottom);
echo '<img src="'.$filename.'"/>';

?>


<?php 
require_once '../res/footer.php';
?>
