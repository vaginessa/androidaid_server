<?php

/**
 * DeviceManager - ApkStore brand information management class
 * NOTE: 
 * Dependencies:
 *     'class.log.php' 
 *     '../proxy/class.databaseproxy.php'
 *
 * @package ApkStore
 * @author wangpeifeng
 */
require_once 'class.log.php'; 
require_once '../proxy/class.databaseproxy.php';

class DevicedManager
{

    /////////////////////////////////////////////////
    // PROPERTIES, PUBLIC
    /////////////////////////////////////////////////


    /////////////////////////////////////////////////
    // PROPERTIES, PRIVATE
    /////////////////////////////////////////////////

    private $db                     = null;
    private $pdo                    = null;
    
    private $db_name                = DatabaseProxy::DB_NAME;
    private $table                  = DatabaseProxy::DB_TABLE_DEVICE_REGISTER;
    
    private $col_serial             = DatabaseProxy::DB_COLUMN_DEVICE_SERIAL;
    private $col_imei               = DatabaseProxy::DB_COLUMN_DEVICE_IMEI;
    private $col_province           = DatabaseProxy::DB_COLUMN_DEVICE_PROVINCE;
    private $col_register_date      = DatabaseProxy::DB_COLUMN_DEVICE_REGIST_STAMP;

    /////////////////////////////////////////////////
    // PROPERTIES, PROTECTED
    /////////////////////////////////////////////////

    
    /////////////////////////////////////////////////
    // CONSTANTS
    /////////////////////////////////////////////////

    const ERR_CODE                = 'err_code';
    const ERR_NONE                = 0;
    const ERR_DATABASE            = 1;
    const ERR_BRAND               = 2;
    
    const STR_DB_CONN_SUCCESS        = 'DB Connect Success!';
    const STR_DB_CONN_FAILED         = 'DB Connect Failed!';
    const STR_DB_QUERY_FAILED        = 'Database Query Failed!';
    const STR_ACCOUNT_ERR            = 'Account Not Available!';
		
