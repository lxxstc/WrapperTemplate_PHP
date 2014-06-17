<?php
class TestCommand extends CConsoleCommand
{
    /**
    public function run($args)
    {
        print_r($args);die;
    }
    **/

    public function actionT()
    {
    
        GenerationCitys::generation();
        echo 'test'; 
    }
}
