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
                   'animate' => $_SESSION['public'].'css/animate.css',
                   'script' => $_SESSION['public'].'js/script.js',
                   'bootstrap_css' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
                   'bootstrap_script' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
                   'bootstrap_jquery' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js',
                   'fallback_html5shiv' => 'https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js',
                   'fallback_respond' => 'https://oss.maxcdn.com/respond/1.4.2/respond.min.js',
                   'lightbox_script' => "<script src='../js/lightbox/lightbox.js'></script><script>lightbox.option({'alwaysShowNavOnTouchDevices': true,'disableScrolling': true})</script>",
                   'add_to_home' => $_SESSION['public'].'js/addtohome/src/addtohomescreen.js',
                   'add_to_home_css' => $_SESSION['public'].'js/addtohome/style/addtohomescreen.css',
                   'wysiwyg_css' => 'https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css',
                   'wysiwyg_js' => 'https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js',
                   'social_buttons_css' => $_SESSION['public'].'css/social_buttons.css'
                 ));


// Initialize metadata
session('metadata', ['site_name' => '', 'site_url' => '', 'creator' => '','title' => '', 'image' => '', 'description' => '', 'amount' => '', 'currency' => '', 'shipping_cost' => '', 'shipping_cost_currency' => '', 'google-site-verification' => '', 'availability' => ''] );

// Keywords
keywords("caligrafy, php, mvc, framework, mvc framework, php framework, model, view, controller, learn php, learn coding, laravel, symfony", 'keywords');

// Copyright notice
copyright(date("Y")." - Company Name");