    /////////////////////////////////////////////////
    // METHODS, VARIABLES
    /////////////////////////////////////////////////
	
/**
 * 
 * create a new instance, and connect to the database 
 */	
    function __construct()
    {
        $this->db = null;
        $this->pdo = null;
        $this->db = new DatabaseProxy();
        $this->pdo = $this->db->getPDO();
        if($this->pdo){
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__ ,
                self::STR_DB_CONN_SUCCESS );
        }
        else{
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__ ,
                self::STR_DB_CONN_FAILED,
                Log::ERR_ERROR);
        }
    }

    function __destruct()
    {
        if ($this->db != null){
            $this->db = null;
            $this->pdo = null;
        }
    }
    
    public function getTotal()
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_BRANDS;
            $column_brand = DatabaseProxy::DB_COLUMN_BRAND;
            $sql = "SELECT $column_brand"
                    ." FROM $table";
            $query = $this->pdo->query($sql);
            if($query){
                $array = $query->fetchAll();
                return count($array);
            }
        }
        return 0;
    }
    
    public function getBrands()
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_BRANDS;
            $column_brand_serial = DatabaseProxy::DB_COLUMN_BRAND_SERIAL;
            $column_brand = DatabaseProxy::DB_COLUMN_BRAND;
            
            $sql = "SELECT $column_brand, $column_brand_serial "
                    ." FROM $table ORDER BY $column_brand";
            $query = $this->pdo->query($sql);
            if($query){
                $i=0;
                foreach ($query as $row){
                    $array_brands[$i]["$column_brand"] = $row["$column_brand"];
                    $array_brands[$i]["$column_brand_serial"] = $row["$column_brand_serial"];
                    $i++;
                }
                return $array_brands;
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $log_msg,
                    Log::ERR_ERROR);
                
            }
        }
        return FALSE;
        
    }
    
    public function queryDeviceByImei($imei)
    {
        if($this->pdo != null){
            $sql = "SELECT * "
                    ." FROM $this->db_name.$this->table"
                    ." WHERE $this->col_imei = $imei";
            $query = $this->pdo->query($sql);
            if($query){
                $i=0;
                foreach ($query as $row){
                    $array[$i]["$this->col_province"] = $row["$this->col_province"];
                    $array[$i]["$this->col_register_date"] = $row["$this->col_register_date"];
                    $i++;
                }
                return $array;
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $log_msg,
                    Log::ERR_ERROR);
                
            }
        }
        return FALSE;
        
    }
    
    public function fetchBrands($sort, $cur_page, $limit, $order = 0)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_BRANDS;
            $column_brand = DatabaseProxy::DB_COLUMN_BRAND;
            $column_notes = DatabaseProxy::DB_COLUMN_BRAND_NOTES;
            $column_register_date = DatabaseProxy::DB_COLUMN_BRAND_REGISTER_DATE;
            $skip = ($cur_page - 1) * $limit;
            if($order == 0){
                $order_type = "ASC";
            }
            else{
                $order_type = "DESC";
            }
            
            $sql = "SELECT * "
                    ." FROM $table ORDER BY $sort $order_type LIMIT $skip,$limit";
            $query = $this->pdo->query($sql);
            if($query){
                $i=0;
                foreach ($query as $row){
                    $array_brands[$i]["$column_brand"] = $row["$column_brand"];
                    $array_brands[$i]["$column_notes"] = $row["$column_notes"];
                    $array_brands[$i]["$column_register_date"] = $row["$column_register_date"];
                    $i++;
                }
                return $array_brands;
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $log_msg,
                    Log::ERR_ERROR);
                
            }
        }
        return FALSE;
        
    }

    public function checkBrand($brand)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_BRANDS;
            $column_brand = DatabaseProxy::DB_COLUMN_BRAND;
            $sql = "SELECT $column_brand FROM $table WHERE $column_brand = '$brand' LIMIT 1";
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($row["$column_brand"] == $brand){
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
    
    public function addBrand($brand, $notes)
    {
        if ($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_BRANDS;
            $column_brand = DatabaseProxy::DB_COLUMN_BRAND;
            $column_notes = DatabaseProxy::DB_COLUMN_BRAND_NOTES;
            $column_register_date = DatabaseProxy::DB_COLUMN_BRAND_REGISTER_DATE;
            $column_update_time = DatabaseProxy::DB_COLUMN_BRAND_UPDATE_TIME;
            $register_date = date('Y-m-d');
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "INSERT INTO $table ( $column_brand, $column_notes, $column_register_date, $column_update_time )".
                            " VALUES ( '$brand', '$notes', '$register_date','$update_time')";
            $query = $this->pdo->query($sql);
            if($query){
                return TRUE;
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $log_msg,
                    Log::ERR_ERROR);
            }
                
        }
        return FALSE;    
    }

    public function updateBrand($brand, $notes)
    {
        if ($this->pdo != null){
//            if (!$this->checkCustomer($customer)){
                $table = DatabaseProxy::DB_TABLE_BRANDS;
                $column_brand = DatabaseProxy::DB_COLUMN_BRAND;
                $column_notes = DatabaseProxy::DB_COLUMN_BRAND_NOTES;
                $column_update_time = DatabaseProxy::DB_COLUMN_BRAND_UPDATE_TIME;
                $update_time = date('Y-m-d H:i:s');
                
                $sql = "UPDATE $table SET "
                        ."$column_notes = '$notes'"
                        .", $column_update_time = '$update_time'"
                        ."WHERE $column_brand = '$brand'";
                $query = $this->pdo->query($sql);
                if($query){
                    return TRUE;
                }
                else{
                    $log_msg = print_r($this->pdo->errorInfo(),true);
                    Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                        $log_msg,
                        Log::ERR_ERROR);
                }
                
//            }
        }
        return FALSE;    
    }
    
    
    public function removeBrand($brand)
    {
        if ($this->pdo != null){
                $table = DatabaseProxy::DB_TABLE_BRANDS;
                $column_brand = DatabaseProxy::DB_COLUMN_BRAND;
                
                $sql = "DELETE FROM $table" 
                        ." WHERE $column_brand = '$brand'";
                $query = $this->pdo->query($sql);
                if($query){
                    return TRUE;
                }
                else{
                    $log_msg = print_r($this->pdo->errorInfo(),true);
                    Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                        $log_msg,
                        Log::ERR_ERROR);
                }
                
//            }
        }
        return FALSE;    
    }
    
    
    public function fetchStatisticsMon($month_before, $sale_start)
    {
        Log::i($sale_start);
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_DEVICE_REGISTER;
            $column_register_date = DatabaseProxy::DB_COLUMN_DEVICE_REGIST_STAMP;
            
            $sql = "SELECT $column_register_date "
                    ." FROM $table "
                    ." WHERE PERIOD_DIFF( date_format( now( ) , '%Y%m' ) , date_format( $column_register_date, '%Y%m' ) ) = $month_before"
                    ." AND $column_register_date > '$sale_start' ";
            
            Log::i($sql);
            $query = $this->pdo->query($sql);
            if($query){
                $result = $query->fetchAll();
                return count($result);
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $log_msg,
                    Log::ERR_ERROR);
                
            }
        }
        return 0;
        
    }
    
    public function fetchStatisticsWeek($week_before, $sale_start)
    {
        $date = getdate();
        $wday = $date['wday'];
        if ($week_before > 0){
            $date_max = $week_before*7 + $wday;
            $date_min = ($week_before - 1)*7 + $wday+1;
        }
        else{
            $date_min = 0;
            $date_max = $wday;
        }
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_DEVICE_REGISTER;
            $column_register_date = DatabaseProxy::DB_COLUMN_DEVICE_REGIST_STAMP;
            
            $sql = "SELECT $column_register_date "
                    ." FROM $table "
                    ." WHERE TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) >= $date_min AND TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) <= $date_max"
                    ." AND $column_register_date > '$sale_start'";
            Log::i($sql);
            $query = $this->pdo->query($sql);
            if($query){
                $result = $query->fetchAll();
                return count($result);
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $log_msg,
                    Log::ERR_ERROR);
                
            }
        }
        return 0;
        
    }
    
    public function fetchStatisticsDay($day_before,$sale_start)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_DEVICE_REGISTER;
            $column_register_date = DatabaseProxy::DB_COLUMN_DEVICE_REGIST_STAMP;
            
            $sql = "SELECT $column_register_date "
                    ." FROM $table "
                    ." WHERE TO_DAYS(NOW())-TO_DAYS(device_regist_stamp) = $day_before "
                    ." AND $column_register_date > '$sale_start'";
            $query = $this->pdo->query($sql);
            if($query){
                $result = $query->fetchAll();
                return count($result);
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $log_msg,
                    Log::ERR_ERROR);
                
            }
        }
        return 0;
        
    }    
    
    
    public function fetchStatisticsProvince($sale_start, $province = "")
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_DEVICE_REGISTER;
            $column_province = DatabaseProxy::DB_COLUMN_DEVICE_PROVINCE;
            $column_register_date = DatabaseProxy::DB_COLUMN_DEVICE_REGIST_STAMP;
            
            $sql = "SELECT $column_province "
                    ." FROM $table "
                    ." WHERE "
                    ." $column_province = '$province' ";
            if ($province == "" or !isset($province)){
                $sql .= " OR $column_province = 'δ֪' ";
                $sql .= " OR $column_province = NULL ";
                $province_name = 'δ֪';
                
            }
            else{
                $province_name = $province;
            }
            
            $query = $this->pdo->query($sql);
            if($query){
                $result = $query->fetchAll();
                $i=0;
                
                $array['province'] = $province_name;
                $array['num'] = count($result);
                return $array;
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $log_msg,
                    Log::ERR_ERROR);
                
            }
        }
        return FALSE;
        
    }
    
}
?>