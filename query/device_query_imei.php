<?php 

$page_title = " 产品激活日期与地域查询！"; 
require_once '../res/header.php';
?>

<?php 
require_once '../proxy/class.databaseproxy.php';
$column_serial = DatabaseProxy::DB_COLUMN_DEVICE_SERIAL;
$column_imei = DatabaseProxy::DB_COLUMN_DEVICE_IMEI;
$column_province = DatabaseProxy::DB_COLUMN_DEVICE_PROVINCE;
$column_register_date = DatabaseProxy::DB_COLUMN_DEVICE_REGIST_STAMP;



?>
<hr />
				<form method="post" action="<?php echo $_SERVER ['PHP_SELF']; ?>">
					<div align="center">
						<table width="60%" border="3">
							<tr>
								<td width="30%">
									<div class="column_name">输入IMEI</div>
								</td>
								<td width="30%">
									<div class="column_name">激活省份</div>
								</td>
								<td width="40%">
									<div class="column_name">激活日期</div>
								</td>
							</tr>

<?php
if (isset($_POST['imei'])){
    $imei = $_POST['imei'];
    require_once dirname(dirname(__FILE__)).'/utilities/class.devicemanager.php';
    $mgr = new DevicedManager();
    $array = $mgr->queryDeviceByImei($imei);
    $row = $array[0];
}
else{
    $imei = "";
    $row["$column_province"];
    $row["$column_register_date"];
    
}
    
?>
							<tr>
								<td >
									<input type="submit" name="query" value="查询" />
									<input type="text" id="imei" name="imei" value="<?php echo $imei; ?>">
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_province"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_register_date"];?></div>
								</td>
							</tr>
					</table>
					</div>
				</form>

<?php 
require_once '../res/footer.php';
?>
