<?php

class ErrorController extends Controller
{
    public $data;

    public function actionIndex()
    {
        $this->data['messages'] = base64_decode(Util::_G('messages'));
        $this->render('index', $this->data); 
    }

}
