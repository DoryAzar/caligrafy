<?php 

/**
 * defaultsettings.php is used for the default configurations needed for the framework
 * @author Dory A.Azar
 * @copyright 2019
 * @version 1.0
 *
*/


define('ROUTE_ARG_PATTERN', '#\{+(.*?)\}+#');
define('ROUTE_PARAM_PATTERN', '/([^?&=#]+)=([^&#]*)/');

session('scripts', array(  'favicon' => $_SESSION['imagesUrl'].getenv('FAVICON'),
                   'css' => $_SESSION['public'].'css/styles.css',
                   'script' => $_SESSION['public'].'js/script.js',
                   'bootstrap_css' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
                   'bootstrap_script' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
                   'bootstrap_jquery' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js',
                   'fallback_html5shiv' => 'https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js',
                   'fallback_respond' => 'https://oss.maxcdn.com/respond/1.4.2/respond.min.js',
                   'lightbox_script' => "<script src='../js/lightbox/lightbox.js'></script><script>lightbox.option({'alwaysShowNavOnTouchDevices': true,'disableScrolling': true})</script>",
                   'download_script' => "<script src='../js/addtohome/src/addtohomescreen.js'></script><script>var ath = addToHomescreen({skipFirstVisit: false, startDelay: 0, lifespan: 0, displayPace: 0, maxDisplayCount: 0 });</script>",
                   'wysiwyg_css' => 'https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css',
                   'wysiwyg_js' => 'https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js',
                   'social_buttons_css' => $_SESSION['public'].'css/social_buttons.css'
                 ));


//Initialize phone icons
session('icons', "<link rel='apple-touch-icon' sizes='57x57' href='' />
<link rel='apple-touch-icon' sizes='72x72' href='' />
<link rel='apple-touch-icon' sizes='114x114' href='' />
<link rel='apple-touch-icon' sizes='144x144' href='' />");

// Initialize phone standalone script
session('standalone', array('ios' => "<meta name='apple-mobile-web-app-capable' content='yes'>
                               <meta name='apple-mobile-web-app-status-bar-style' content='black' />",
                     'android' => "<meta name='mobile-web-app-capable' content='yes'>"));

// Initialize metadata
session('metadata', ['site_name' => '', 'site_url' => '', 'creator' => '','title' => '', 'image' => '', 'description' => '', 'amount' => '', 'currency' => '', 'shipping_cost' => '', 'shipping_cost_currency' => ''] );

// Keywords
keywords("caligrafy, php, mvc, framework, mvc framework, php framework, model, view, controller, learn php, learn coding, laravel, symfony", 'keywords');

// Copyright notice
copyright(date("Y")." - Company Name");