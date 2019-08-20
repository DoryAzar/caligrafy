<?php
/**
 * Initialize.php is the file that handles the calls to all bootstrap and external scripts
 * @copyright 2019
 * @author Dory A.Azar
 * @version 1.0
 */
/**
 * The Initialize module handles the calls to all bootstrap and external scripts
 * @author Dory A.Azar
 * @version 1.0
 */


date_default_timezone_set(getenv('SERVER_TIMEZONE') != null? getenv('SERVER_TIMEZONE'): 'America/New_York');
setlocale(LC_MONETARY, getenv('APP_LOCALE'));
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// Define path constants

define("DS", DIRECTORY_SEPARATOR);
define("ROOT", getcwd() . DS);
define("APP_PATH", ROOT . 'application' . DS);
define("TEST_PATH", ROOT . 'tests' . DS);
define("FRAMEWORK_PATH", ROOT . "framework" . DS);
define("PUBLIC_PATH", ROOT . "public" . DS);
define("CONFIG_PATH", APP_PATH . "config" . DS);
define("CONTROLLER_PATH", APP_PATH . "controllers" . DS);
define("MODEL_PATH", APP_PATH . "models" . DS);
define("VIEW_PATH", APP_PATH . "views" . DS);
define("CORE_PATH", FRAMEWORK_PATH . "core" . DS);
define('DB_PATH', FRAMEWORK_PATH . "database" . DS);
define("LIB_PATH", FRAMEWORK_PATH . "librairies" . DS);
define("HELPER_PATH", FRAMEWORK_PATH . "helpers" . DS);
define("UPLOAD_PATH", PUBLIC_PATH . "uploads" . DS);
define("FONTS_PATH", PUBLIC_PATH . "fonts" . DS);


//URL of the application
$_SESSION['base'] = getenv('APP_PROTOCOL')."://".$_SERVER['SERVER_NAME'].DS;
$_SESSION['home'] = $_SESSION['base'].(getenv('APP_ROOT')? getenv('APP_ROOT').DS : '');
$_SESSION['public'] = $_SESSION['home'].'public'.DS;
$_SESSION['appClient'] = $_SESSION['home'].'public/app'.DS;
$_SESSION['appService'] = $_SESSION['home'].'public/app/services'.DS;
$_SESSION['uploadsUrl'] = $_SESSION['home']."public/uploads".DS;
$_SESSION['jsUrl'] = $_SESSION['home']."public/js".DS;
$_SESSION['cssUrl'] = $_SESSION['home']."public/css".DS;
$_SESSION['imagesUrl'] = $_SESSION['home']."public/images".DS;

define("APP_CLIENT", $_SESSION['appClient']);
define("APP_SERVICE_ROOT", $_SESSION['appService']);