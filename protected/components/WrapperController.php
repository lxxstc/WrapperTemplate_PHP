<?php
/**
 * @auth degang.shen
 */
class WrapperController extends CController
{
    public $layout='column1';

    public $menu=array();

    public $breadcrumbs=array();
    
    public $keywords, $description;

    public $data = array();

    public $is_ssl = 0x00;

    public $is_proxy = 0x00;

    public $header = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HEADER => true,
        CURLOPT_VERBOSE => true,
        CURLOPT_AUTOREFERER => true,
        CURLOPT_CONNECTTIMEOUT => 60,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'
    );

    public $save_header = array();

    public $curl = null;

    public $cur_url = null;

    public $log_info = array('id' => null, 'url' => null, 'dep' => null, 
        'arr' => null, 'dept' => null, 'method' => null, 'status' => null);

    public $coding = 'utf-8';
    
    public $start_time = '';

    public $request = array();

    public $flight_res = array('data' => array(), 'ret' => true, 'status' => 'NO_RESULT');

    public function init()
    {
        parent::init();

        //请求参数
        $this->request = array(
            'wrapperid' => Util::_G('wrapperid'),
            'retDate' => Util::_G('retDate'),
            'dep' => Util::_G('dep'),
            'arr' => Util::_G('arr'),
            'depDate' => Util::_G('depDate'),
            'token' => Util::_G('token'),
            'booking' => Util::_G('booking'),
            'timeout' => Util::_G('timeout')
        );

        $this->request['timeout'] && $this->header[CURLOPT_TIMEOUT] = $this->request['timeout'];

        //log
        $this->log_info['id'] = $this->request['wrapperid'];
        $this->log_info['dep'] = $this->request['dep'];
        $this->log_info['arr'] = $this->request['arr'];
        $this->log_info['dept'] = $this->request['depDate'];

        WLog::$log_info = $this->log_info; 
        $this->start_time = microtime(true);
    }

    public function curl()
    {
        $curl_options = array();
        $cookie = tempnam('.', '~');
        $headers = array(
            'Accept-Language' => 'zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3',
            'User-Agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9) Gecko/2008052906 Firefox/3.0',
            'Referer' => $this->cur_url
        );
        $this->header[CURLOPT_COOKIESESSION] = true;
        $this->header[CURLOPT_COOKIEJAR] = $cookie;
        $this->header[CURLOPT_HTTPHEADER] = $headers;
        $this->header[CURLOPT_COOKIEFILE] = $cookie;
        //$this->header[CURLOPT_COOKIEJAR] = $cookie;
        ($this->is_ssl == 0x01) && $this->header[CURLOPT_SSL_VERIFYPEER] = true; 
        if ($this->is_proxy == 0x01)
        {
            //test
            $this->header[CURLOPT_PROXY] = 'http://1.179.147.2:8080';
        }
        $this->curl = Yii::app()->curl->setOptions($this->header);
        $this->header = array();
        return $this->curl;
    }

    public function toUtf8($content, $header)
    {
        if (isset($header['Content-Type']) && $header['Content-Type'])
        {
            $char = explode('=', $header['Content-Type']);
            if (isset($char[1]) && $char[1])
                $content = mb_convert_encoding($content, 'UTF-8', $char[1]); 
        }
        else 
            $content = mb_convert_encoding($content, 'UTF-8', $header); 
        return $content;
    }

    public function toJson($data)
    {
        $total = microtime(true)-$this->start_time;
        $str_total = var_export($total, TRUE);  
        if(substr_count($str_total,"E"))
        {  
            $float_total = floatval(substr($str_total,5));  
            $total = $float_total/100000;  
        }  

        WLog::$log_info['status'] = $data['status'];
        WLog::$log_info['span'] = substr($total, 0, 5);
        WLog::writeLog('info', '', 'wrapper');
        exit(CJSON::encode($data));
    }

    //单程
    public function actionOneway(){}

    //单程booking
    public function actionOnewaybooking(){}

    //往返
    public function actionRound(){}

    //往返booking
    public function actionRoundbooking(){}
}
