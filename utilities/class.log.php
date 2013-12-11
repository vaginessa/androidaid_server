<?php

/**
 * Log - ApkStore log infomation class
 * NOTE: 
 * Dependencies:
 *     '../utilities/Utilities.php'
 *
 * @package ApkStore
 * @author wangpeifeng
 */

class Log
{
    /////////////////////////////////////////////////
    // PROPERTIES, PUBLIC
    /////////////////////////////////////////////////


    /////////////////////////////////////////////////
    // PROPERTIES, PRIVATE
    /////////////////////////////////////////////////

    
    /////////////////////////////////////////////////
    // PROPERTIES, PROTECTED
    /////////////////////////////////////////////////

    
    /////////////////////////////////////////////////
    // CONSTANTS
    /////////////////////////////////////////////////
    
    const FILE                   = "logs.txt";
    
    const LEVEL_INFO             = 0;
    const LEVEL_WARNING          = 1;
    const LEVEL_ERROR            = 2;
    
    const LEVEL_SETTING          = self::LEVEL_INFO;

    const ERR_LOG                = "LOG";
    const ERR_WARNING            = "WARNING";
    const ERR_ERROR              = "ERROR";
    
    const DEFAULT_MESSAGE        = "STEP HERE!";
    
    /////////////////////////////////////////////////
    // METHODS
    /////////////////////////////////////////////////

    /**
     * Write log infomation to log file according to the LEVEL_SETTING 
     * 
     * @param string $TAG
     * @param string $msg
     * @param string $error
     * 
     * @access public
     */
    public static function i($TAG, $msg = self::DEFAULT_MESSAGE, $error = self::ERR_LOG)
    {
        $flag = false;
        switch (self::LEVEL_SETTING){
            case self::LEVEL_INFO:
                if($error == self::ERR_LOG || $error == null){
                    $flag = true;
                }
                //break;
            case self::LEVEL_WARNING:
                if($error == self::ERR_WARNING ){
                    $flag = true;
                }
                //break;
            case self::LEVEL_ERROR:
                if($error == self::ERR_ERROR ){
                    $flag = true;
                }
                break;
        }

        if($flag){
            self::write_to_log($TAG, $msg, $error);
        }
    }

    /**
     * write log infomation to log file
     * 
     * @param string $TAG
     * @param string $msg
     * @param string $error
     * 
     * @access private
     */
    private static function write_to_log($TAG, $msg = self::DEFAULT_MESSAGE, $error = self::ERR_LOG)
    {
        $fd = @fopen(dirname(__FILE__).'/'.self::FILE, "a");
        if($fd) {
            $stamp = date('Y-m-d H:i:s');
            $script = $_SERVER['PHP_SELF'];
            $newline2 = self::getNewLine2();
            $newline = self::getNewLine();

            fputs($fd, "$stamp  $error  Message: $newline    '$msg' $newline    $TAG  Script:$script $newline2");

            fclose($fd);
        }

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

}

?>