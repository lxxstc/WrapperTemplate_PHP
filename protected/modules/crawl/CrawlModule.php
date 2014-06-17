<?php
class CrawlModule extends CWebModule
{
    public function init()
    {
        $this->setImport(array(
            'crawl.models.*',
            'crawl.models.base.*',
            'crawl.components.*',
        ));
    }

    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action))
        {
            return true;
        }
        else
            return false;
    }
}

