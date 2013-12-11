<?php 

$page_title = "激活量分省分布图"; 
$link = "disp_device_statistics.php";
$link_name = '查询"激活量时间趋势图"';
require_once '../res/header.php';
//require_once '../res/navigator.php';
//echo '<hr />';
//require_once '../res/righttop_link.php';
?>
<?php 
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
        '澳门'//,
//        '未知'
    );
    $provinces_color = Array(
        0xff0000,//'北京',
        0x00ff00,//'上海',
        0x0000ff,//'天津',
        0xffff00,//'重庆',
        0x00ffff,//'黑龙江',
        0xff00ff,//'吉林',
        0x99ff00,//'辽宁',
        0x0099ff,//'内蒙古',
        0xff0099,//'河北',
        0xff3333,//'山西',
        0x33ff33,//'陕西',
        0x3333ff,//'甘肃',
        0xff6666,//'宁夏',
        0x66ff66,//'青海',
        0x6666ff,//'新疆',
        0xff6600,//'西藏',
        0x00ff66,//'四川',
        0x6600ff,//'贵州',
        0xff3300,//'云南',
        0x00ff33,//'河南',
        0x3300ff,//'山东',
        0xff9966,//'江苏',
        0x66ff99,//'安徽',
        0x9966ff,//'浙江',
        0xff33ff,//'福建',
        0xffff33,//'江西',
        0x33ffff,//'湖北',
        0xff66ff,//'湖南',
        0xffff66,//'广东',
        0x66ffff,//'广西',
        0xff99ff,//'海南',
        0xffff99,//'台湾',
        0x99ffff,//'香港',
        0xff9933,//'澳门'//,
//        '未知'
    );
    
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
    
    imagepng($image,$filename,5);
    imagedestroy($image);
    
}

?>
<hr />

<?php 
require_once '../utilities/class.devicemanager.php';
require_once '../utilities/Utilities.php';

$mgr = new DevicedManager();
$i = 0;
$j = 0;
$total_province = 0;
$max_provice = 0;
foreach ($provinces as $province){
    $sum_province = $mgr->fetchStatisticsProvince("",$province);
    if ($sum_province['num']>0){
        $array_province[$i]['num'] = $sum_province['num'];
        $total_province += $sum_province['num'];
        $array_province[$i]['name'] = $sum_province['province'];
        $array_province[$i]['scale'] = "";
        $array_province[$i]['color'] = $provinces_color[$j];
        $i++;
        if($sum_province['num'] > $max_provice){
            $max_provice = $sum_province['num'];
        }
    }
    $j++;
}
$average_province = intval(round($total_province/count($array_province)));
// 取得列的列表
foreach ($array_province as $key => $row) {
    $num[$key]  = $row['num'];
    $name[$key] = $row['name'];
}

// 将数据根据 volume 降序排列，根据 edition 升序排列
// 把 $data 作为最后一个参数，以通用键排序
array_multisort($num, SORT_DESC, $name, SORT_ASC, $array_province);

?>


<?php 
//+------------------------+ 
//| pie3dfun.PHP//公用函数 | 
//+------------------------+ 
define("ANGLE_STEP", 3); //定义画椭圆弧时的角度步长 
define("FONT_USED", "../res/huawen.ttf"); // 使用到的字体文件位置 

function draw_getdarkcolor($img,$clr) //求$clr对应的暗色 
{ 
    $rgb = imagecolorsforindex($img,$clr); 
    return array($rgb["red"]/2,$rgb["green"]/2,$rgb["blue"]/2); 
} 

function draw_getexy($a, $b, $d) //求角度$d对应的椭圆上的点坐标 
{ 
    $d = deg2rad($d); 
    return array(round($a*Cos($d)), round($b*Sin($d))); 
} 

