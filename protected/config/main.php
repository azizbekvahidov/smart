<?php
Yii::setPathOfAlias('chartjs', dirname(__FILE__).'/../extensions/yii-chartjs');
Yii::setPathOfAlias('highchartjs', dirname(__FILE__).'/../extensions/yii-highcharts-5.0.2');
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Artel',
    'language' => 'ru',

	// preloading 'log' component
	'preload'=>array('log','chartjs','highchartjs','highstockjs'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'ext.eauth.*',
        'ext.eauth.services.*',
        'ext.PHPExcel.*',
        'ext.compress-image.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'pass',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),


		
	),
    'controllerMap' => array(
        // ...
        'barcodegenerator' => array(
            'class' => 'ext.barcodegenerator.BarcodeGeneratorController',
        ),
        'barcodegenerator' => array(
            'class' => 'ext.barcodegenerator.BarcodeGeneratorController',
        ),
    ),

	// application components
	'components'=>array(
		'user'=>array(
            'class'=>'WebUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
        'chartjs' => array('class' => 'chartjs.components.ChartJs'),
        'highchartjs' => array('class' => 'highchartjs.highcharts.HighchartsWidget'),
		'highstockjs' => array('class' => 'highchartjs.highcharts.HighstockWidget'),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
            'showScriptName' => false,
            
		),
		
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		// uncomment the following to use a MySQL database
		*/
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=artel',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '198923233171',
			'charset' => 'utf8',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
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
		'adminEmail'=>'webmaster@example.com',
	),
);