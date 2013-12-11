<?php
/**
 * DatabaseProxy - ApkStore database proxy class
 *     Connect to database, get a PDO instance for query operations
 * NOTE: 
 *     database connection config depend on '../conf/db_conf.xml'
 *
 * @package ApkStore
 * @author wangpeifeng
 */

class DatabaseProxy
{
    /////////////////////////////////////////////////
    // PROPERTIES, PUBLIC
    /////////////////////////////////////////////////
    private $pdo;
    private $connected;
    
    /////////////////////////////////////////////////
    // PROPERTIES, PRIVATE
    /////////////////////////////////////////////////
    
    /////////////////////////////////////////////////
    // PROPERTIES, PROTECTED
    /////////////////////////////////////////////////
    
    
    /////////////////////////////////////////////////
    // CONSTANTS
    /////////////////////////////////////////////////
    
    const DB_NAME                     = "androidaid";
    const CONF_DIR                    = '/conf';
    const CONF_FILE                   = '/db_conf.xml';

    /////////////////////////////////////////////////
    // TABLES, DEVICE_REGISTER
    /////////////////////////////////////////////////
    const DB_TABLE_DEVICE_REGISTER          = 'device_register';
    
    const DB_COLUMN_DEVICE_SERIAL           = 'device_serial';
    const DB_COLUMN_DEVICE_IMEI             = 'device_imei';
    const DB_COLUMN_DEVICE_PROVINCE         = 'device_province';
    const DB_COLUMN_DEVICE_REGIST_STAMP     = 'device_regist_stamp';
    
    

    /////////////////////////////////////////////////
    // METHODS
    /////////////////////////////////////////////////
    /**
     * 
     * Constructure a new DatabaseProxy instance and connect to database 
     */	
    function __construct()
    {
        $this->pdo = null;
        $this->connected = false;
//        $this->connectDatabase();
    }
    
    private function connectDatabase($db_name = self::DB_NAME)
    {
        $result = false;
        
        $xml = simplexml_load_file(dirname(dirname(__FILE__)).self::CONF_DIR.self::CONF_FILE);
        $json = json_encode($xml);
        $obj = json_decode($json);
        
        $dsn = $obj->DB_DRIVER.
            'host='.$obj->DB_HOST.';'.
            'port='.$obj->DB_PORT.';'.
            'dbname='.$db_name;
        $user = $obj->DB_USER;
        $password = $obj->DB_PWD;
        
        Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                "dsn=$dsn, user=$user, pwd=$password" );
        
        try {
            $this->pdo = new PDO($dsn, $user, $password, 
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8';", 
                       PDO::ATTR_PERSISTENT => true)
                );
            if ($this->pdo == NULL){
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__ );
            }
            else{
                $this->connected = true;
                $result = true;
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__ );
            }
        }
        catch (PDOException $e) {
            $this->pdo = null;
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                 print_r($e->getMessage(),TRUE) );
        }
        return $result;
    }
    /**
     * 
     * check the database connection
     * @return boolean
     */
    public function isConnected(){
        return $this->connected;
    }
    /**
     * 
     * Get the PDO object for database query
     * @return PDO
     */
    public function getPDO($db_name = self::DB_NAME)
    {
        if($this->connectDatabase($db_name)){
            return $this->pdo;
        }
        else{
            return FALSE;
        }
    }
    /**
     * get constants of DB_VALUE_ACCOUNT_TYPE_NAME
     * @return Array<string>
     */
    public static function _DB_VALUE_ACCOUNT_ROLE_NAME()
    {
        $account_type_name = array(
            self::DB_VALUE_ROLE_UNKNOWN,
            self::DB_VALUE_ROLE_ROOT,
            self::DB_VALUE_ROLE_ADMIN,
            self::DB_VALUE_ROLE_INFO_ADMIN,
            self::DB_VALUE_ROLE_APP_ADMIN,
            self::DB_VALUE_ROLE_DATA_ADMIN,
            self::DB_VALUE_ROLE_STATISTICS,
            self::DB_VALUE_ROLE_CUSTOMER,
            self::DB_VALUE_ROLE_AUDIT
        );
        return $account_type_name;
    }

    public static function _DB_VALUE_CUSTOMER_TYPE()
    {
        $array = Array(
            self::DB_VALUE_CUSTOMER_TYPE_UNKNOWN,
            self::DB_VALUE_CUSTOMER_TYPE_BRAND,
            self::DB_VALUE_CUSTOMER_TYPE_ODM,
            self::DB_VALUE_CUSTOMER_TYPE_CHANNEL
        );
        return $array;
    }
    
    public static function _DB_VALUE_SUPPLIER_TYPE()
    {
        $array = Array(
            self::DB_VALUE_SUPPLIER_TYPE_UNKNOWN,
            self::DB_VALUE_SUPPLIER_TYPE_ORIGINAL,
            self::DB_VALUE_SUPPLIER_TYPE_AGENT
        );
        return $array;
    }
}	

?>