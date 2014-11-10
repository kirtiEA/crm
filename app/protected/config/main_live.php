<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

$protocol = 'http://';     // https:// or http://

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Monitorly',
    'defaultController' => 'account/index',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.JOY.*',
        'ext.easyimage.EasyImage',
        'application.extensions.PasswordHash',
        'ext.YiiMailer.EatadsMailer', // Wrapper of YiiMailer
        'application.extensions.GcmPushNotification',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool        
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'monitorly',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => false,//array($_SERVER['REMOTE_ADDR']),
        ),
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'showScriptName'=>false,
            'urlFormat' => 'path',            
            'rules'=>array(
                /*
                'post/<id:\d+>/<title:.*?>'=>'post/view',                
                'posts/<tag:.*?>'=>'post/index',                 
                */
                
                // REST patterns
                array('api/list', 'pattern'=>'api/<model:\w+>', 'verb'=>'GET'),
                array('api/view', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'GET'),
                array('api/update', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
                array('api/delete', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
                array('api/create', 'pattern'=>'api/<model:\w+>', 'verb'=>'POST'),
                
                // Other controllers
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
        ),
        /*
          'db'=>array(
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ),
         */
        // uncomment the following to use a MySQL database
        'db' => array(
          //  kelltoneatads.cggaglcdijh1.ap-southeast-1.rds.amazonaws.com:3306
        //    kelltoneatads.cggaglcdijh1.ap-southeast-1.rds.amazonaws.com
            'connectionString' => 'mysql:host=kelltoneatads.cggaglcdijh1.ap-southeast-1.rds.amazonaws.com;dbname=eatadskell',
            'emulatePrepare' => true,
            'username' => 'eatadskell',
            'password' => 'jsdf98t78DIUISA',
            'charset' => 'utf8',
            // enabled profile params for YII-DEBUG-TOOLBAR
            'enableProfiling' => true,
            'enableParamLogging' => true,
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
                    'levels' => 'error, warning, trace, info, vardump',
                ),
            // uncomment the following to show log messages on web pages

            /* array(
              'class'=>'CWebLogRoute',
              ), */
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'protocol' => $protocol,    // goto top
        // this is used in contact page
        'adminEmail' => 'admin@eatads.com',
        'linkexpiry' => array(
            'signup' => 48,
            'forgot' => 24
        ),
        // for password protection extension
        'phpass' => array(
            'iteration_count_log2' => 8,
            'portable_hashes' => false,
        ),
        'fileUploadPath' => $_SERVER['DOCUMENT_ROOT'] .  '/uploads/',
        'mandrill' => array(
            'api_key' => 'wtWRc4QXlHhoMyK6nzHUqQ'
        ),
        'mailChimp' => array(       // mail chimp api key and id
            'api_key' => '487aee600f274bad7118b9eee06d380e-us4',
            'id' => 'e765513994'
        ),
        'mailChimpBrief' => array(       // mail chimp api key and id for brief
            'api_key' => '487aee600f274bad7118b9eee06d380e-us4',
            'id' => '14343a88f9'
        ),
        // for gmail api
        'gmapApiKey' => 'AIzaSyDPTsHK_Dv2OBv6UaltL_LrBI3875nYqfw',
        // for password protection extension
        'init_markers' => 1000,
        'load_markers' => 3000,
        'solrCurl' => 'http://eataddsolr.eatads.com:8080/solr/listing/select?',
        'awss3' => array(           // amazon s3 details
            's3Bucket'=>'monitorly-prod-media',
            'accessKey'=>'AKIAIW62GKSH4I5LIEXQ',
            'secretKey'=>'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'
        ),
        'gcmApiKey' => 'AIzaSyBYNN6H2ZsKL0myBHdRtV3_wRkQ_8-3QGc',
    ),
);