function draw_arc($img,$ox,$oy,$a,$b,$sd,$ed,$clr) //椭圆弧函数 
{ 
    $n = ceil(($ed-$sd)/ANGLE_STEP); 
    $d = $sd; 
    list($x0,$y0) = draw_getexy($a,$b,$d); 
    for($i=0; $i<$n; $i++) 
    { 
        $d = ($d+ANGLE_STEP)>$ed?$ed:($d+ANGLE_STEP); 
        list($x, $y) = draw_getexy($a, $b, $d); 
        imageline($img, $x0+$ox, $y0+$oy, $x+$ox, $y+$oy, $clr); 
        $x0 = $x; 
        $y0 = $y; 
    } 
} 

function draw_sector($img, $ox, $oy, $a, $b, $sd, $ed, $clr) //画扇面 
{ 
    $n = ceil(($ed-$sd)/ANGLE_STEP); 
    $d = $sd; 
    list($x0,$y0) = draw_getexy($a, $b, $d); 
    imageline($img, $x0+$ox, $y0+$oy, $ox, $oy, $clr); 
    for($i=0; $i<$n; $i++) 
    { 
        $d = ($d+ANGLE_STEP)>$ed?$ed:($d+ANGLE_STEP); 
        list($x, $y) = draw_getexy($a, $b, $d); 
        imageline($img, $x0+$ox, $y0+$oy, $x+$ox, $y+$oy, $clr); 
        $x0 = $x; 
        $y0 = $y; 
    } 
    imageline($img, $x0+$ox, $y0+$oy, $ox, $oy, $clr); 
    list($x, $y) = draw_getexy($a/2, $b/2, ($d+$sd)/2); 
    imagefill($img, $x+$ox, $y+$oy, $clr); 
} 

function draw_sector3d($img, $ox, $oy, $a, $b, $v, $sd, $ed, $clr) //3d扇面 
{ 
    draw_sector($img, $ox, $oy, $a, $b, $sd, $ed, $clr); 
    if($sd<180) 
    { 
        list($R, $G, $B) = draw_getdarkcolor($img, $clr); 
        $clr=imagecolorallocate($img, $R, $G, $B); 
        if($ed>180) $ed = 180; 
        list($sx, $sy) = draw_getexy($a,$b,$sd); 
        $sx += $ox; 
        $sy += $oy; 
        list($ex, $ey) = draw_getexy($a, $b, $ed); 
        $ex += $ox; 
        $ey += $oy; 
        imageline($img, $sx, $sy, $sx, $sy+$v, $clr); 
        imageline($img, $ex, $ey, $ex, $ey+$v, $clr); 
        draw_arc($img, $ox, $oy+$v, $a, $b, $sd, $ed, $clr); 
        list($sx, $sy) = draw_getexy($a, $b, ($sd+$ed)/2); 
        $sy += $oy+$v/2; 
        $sx += $ox; 
        imagefill($img, $sx, $sy, $clr); 
    } 
} 

function draw_getindexcolor($img, $clr) //RBG转索引色 
{ 
    $R = ($clr>>16) & 0xff; 
    $G = ($clr>>8)& 0xff; 
    $B = ($clr) & 0xff; 
    return imagecolorallocate($img, $R, $G, $B); 
} 

