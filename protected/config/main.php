<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'theme' => 'booster',
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Tenis++',
    // preloading 'log' component
    'preload' => array(
        'log',
        'booster',
    ),
    //set the language to PT
    'language' => 'pt',
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'ext.YiiMailer.YiiMailer',
        'ext.EFullCalendar.EFullCalendar',
    ),
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'password',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
            'generatorPaths' => array(
                'booster.gii'
            ),
        ),
    ),
    // application components
    'components' => array(
        'booster' => array(
            'class' => 'ext.yiibooster.components.Booster',
        ),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        /*
          'db'=>array(
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ),
         */
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=mytennisapp_mtest',
            //'emulatePrepare' => true,
            'username' => 'myTennisUser',
            'password' => 'Tenis123',
            'charset' => 'utf8',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
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
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'admin@tenismaismais.com',
        'playerLevelURL' => 'http://www.tennisplayandstay.com/media/131801/131801.pdf',
        'loggedInUser' => null,
    ),
);
