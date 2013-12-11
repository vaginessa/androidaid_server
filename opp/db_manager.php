<?php

/******************************************************
 * Android APK File Parser
 * Author: Katana
 * Version: v0.1
 * Web: http://www.win-ing.cn
 *
 *
 ******************************************************/
#require('../Data.php'); 

class DBManager{
	
//----------------------
//----------------------
//    const AXML_FILE             = 0x00080003;

//    private static $RADIX_MULTS = array(0.00390625, 3.051758E-005, 1.192093E-007, 4.656613E-010);
    
    private $con = NULL;


//----------------------
//----------------------

	public function connect(){
		$this->con = mysql_connect("localhost","root","namo2010");
		if($this->con!=false){
			mysql_set_charset('uft8', $this->con);
			echo "DB connected ".$this->con."<br/>";
			return true;
		}
		else{
			echo "DB not connected";
			return false;
		}
		
	}
	
	public function close(){
		mysql_close($this->con);
	}
	
	public function select($db_name = "androidaid"){
#		echo $this->con;
		
		$result = mysql_select_db($db_name,$this->con);
		if($result){
			echo "table selected!".$db_name."<br/>";
		}
		else{
			echo "table not selected ".$db_name."<br/>";
		}
		return $result;
		
	}
	
	public function isApkInRepo($app_file_original){
		
		$filename = mb_convert_encoding($app_file_original,'UTF-8','GBK');
		
		$table = "app_repository";
		$app_online = "Online";
		$query = "SELECT * FROM $table WHERE app_file_original = '$filename'";// AND app_state = '$app_online'";
		$result= mysql_query($query,$this->con);
		$num=mysql_numrows($result);

		if($num>0){
			echo "<br/>".$app_file_original." in repository!"."<br/>";
			return true;
		}
		else {
			echo "<br/>".$app_file_original." is new a APK"."<br/>";
			return false;
		}

	}

	public function insertNewApk($app_file_original, $app_package, $app_version_code, 
	
	
		$app_version_name, $app_sdk_min){
		
		date_default_timezone_set('Asia/Shanghai'); //系统时间差8小时问题
		$app_deploy_date = Date("Y-m-d");
		$app_update_stamp =  date("Y-m-d H:i:s");
	 
		$table = 'app_repository';
/*		
		$query = "INSERT INTO $table ( app_file_original,app_package, app_version_code, app_version_name,
			app_file, app_sdk_min, app_state,app_deploy_date, app_update_stamp) 
			VALUES ( '$app_file_original','$app_package',$app_version_code,'$app_version_name',
			'$app_file',$app_sdk_min,'$app_state','$app_deploy_date','$app_update_stamp')"; 	
		

		$result=mysql_query($query,$this->con);
		if(!$result){
			echo "DB Error:".mysql_error()."<br/>";
		}
	*/
		$filename = mb_convert_encoding($app_file_original,'UTF-8','GBK');
		$query = "INSERT INTO $table ( app_file_original,app_deploy_date, app_update_stamp) 
		VALUES ( '$filename','$app_deploy_date','$app_update_stamp')"; 	
		echo $query."<br/>";
		
		$result = mysql_query($query,$this->con);// or die('DB Error: '.mysql_error($this->con).'<br/>');		
		if(!$result){
			echo "DB Error:".mysql_error()."<br/>";
			return false;
		}
		else{
			echo "Insert OK! <br/>";
		}
		
		$app_file = $app_package.'_'.$app_version_code.'.apk';
		$app_state = 'Upload';
		
		$filename = mb_convert_encoding($app_file_original,'UTF-8','GBK');
		$query = "UPDATE $table SET app_state = '$app_state',app_file = '$app_file' ,app_package = '$app_package', app_version_code = $app_version_code,  app_version_name = '$app_version_name', app_sdk_min = $app_sdk_min,app_update_stamp = '$app_update_stamp'
			WHERE app_file_original = '$filename' "; 	
		echo $query."<br/>";
		
		$result=mysql_query($query,$this->con);
		if(!$result){
			echo "DB Error:".mysql_error()."<br/>";
			return false;
		}
		else{
			echo "Update OK! <br/>";
			return true;
		}
		
	}
	
	
	
	public function updateApkInfo($app_file_original, $app_package, $app_version_code, 
		$app_version_name, $app_sdk_min){
		
		date_default_timezone_set('Asia/Shanghai'); //系统时间差8小时问题
//		$app_deploy_date = Date("Y-m-d");
		$app_update_stamp =  date("Y-m-d H:i:s");
	 
		$app_file = $app_package.'_'.$app_version_code.'.apk';
		$app_state = 'Upload';
		$table = 'app_repository';
		
		$filename = mb_convert_encoding($app_file_original,'UTF-8','GBK');
		
		$query = "UPDATE $table SET app_state = '$app_state',app_file = '$app_file' ,app_package = '$app_package', app_version_code = $app_version_code,  app_version_name = '$app_version_name', app_sdk_min = $app_sdk_min,app_update_stamp = '$app_update_stamp'
			WHERE app_file_original = '$filename'"; 	
		echo $query."<br/>";
		
		$result=mysql_query($query,$this->con);
		
		if(!$result){
			echo "DB Error:".mysql_error()."<br/>";
			return false;
		}
		else{
			echo "Update OK! <br/>";
			return true;
		}
		
		
	}
	
	
	
 
//----------------------
//----------------------
}

?>