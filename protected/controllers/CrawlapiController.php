<?php
/**
 * 抓取
 *
 * @auth degang.shen
 */
class CrawlapiController extends WrapperController 
{
    public function actionIndex()
    {
        $type = 'process';
        $this->request['booking'] && $type = 'bookingInfo';
        $controller = Yii::app()->runController('crawl/'.$this->request['wrapperid'].'/'.$type); 
    }

}
