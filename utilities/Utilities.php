<?php

require_once dirname(__FILE__).'/class.phpmailer.php';
require_once dirname(__FILE__).'/class.log.php';

class Utilities{
	
    const SMTP_HOST = "smtp.namo.com.cn";
	const SMTP_PORT = 25;
	const SMTP_AUTH = TRUE;
	const SMTP_SECURE = '';
	const SMTP_USERNAME = "androidaid";
	const SMTP_PASSWORD = "123.com";
	const FROM = "androidaid@namo.com.cn";
	const FRON_NAME = "易捷桌面";
	
	
	public static function sendMail($to_array, $subject, $message, $from = self::FROM, $from_name = self::FRON_NAME){

		Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__);
		
		$mail = new PHPMailer();
		
		Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__);
		
		$mail->CharSet = 'UTF-8';
		$mail->Mailer = 'smtp';
		$mail->IsSMTP();                                      // set mailer to use SMTP
		$mail->Host = self::SMTP_HOST;  // specify main and backup server
		$mail->Port = self::SMTP_PORT;
		$mail->SMTPAuth = self::SMTP_AUTH;     // turn on SMTP authentication
		$mail->SMTPSecure = self::SMTP_SECURE;
		$mail->Username = self::SMTP_USERNAME;  // SMTP username
		$mail->Password = self::SMTP_PASSWORD; // SMTP password

		$mail->From = $from;
		
		$mail->FromName = $from_name;
		
		for($i=0; $i<count($to_array); $i++){
		    $mail->AddAddress($to_array[$i]);                  // name is optional
		}
		
		$mail->WordWrap = 50;                                 // set word wrap to 50 characters
		$mail->IsHTML(false);                                  // set email format to HTML

		$mail->Subject = $subject;
		$mail->Body    = $message;
		Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__);
		try{
		
			$mail->Send();
   			Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
						"send mail! !");
 		
  		}
   		catch (phpmailerException $e) 
   		{
  			Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
						$mail->ErrorInfo, 
						Log::ERR_ERROR);
    			
   		}
			
	}
	
	public static function checkDNSRR($domain, $recType){
		if(self::isWindows()){
			return self::win_checkdnsrr($domain,$recType);
		}
		else{
			return checkdnsrr($domain,$recType);
		}
	}
	
	private static function win_checkdnsrr($domain, $recType = ''){

    	$result = false;
    	
    	if(!empty($domain)){
    		if($recType == '')
    			$recType = 'MX';
    		exec("nslookup -type=$recType $domain", $output);
    		foreach($output as $line){
    			if(preg_match("/^$domain/", $line)){
    				$result = true;
    			}
    		}
    	}
    	
    	return $result;
    	
    }
    
	private static function isWindows(){
	
		if(stristr(php_uname(), 'Win')){
			return true;
		}
		else{
			return false;
		}
			
	}
	
	public static function getNewLine2(){
	
		if(self::isWindows()){
			return "\r\n\r\n";
		}
		else{
			return "\n\n";
		}
			
	}
	
	public static function getNewLine(){
	
		if(self::isWindows()){
			return "\r\n";
		}
		else{
			return "\n";
		}
			
	}
	
	public static function getDateMonthBefore($month_before){
	    
	    $array = getdate(time());
	    $result->mon = $array['mon'];
	    $result->year = $array['year'];
	    $result->mday = $array['mday'];
	    $mday_max = cal_days_in_month(CAL_GREGORIAN,10,2005);
	    if($result->mday > $mday_max){
	        $result->mady = $mday_max;
	    }
	    if ($result->mon <= $month_before){
	        $result->year = $result->year-1;
	        $result->mon = 12 - ($month_before - $result->mon);
	    }
	    else{
	        $result->mon = $result->mon - $month_before;
	    }
	    return $result;
	}

	public static function getDateWeekBefore($week_before){
	    $array = getdate(time() - $week_before*7*24*60*60);
	    $result->mon = $array['mon'];
	    $result->year = $array['year'];
	    $result->mday = $array['mday'];
	    
	    return $result;
	}
	public static function getWeekWeekBefore($week_before){
	    return date('W',time() - $week_before*7*24*60*60);
	}
	
	public static function getDateDayBefore($day_before){

	    $array = getdate(time() - $day_before*24*60*60);
	    $result->mon = $array['mon'];
	    $result->year = $array['year'];
	    $result->mday = $array['mday'];
	    
	    return $result;
	}
}

?>