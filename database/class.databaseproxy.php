<?php
/**
 * DatabaseProxy - ApkStore database proxy class
 *     Connect to database, get a PDO instance for query operations
 * NOTE: 
 * Dependencies:
 *     '../utilities/class.log.php' 
 *
 * @package ApkStore
 * @author wangpeifeng
 */

require_once dirname(dirname(__FILE__)).'/utilities/class.log.php';

class DatabaseProxy{
	// constants 
	
	// DB connection
	const DB_DRIVER = "mysql:";
	const DB_HOST = "localhost";
	const DB_PORT = "3306";
	const DB_USER = "root";
	const DB_PWD = "namo2010";
	const DB_NAME = "namo";
	
	
	const STRING_TYPE = 'type';
	const STRING_EMAIL = 'email';
	const STRING_REGISTER_DATE = 'register_date';
	const STRING_UPDATE_TIME = 'update_time';
	
	
/**
 * 
 * table name of agent infomation list
 * @var String
 */
	const DB_TABLE_AGENT_LIST = 'agent_list';

	const DB_COLUMN_AGENT_SERIAL = 'agent_serial';
	const DB_COLUMN_AGENT_NAME = 'agent_name';
	const DB_COLUMN_AGENT_CONTACT = 'agent_contact';
	const DB_COLUMN_CONTACT_EMAIL = 'contact_email';
	const DB_COLUMN_CONTACT_PHONE = 'contact_phone';
	const DB_COLUMN_AUDIT_URL = 'audit_url';
	const DB_COLUMN_AUDIT_ACCOUNT = 'audit_account';
	const DB_COLUMN_AUDIT_PWD = 'audit_pwd';
	const DB_COLUMN_AGENT_REGISTER_DATE = self::STRING_REGISTER_DATE;
	const DB_COLUMN_AGENT_UPDATE_TIME = self::STRING_UPDATE_TIME;
	
	
	const DB_TABLE_ROLES = 'roles';
	
	const DB_COLUMN_ROLES_ID = 'role_id';
	const DB_COLUMN_ROLE_NAME = 'role_name';
	const DB_COLUMN_ROLE_UPDATE_TIME = self::STRING_UPDATE_TIME;
	
	const DB_VALUE_ROLE_ROOT = '根用户';
	const DB_VALUE_ROLE_ADMIN = '管理员';
	const DB_VALUE_ROLE_APP_ADMIN = '应用管理员';
	const DB_VALUE_ROLE_STATISTICS_ADMIN = '统计管理员';
	const DB_VALUE_ROLE_STATISTICS = '统计员';
	const DB_VALUE_ROLE_CUSTOMER = '客户';
	
/**
 * database table for account management
 */
	const DB_TABLE_ACCOUNTS = 'accounts';
	
	const DB_COLUMN_ACCOUNT_SERIAL = 'account_serial';
	const DB_COLUMN_ACCOUNT_ROLE_ID = self::DB_COLUMN_ROLES_ID;
	const DB_COLUMN_ACCOUNT = 'account';
	const DB_COLUMN_ACCOUNT_PASSWORD_SHA = 'password_sha';
	const DB_COLUMN_ACCOUNT_EMAIL = self::STRING_EMAIL;
	const DB_COLUMN_ACCOUNT_REGISTER_DATE = self::STRING_REGISTER_DATE;
	const DB_COLUMN_ACCOUNT_UPDATE_TIME = self::STRING_UPDATE_TIME;
	
	const DB_VALUE_ACCOUNT_TYPE_UNKOWN_STR = "未知类型";
	const DB_VALUE_ACCOUNT_TYPE_UNKOWN_INT = 0;
	
	const DB_VALUE_ACCOUNT_TYPE_ROOT_STR = "超级管理员";
	const DB_VALUE_ACCOUNT_TYPE_ROOT_INT = 1;
	
	const DB_VALUE_ACCOUNT_TYPE_ADMIN_STR = "客户信息管理员";
	const DB_VALUE_ACCOUNT_TYPE_ADMIN_INT = 2;
	
	const DB_VALUE_ACCOUNT_TYPE_APP_ADMIN_STR = "软件包管理员";
	const DB_VALUE_ACCOUNT_TYPE_APP_ADMIN_INT = 3;
	
	const DB_VALUE_ACCOUNT_TYPE_STATISTICS_ADMIN_STR = "统计信息管理员";
	const DB_VALUE_ACCOUNT_TYPE_STATISTICS_ADMIN_INT = 4;
	
	const DB_VALUE_ACCOUNT_TYPE_STATISTICS_STR = "信息统计员";
	const DB_VALUE_ACCOUNT_TYPE_STATISTICS_INT = 5;
	
	const DB_VALUE_ACCOUNT_TYPE_CUSTOMER_STR = "客户查询帐号";
	const DB_VALUE_ACCOUNT_TYPE_CUSTOMER_INT = 6;
	
	const DB_VALUE_ACCOUNT_TYPE_INT_MAX = self::DB_VALUE_ACCOUNT_TYPE_CUSTOMER_INT;
	
	//const DB_VALUE_ACCOUNT_TYPE[] = {}; 
	
	
	
	// private variables
	private $pdo;
	private $connected;
	// public functions
	
	function __construct(){
		
		$this->pdo = null;
		
		$this->connected = false;

//		$this->connectDatabase();
  		
	  Log::i('function '.__FUNCTION__.'()'.' class::'.__CLASS__.' Line:'.__LINE__.' at '.__FILE__ );
		
	}
	
	function __destruct(){
		
		$this->connected = false;
		
		Log::i('function '.__FUNCTION__.'()'.' class::'.__CLASS__.' Line:'.__LINE__.' at '.__FILE__ );
				
	}
	
/**
 * 
 * connect to database 
 * 
 * @return true/flase
 */
	
	public function connectDatabase($db_name = self::DB_NAME){
		
		$result = false;
		
		$dsn = self::DB_DRIVER.
				'host='.self::DB_HOST.';'.
				'port='.self::DB_PORT.';'.
				'dbname='.$db_name;
		
		$user = self::DB_USER;
		$password = self::DB_PWD;

		try {
    		$this->pdo = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8';", PDO::ATTR_PERSISTENT => true));
    		
    		$this->connected = true;
    		$result = true;
    		
    	} catch (PDOException $e) {
    		
			    $this->pdo = null;
		
    		    Log::i('function '.__FUNCTION__.'()'.' class::'.__CLASS__.' Line:'.__LINE__.' at '.__FILE__ ,
    				"connect to database failed!");
    		
		}
		
		return $result;
	}
	
	
	public function isConnected(){
		
		return $this->connected;
	
	}
	
	public function getPDO(){
		
		return $this->pdo;
		
	}
	

	
}

?>