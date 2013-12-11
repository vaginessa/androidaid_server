<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php 

    if(!isset($page_title)){
        $page_title = "";
    }
    $xml = simplexml_load_file('../conf/app_conf.xml');
    $json = json_encode($xml);
    $obj = json_decode($json);
        
    $app_name = $obj->APP_NAME;  
    $app_org = $obj->APP_ORG;
    
    echo "<title>$app_name - $page_title</title>";    
    
    
?>

  <link rel="stylesheet" type="text/css" href="../styles/style.css" />
</head>
<body>
	<table>
		<tr>
			<td width="20%">
				<div align="left">
<?php 
    require '../res/logo_left.php';
?>
				</div>
			</td>
			<td>
		<div align="left">
			<h2><?php echo $app_org.'"'.$app_name.'”'.$page_title; ?></h2>
		</div>
			</td>
			<td width="50%">
				<div align="right">
					<a href="<?php echo $link; ?>"><?php echo $link_name; ?></a>
				</div>
			</td>
		</tr>
	</table>