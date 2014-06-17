<?php
/**
 * @auth degang.shen
 */
class Util 
{
    //将飞行时长(秒)转换成(小时:分钟)格式
    public static function flightTimeFormat($seconds_count){
        $h = floor($seconds_count/3600);
        $tmp = $seconds_count%3600;
        $minute = intval(floor($tmp/60));
        $minute = ($minute>9)? $minute : '0'.$minute;
        return $h.':'.$minute;
    }
    
    //将格式(小时:分钟)转换成秒
    public static function transHourMinuteToSecond($hm){
        $tmp = explode(':',$hm);
        return (is_array($tmp) && count($tmp)==2)? 3600*intval($tmp[0])+60*intval($tmp[1]) : 0;
    }
    
    //获取两个microtime之间的时间差(单位:毫秒)
    public static function getMicrotimeDiff($start,$end){
        $s_tmp = explode(' ',$start);
        $e_tmp = explode(' ',$end);
        $start_seconds_format = $s_tmp[1]+round($s_tmp[0],3);
        $end_seconds_format = $e_tmp[1]+round($e_tmp[0],3);
        //echo($end_seconds_format.'-'.$start_seconds_format);die();
        return round(($end_seconds_format - $start_seconds_format)*1000);
    }

    /**
     * 获取$_GET值
     *
     * @param string    $param_name 参数名
     * @param integer   $is_filter 是否安全过滤
     * @return string 
     */
    public static function _G($param_name, $is_filter = 0x01)
    {
        $param_value = isset($_GET[$param_name]) ? $_GET[$param_name] : '';
        return self::addslashesFliter($param_value, $is_filter);
    }

    /**
     * 获取$_POST值
     *
     * @param string    $param_name 参数名
     * @param integer   $is_filter 是否安全过滤
     * @return string 
     */
    public static function _P($param_name, $is_filter = 0x01)
    {
        $param_value = isset($_POST[$param_name]) ? $_POST[$param_name] : '';
        return self::addslashesFliter($param_value, $is_filter);
    }

    /**
     * 过滤
     *
     * @param mixed $param 
     * @return mixed $param
     */
    public static function addslashesFliter($param_value, $is_filter)
    {
        if(!is_array($param_value)) 
            return $is_filter ? self::fliter_html(self::safe_replace($param_value)) : $param_value;
        else
        {
            $string = array();
            foreach($param_value as $key => $val) 
                $string[$key] = ($is_filter ? self::fliter_html(self::safe_replace($val)) : $val);
            return $string;
        }
    }

    /**
     * 获取$_COOKIE值
     *
     * @param string    $param_name 参数名
     * @param integer   $is_filter 是否安全过滤
     * @return string 
     */
    public static function _C($param_name, $is_filter = 0x01)
    {
        $param_value = isset($_COOKIE[$param_name]) ? $_COOKIE[$param_name] : '';
        return $is_filter ? self::fliter_html(self::safe_replace($param_value)) : $param_value;
    }
    
    /**
     * 过滤html代码
     *
     * @param string $value
     * @return string 
     */
    public static function fliter_html($value) 
    {
        if (function_exists('htmlspecialchars')) 
            return htmlspecialchars($value);
        return str_replace(array("&", '"', "'", "<", ">"), array("&", "\"", "'", "<", ">"), $value);
    }

    /**
     * 安全过滤函数
     *
     * @param $string 
     * @return string
     */
    public static function safe_replace($string, $is_replace = 0x00) 
    {
        if ( ! get_magic_quotes_gpc())
            return addslashes($string);
        if ($is_replace == 0x01)
        {
            $string = str_replace('%20','',$string);
            $string = str_replace('%27','',$string);
            $string = str_replace('%2527','',$string);
            $string = str_replace('*','',$string);
            $string = str_replace('"','&quot;',$string);
            $string = str_replace("'",'',$string);
            $string = str_replace('"','',$string);
            $string = str_replace(';','',$string);
            $string = str_replace('<','&lt;',$string);
            $string = str_replace('>','&gt;',$string);
            $string = str_replace("{",'',$string);
            $string = str_replace('}','',$string);
            $string = str_replace('\\','',$string);
        }
        return $string;
    }

    /**
     * 加载摸板
     *
     * @param string $view_name 模板名
     * @param array $data 数据
     * @return mixed 
     */
    public static function view($view_name, $data = array())
    {
        $view = Loader::loadView($view_name);  
        if (is_array($data) && count($data) >= 1)
        {
            foreach ($data as $key => $val)
                $view->loadParams($key, $val);
        }
        $view->disPlay();
    }

    /**
     * 提示语
     *
     * @param string $messages 提示语
     * @param integer $type 1:warn 2:busy 3:warn flightno
     * @return mixed
     */
    public static function messages($messages = '', $type = 1)
    {
        $old_message = $messages;
        ($type == 1 && $ico_box = 'ico-box ico-warn') && $messages = '该航线无可查询航班，请您尝试其它航线。';
        ($type == 3 && $ico_box = 'ico-box ico-warn') && $messages = '抱歉，没有找到结果。';
        ($type == 2 && $ico_box = 'ico-box ico-busy') && 
            $messages = '您输入出发城市与到达城市相同，请至少修改其中之一。';
        $messages = $old_message ? $old_message : $messages;
        return '<div class="failure"><p><i class="'.$ico_box.'"></i>'.$messages.
            '</p><i class="split"></i></div>';
    }

