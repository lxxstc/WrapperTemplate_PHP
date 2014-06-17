<?php
class QLogFilter extends CLogFilter
{

    public function filter(&$logs)
    {
        if (!empty($logs))
        {
            $this->format($logs);
        }
        return $logs;
    } 

    protected function format(&$logs)
    {
        foreach($logs as &$log)
        {
            $mes = explode("\nin", $log[0]);
            (strtolower($log[1]) == 'info') && $log[0] = $mes[0];
        }
    }

    public function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else if(!empty($_SERVER['REMOTE_ADDR']))
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }else{
            $ip='0.0.0.0';
        }
        return $ip;
    }
}
