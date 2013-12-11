<?php
/**
 * Utilities - ApkStore Utilities class
 *     Provide static functions for general utilities
 * NOTE: 
 *     
 * @package ApkStore
 * @author wangpeifeng
 */

class Utilities
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
    const EMAIL_REGULAR           = '/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/';
    
    /////////////////////////////////////////////////
    // METHODS
    /////////////////////////////////////////////////

    public static function generate_page_links($url, $sort,$order, $cur_page, $num_pages, $page_index_max)
    {
        $page_links = '';
        
        $page_links .= '<table><tr><td>';
        $page_links .= "总共".$num_pages."页：";
        
        if ($cur_page > 1) {
            $page_links .= '<a href="'.$url
                ."?sort=$sort&order=$order"
                ."&page=".($cur_page-1)
                .'"><前一页 </a>';
        }
        else{
            $page_links .= '<前一页 ';
        }
        
        $index_start = max(Array(1, $cur_page - ceil($page_index_max/2)));
        $index_end = min(Array($num_pages, $index_start + $page_index_max-1));

        if ($index_start > 1) {
            $page_links .= '<a href="'.$url
                ."?sort=$sort&order=$order"
                ."&page=1"
                .'"> 1 </a>'.' ... ';
        }
        
        for($i = $index_start; $i <= $index_end; $i++){
            if($cur_page == $i){
                $page_links .= ''.$i;
            }
            else {
                $page_links .= '<a href="'.$url
                    ."?sort=$sort&order=$order"
                    ."&page=$i"
                    .'"> '.$i.' </a>';
            }
        }
        
        if ($index_end < $num_pages) {
            $page_links .= ' ... <a href="'.$url
                ."?sort=$sort&order=$order"
                ."&page=$num_pages"
                .'"> '.$num_pages.' </a>';
        }
        
        if ($cur_page < $num_pages){
            $page_links .= '<a href="'.$url
                ."?sort=$sort&order=$order"
                ."&page=".($cur_page+1)
                .'"> 下一页></a>';
        }
        else {
            $page_links .= ' 下一页>';
        }
        
        $page_links .= '</td><td>';
        
        $page_links .= '<form method="GET" action="'.$_SERVER ['PHP_SELF'].'">';
        $page_links .= '<input id="page" name="page" type="text" value="'.$cur_page.'"></input>';
        $page_links .= '<input id="sort" name="sort" type="hidden" value="'.$sort.'"></input>';
        $page_links .= '<input id="order" name="order" type="hidden" value="'.$order.'"></input>';
        $page_links .= '<input type="submit" name="submit" value="GO!" />';

        $page_links .= '</td></tr></table>';
        
        
        return $page_links;
        
    }
    
    public static function escapString($string)
		{
		    $escape =  preg_replace('/\\/', '\\\\', $string);
		    return  preg_replace("/'/","\\'",$escape);
		}
    
    public static function checkEmail($email)
    {
        if (!preg_match(self::EMAIL_REGULAR, $email)){
            return FALSE;
        }
        else{
            $domain = preg_replace(self::EMAIL_REGULAR, '', $email);
            if(!self::checkDNSRR($domain)){
                return FALSE;
            }
        }
        return TRUE;
    }
    
    /**
     * 
     * Check the availability of $domain 
     * @param string $domain
     * @param string $recType
     * 
     * @return boolean
     */
    public static function checkDNSRR($domain, $recType)
    {
        if(self::isWindows()){
            return self::win_checkdnsrr($domain,$recType);
        }
        else{
            return checkdnsrr($domain,$recType);
        }
    }

    private static function win_checkdnsrr($domain, $recType = '')
    {
        $result = false;
        if(!empty($domain)){
            if($recType == ''){
                $recType = 'MX';
            }
            exec("nslookup -type=$recType $domain", $output);
            foreach($output as $line){
                if(preg_match("/^$domain/", $line)){
                    $result = true;
                }
            }
        }
        return $result;
    }
    
    
    private static function isWindows()
    {
        if (stristr(php_uname(), 'Win')){
            return TRUE;
        }
        return FALSE;
    }
    
    public static function getNewLine2()
    {
        if (self::isWindows()){
            return "\r\n\r\n";
        }
        else{
            return "\n\n";
        }
    }
    
    public static function getNewLine()
    {
        if (self::isWindows()){
            return "\r\n";
        }
        else{
            return "\n";
        }
    }

		public static function convertFileName($file_name)
		{
		    if(self::isWindows()){
		        return mb_convert_encoding($file_name, "gb2312", "utf-8");
		    }
		    else{
		        return $file_name;
		    }
		}
		
		public static function checkUploadError($upload_error)
		{
        switch($upload_error){
        case 0:
            return "文件上传成功!";
        case 1:
            return "文件大小超出服务器空间限制！";
        case 2:
            return "文件大小超出浏览器限制！";
        case 3:
            return "文件仅部分被上传！";
        case 4:
            return "没有找到要上传的文件！";
        case 5:
            return "服务器临时文件丢失！";
        case 6:
            return "写入临时文件夹出错！";
        }
		    
		}
}

?>