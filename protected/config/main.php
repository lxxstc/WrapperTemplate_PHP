<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
$config = array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'机票监控后台',

    'defaultController' => 'default',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
        'crawl'
	),

	// application components
	'components'=>array(
        'curl' => array(
            'class' => 'Curl',
        ),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,  
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
        /**
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
        **/
        //test 
        'session'=>array(
            'autoStart'=>false,
            'sessionName'=>'qunar',
            'cookieMode'=>'only',
            'savePath'=>'/tmp/',
        ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'error/index',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'error/index',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
                array(  
                    'class' => 'CFileLogRoute',  
                    'levels' => 'error, warning, info',  
                    'categories'=> 'wrapper.*',  
                    'logFile'=> 'wrapper.log',  
                    "filter"=> array(
                        'class' => "QLogFilter",
                        'prefixSession' => false,
                        'prefixUser' => true,
                        'logUser' => true,
                    ),
                ), 
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'degang.shen@qunar.com',
	),
);

$database = include_once dirname(__FILE__).'/db.php';  
(!empty($database)) && $config['components'] = array_merge($config['components'], $database);  
return $config;
