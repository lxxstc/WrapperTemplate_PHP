<?php
/**
 * 多库连接
 *
 * @auth degang.shen
 */
class Ar extends CActiveRecord  
{  
    static $database = array();  
    public $dbname  = 'db';  
    public function __construct($scenario='insert', $dbname = '')  
    {  
        if (!empty($dbname))  
            $this->dbname = $dbname;  
              
        parent::__construct($scenario);  
    }  
      
    public function getDbConnection()  
    {  
        $dbname = $this->dbname;  
        if ( isset(self::$database[$dbname]) && self::$database[$dbname] !==null)  
            return self::$database[$dbname];  
        else  
        {  
            if ($this->dbname == 'db')  
                self::$database[$dbname] = Yii::app()->getDb();  
            else   
                self::$database[$dbname] = Yii::app()->$dbname;  
                  
            if(self::$database[$dbname] instanceof CDbConnection)  
            {  
                self::$database[$dbname]->setActive(true);  
                return self::$database[$dbname];  
            }  
            else  
                throw new CDbException(Yii::t('yii','Active Record requires a "db" CDbConnection application component.'));  
        }  
    }  
} 