// 绘图主函数，并输出图片 
// $datLst 为数据数组, $datLst 为标签数组, $datLst 为颜色数组 
// 以上三个数组的维数应该相等 
function draw_img($datLst,$labLst,$clrLst,$filename,$a=300,$b=200,$v=20,$font=14) 
{ 
    $left = 20;
    $top = 50;
    $right = 50;
    $bottom = 50;
    $fw = 12;//imagefontwidth($font); 
    $fh = 16;//imagefontheight($font); 
    $label_weidth = 250;
    $n = count($datLst);//数据项个数 
    $w = 10+$a*2 + $label_weidth*2 +$left + $right ; 
    $h = 10+$b*2 + $v+($fh+2)*$n;
    $h1 = 10+$b*2;
    $h2 = $v+($fh+2)*$n;
    if($h2>$h1){
        $h=$h2+$top+$bottom; 
    }
    else{
        $h = $h1+$top+$bottom;
    }
    
    $img = imagecreate($w, 540); 
    
    $ox = 5+$a + $label_weidth + $left; 
    $oy = 5+$b + $top; 
    
    //转RGB为索引色 
    
    for($i=0; $i<$n; $i++) 
        $clrLst[$i] = draw_getindexcolor($img,$clrLst[$i]); 
    $clrbk = imagecolorallocate($img, 0xff, 0xff, 0xff); 
    $clrt = imagecolorallocate($img, 0, 0, 0); 
    //填充背景色 
    imagefill($img, 0, 0, $clrbk); 
    //求和 
    $tot = 0; 
    for($i=0; $i<$n; $i++) 
        $tot += $datLst[$i]; 
    $sd = 0; 
    $ed = 0; 
    $ly = 10;//+$b*2+$v; 
    $ly += $fh+2; 
    for($i=0; $i<$n; $i++) 
    { 
        $sd = $ed; 
        $ed += $datLst[$i]/$tot*360; 
        //画圆饼 
        draw_sector3d($img, $ox, $oy, $a, $b, $v, $sd, $ed, $clrLst[$i]); //$sd,$ed,$clrLst[$i]); 
        //画标签 
    
        $tag_left = 5+$left;
        $tag_top = $ly +$top;
        if ($i>intval(round($n/2))){
            $tag_left += $a*2 + 300;
            $tag_top = $ly - $fh*intval(round($n/2));
        }
        imagefilledrectangle($img, $tag_left, $tag_top, $tag_left+$fw, $tag_top+$fh, $clrLst[$i]); 
        imagerectangle($img, $tag_left, $tag_top, $tag_left+$fw, $tag_top+$fh, $clrt); 
        //imagestring($img, $font, 5+2*$fw, $ly, $labLst[$i].":".$datLst[$i]."(".(round(10000*($datLst[$i]/$tot))/100)."%)", $clrt); 
        //$str = iconv("GB2312", "UTF-8", $labLst[$i]); 
        $str = $labLst[$i];
        $label_left = 5+2*$fw+$left;
        $lable_top = $ly+13+$top;
        if ($i>intval(round($n/2))){
            $label_left += $a*2 + 300;
            $lable_top = $ly+13 - $fh*intval(round($n/2));
        }
        ImageTTFText($img, $font, 0, $label_left , $lable_top, $clrt, FONT_USED, $str.":".$datLst[$i]."(".(round(10000*($datLst[$i]/$tot))/100)."%)"); 
        $ly += $fh+2; 
    } 
    //输出图形 
    //header("Content-type: image/png"); 
    //输出生成的图片 
    //imagepng($img); 
    

    imagepng($img,$filename,5);
    imagedestroy($img);
    

} 

/*
$datLst = array(30, 20, 20, 20, 10, 20, 10, 20); //数据 
$labLst = array("浙江省", "广东省", "上海市", "北京市", "福建省", "江苏省", "湖北省", "安徽省"); //标签 
$clrLst = array(0x99ff00, 0xff6666, 0x0099ff, 0xff99ff, 0xffff99, 0x99ffff, 0xff3333, 0x009999); 
*/
$i=0;
foreach ($array_province as $province){
    $datLst[$i] = $province['num']; 
    $labLst[$i] = $province['name']; 
    $clrLst[$i] = $province['color']; 
    $i++;
}


//画图 
$filename = '../images/pie_province.png'; 
unlink($filename);
draw_img($datLst,$labLst,$clrLst,$filename); 


?>
<h3 align="center">分省分布饼状图</h3>
<div align="center"> <img src="<?php echo $filename;?>"  /> </div>


<h3 align="center">分省分布柱状图</h3>
<?php 

$filename = '../images/bar_province.png';
unlink($filename);
$bar_width = 30;
$weidth = $bar_width*$i*2;
if ($weidth > 1024){
    $weidth = 1024;
}
$left = 50;
$top = 50;
$right = 50;
$bottom = 100;
draw_bar_graph_num($weidth, 300, $array_province, $max_provice, $average_province, $filename, $left,$top,$right,$bottom);
echo '<img src="'.$filename.'"/>';
?>

<?php 
require_once '../res/footer.php';
?>
