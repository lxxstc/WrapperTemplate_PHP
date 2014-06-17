<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
$config = array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'qunar Console Application',

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.commands.basic_data.*',
	),

	// preloading 'log' component
	'preload'=>array('log'),

	// application components
	'components'=>array(
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);

$database = include_once dirname(__FILE__).'/db.php';  
(!empty($database)) && $config['components'] = array_merge($config['components'], $database);  
return $config;