    /**
     * 获取配置文件
     *
     * @param mixed $param_name 变量名
     * @param string $file_name 文件名
     * @cache_time 缓存时间
     */
    public static function get_cache_config($param_name, $file_name, $cache_time = 86400)
    {
        $cache_key = md5($file_name.'array');
        $array_list = array();
        if (($array_list = unserialize(RedisCache::getCacheInfo($cache_key))) == null)
        {
            include dirname(dirname(__FILE__)).'/../../config/app-static-config/'.$file_name.'.php';
            $array_list = $$param_name;
            RedisCache::setCacheInfo($cache_key, serialize($array_list), $cache_time);
        }
        return $array_list;
    }
    
    /**
     * 判断是否是ip地址
     * @param str $ip 变量名
     */
    public static function is_ip($ip){
        return preg_match('/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1 -9]?\d))))$/',$ip);
    }
    
    /**
     * 对二维数组按其中的某个键值排序
     * @param str $arr 需要排序的数组
     * @param str $keys 用于排序的键名
     */
    public static function array_sort($arr,$keys,$type='asc'){ 
    	$keysvalue = $new_array = array();
    	foreach ($arr as $k=>$v){
    		$keysvalue[$k] = $v[$keys];
    	}
    	if($type == 'asc'){
    		asort($keysvalue);
    	}else{
    		arsort($keysvalue);
    	}
    	reset($keysvalue);
    	foreach ($keysvalue as $k=>$v){
    		$new_array[$k] = $arr[$k];
    	}
    	return $new_array; 
    }
    
    /**
     * 获取毫秒时间戳
     */
    public static function getMillisecond(){
        list($t1, $t2) = explode(' ', microtime());    
        return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000); 
    }

    /**
     * 获取js版片号
     */
    public static function jsver()
    {
        $ver_file = dirname(dirname(__FILE__)).'/../../.ver';
        if (file_exists($ver_file) && is_readable($ver_file))
            return trim(file_get_contents($ver_file));
        else
            return '2014040418463202';
    }

    /**
     * 禁止一些危险函数
     */
    public static function prohibitedFun( $content, $fun_arr = array() )
    {
        empty($fun_arr) && $fun_arr = array(
            'phpinfo', 'eval', 'passthru', 'exec', 'system', 'chroot', 'scandir', 'chgrp', 'chown',
            'shell_exec', 'proc_open', 'proc_get_status', 'ini_alter', 'ini_alter', 'ini_restore',
            'pfsockopen', 'openlog', 'syslog', 'readlink', 'symlink', 'popepassthru', 
            'stream_socket_server', 'fsocket', 'fsockopen', 'file_get_contents', 'file_put_contents',
            'createCommand', 'bindParam', 'execute', 'find', 'findAll', 'findAllByPk', 
            'findAllByAttributes', 'findAllBySql', 'popen', 'readfile', 'realpath', 'rmdir', 'rename',
            'symlink', 'tempnam', 'tmpfile', 'touch', 'umask', 'unlink', 'fwrite',
        );
    
        $ret = array();
        foreach ($fun_arr as $key => $val)
        {
            $out = '';
            preg_match('/\s*'.$val.'\s*\n*\t*\0*\x0B*(\r\n)*\(/', $content, $out); 
            (!empty($out)) && ($ret[] = $out[0]);
        }
        return $ret;
    }
    
    /**
     * 加密及解密
     *
     * $string： 明文 或 密文  
     * $operation：DECODE表示解密,其它表示加密  
     * $key： 密匙  
     * $expiry：密文有效期  
     * return mixed
     */
    public static function authcode($string, $operation = 'DECODE', $key = 'bbaaadddsqunardei324dss', $expiry = 0) 
    {  
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙  
        $ckey_length = 4;  
          
        // 密匙  
        $key = md5($key ? $key : 'qunar.comabcabc');  
          
        // 密匙a会参与加解密  
        $keya = md5(substr($key, 0, 16));  
        // 密匙b会用来做数据完整性验证  
        $keyb = md5(substr($key, 16, 16));  
        // 密匙c用于变化生成的密文  
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';  
        // 参与运算的密匙  
        $cryptkey = $keya.md5($keya.$keyc);  
        $key_length = strlen($cryptkey);  
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性  
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确  
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;  
        $string_length = strlen($string);  
        $result = '';  
        $box = range(0, 255);  
        $rndkey = array();  
        // 产生密匙簿  
        for($i = 0; $i <= 255; $i++) {  
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);  
        }  
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度  
        for($j = $i = 0; $i < 256; $i++) {  
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;  
            $tmp = $box[$i];  
            $box[$i] = $box[$j];  
            $box[$j] = $tmp;  
        }  
        // 核心加解密部分  
        for($a = $j = $i = 0; $i < $string_length; $i++) {  
            $a = ($a + 1) % 256;  
            $j = ($j + $box[$a]) % 256;  
            $tmp = $box[$a];  
            $box[$a] = $box[$j];  
            $box[$j] = $tmp;  
            // 从密匙簿得出密匙进行异或，再转成字符  
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));  
        }  
        if($operation == 'DECODE') {  
            // substr($result, 0, 10) == 0 验证数据有效性  
            // substr($result, 0, 10) - time() > 0 验证数据有效性  
            // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性  
            // 验证数据有效性，请看未加密明文的格式  
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {  
                return substr($result, 26);  
            } else {  
                return '';  
            }  
        } else {  
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因  
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码  
            return $keyc.str_replace('=', '', base64_encode($result));  
        }  
    }

    public static function getIp()
    {
        if ($_SERVER['REMOTE_ADDR']) {
            $cip = $_SERVER['REMOTE_ADDR'];
        } elseif (getenv("REMOTE_ADDR")) {
            $cip = getenv("REMOTE_ADDR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $cip = getenv("HTTP_CLIENT_IP");
        } else {
            $cip = "unknown";
        }
        return $cip;
    }
}
?>
