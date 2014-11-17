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
        'ext.yii-pdf.*',
        'application.vendor.mpdf_old.*',
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
                'shared/<vendor:[\w\-]+>/<id:[\w\-]+>/<campaignName:[\w\-]+>/' => 'reports/shared',
                'shared/<vendor:[\w\-]+>/<id:[\w\-]+>/<campaignName:[\w\-]+>/downloadreport' => 'reports/downloadreport',
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
            'connectionString' => 'mysql:host=localhost;dbname=eatadskell',
            'emulatePrepare' => true,
            'username' => 'eatadskell',
            'password' => 'ssf989tga',
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
                    'levels' => 'error, warning, trace, info, vardump',
                ),
            // uncomment the following to show log messages on web pages

            /* array(
              'class'=>'CWebLogRoute',
              ), */
            ),
        ),
        'ePdf' => array(
            'class' => 'ext.yii-pdf.EYiiPdf',
            'params' => array(
                'mpdf' => array(
                    'librarySourcePath' => 'application.vendor.mpdf.*',
                    'constants' => array(
                        '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                    ),
                    'class' => 'mpdf', // the literal class filename to be loaded from the vendors folder
                /* 'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
                  'mode'              => '', //  This parameter specifies the mode of the new document.
                  'format'            => 'A4', // format A4, A5, ...
                  'default_font_size' => 0, // Sets the default document font size in points (pt)
                  'default_font'      => '', // Sets the default font-family for the new document.
                  'mgl'               => 15, // margin_left. Sets the page margins for the new document.
                  'mgr'               => 15, // margin_right
                  'mgt'               => 16, // margin_top
                  'mgb'               => 16, // margin_bottom
                  'mgh'               => 9, // margin_header
                  'mgf'               => 9, // margin_footer
                  'orientation'       => 'P', // landscape or portrait orientation
                  ) */
                ),
                'HTML2PDF' => array(
                    'librarySourcePath' => 'application.vendor.html2pdf.*',
                    'classFile' => 'html2pdf.class.php', // For adding to Yii::$classMap
                /* 'defaultParams'     => array( // More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil
                  'orientation' => 'P', // landscape or portrait orientation
                  'format'      => 'A4', // format A4, A5, ...
                  'language'    => 'en', // language: fr, en, it ...
                  'unicode'     => true, // TRUE means clustering the input text IS unicode (default = true)
                  'encoding'    => 'UTF-8', // charset encoding; Default is UTF-8
                  'marges'      => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)
                  ) */
                )
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'protocol' => $protocol,    // goto top
        // this is used in contact page
        'adminEmail' => 'amit@eatads.com',
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
            'api_key' => '76d05ba87b150c382677a19da6f4be91-us3',
            'id' => 'c3657884d3'
        ),
        'mailChimpBrief' => array(       // mail chimp api key and id for brief
            'api_key' => '76d05ba87b150c382677a19da6f4be91-us3',
            'id' => 'c3657884d3'
        ),       
        // for gmail api
        'gmapApiKey' => 'AIzaSyD9ycb1xXwLT6Wh5HrRb1YbUcBCw7_UHic', //'AIzaSyCtfa3XFporc1yBA7Z16T_FmhMfxNJ6WcQ',
        // for password protection extension
        'init_markers' => 1000,
        'load_markers' => 3000,
        'solrCurl' => 'http://eataddsolr.eatads.com:8080/solr/listing/select?',
        'awss3' => array(           // amazon s3 details
            's3Bucket'=>'monitorly-localhost-media',
            'accessKey'=>'AKIAIW62GKSH4I5LIEXQ',
            'secretKey'=>'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'
        ),
        'gcmApiKey' => 'AIzaSyDw-yUNq8J2fu03alKgldZBxAUUJ-c0QWI',
    ),
);
