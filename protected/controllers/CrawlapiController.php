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
        $type = 'oneway';
        $this->request['retDate'] && $type = 'round';
        $this->request['booking'] && $type = $type.'booking';
        $param = implode("/", $_GET);
        $controller = Yii::app()->runController('crawl/'.$this->request['wrapperid'].'/'.$type); 
    }

}
