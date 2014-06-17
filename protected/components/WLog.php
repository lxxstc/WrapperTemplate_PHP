<?php
class WLog 
{
    public static $log_path = '';  
    public static $_levels  = array('ERROR' => 'crawlerException', 'DEBUG' => '2', 'HTML' => 'crawlerHtml', 
        'INFO' => 'crawlerlog', 'ALL' => '4');  
    public static $log_info = array();
    public static $e;
  
    public static function setLogPath($path) 
    {
        if ($path)
        {
            $log_path = Yii::app()->basePath.'runtime/'.$path;
            if (!is_dir($log_path))
            {
                mkdir($log_path, 02777); 
                chmod($log_path, 02777);
            }
            (is_writable($log_path)) && chmod($log_path, 02777);
            self::$log_path = $log_path;
            return true;
        }
        return false;
    }
        
    public static function writeLog($level = 'error', $msg, $category = 'application', $php_error = FALSE)  
    {         
                 
        $base = Yii::app()->basePath;
        $level = strtoupper($level);  
          
        if ( ! isset(self::$_levels[$level]))  
        {  
            return FALSE;  
        }  
          
        self::$log_path = $base.DIRECTORY_SEPARATOR.'runtime'.DIRECTORY_SEPARATOR.$category.'_log'.
            DIRECTORY_SEPARATOR. date('Y-m-d', time()).DIRECTORY_SEPARATOR;
        $filepath = self::$log_path.self::$_levels[$level].'.'.date('Y-m-d-H', time()).'.log';  
          
        (!is_dir(self::$log_path)) && @mkdir(self::$log_path, 0755, true);
        (!is_writable($filepath)) && @chmod($filepath, 0755);       

        $message = self::message($level, $msg);
        @chmod($filepath, 0755);   
        file_put_contents($filepath, $message, FILE_APPEND);
          
        @chmod($filepath, 0755);   
                  
        return TRUE;  
    }  

    public static function message($level, $e)
    {
        $message = '';
        if (isset(self::$log_info['id']))
        {
            $key = 'codebase='.self::$log_info['id'].'&dep='.self::$log_info['dep'].'&arr='.self::$log_info['arr'].'&dept='.self::$log_info['dept'];
            $message = date('Y-m-d H:i:s', time())." {$level} caodebase: ".self::$log_info['id'].", Key:".$key;
        }
        if ($level == 'INFO')
        {
            if (!isset(self::$log_info['span']))
                $message .= " URL: ".self::$log_info['url'].", Method: ".self::$log_info['method'].", Status: ".self::$log_info['status']."\n";
            else
            {
                $message .= " Status: ".self::$log_info['status']." Span: ".self::$log_info['span']."\n";
            }
        }
        if ($level == 'ERROR')
        {
            $message .= "\n".get_class($e).': '.$e->getMessage().' ('.$e->getFile().':'.$e->getLine().")\n";
            $message .= $e->getTraceAsString()."\n";
        }
        if ($level == 'HTML')
            $message .= "\n".$e."\n";
        return $message;
    }


}
