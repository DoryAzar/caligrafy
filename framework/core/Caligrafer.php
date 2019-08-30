<?php 
/**
 * Caligrafer.php
 * Caligrafer loader
 * @author Dory A.Azar
 * @copyright 2019
 * @version 1.0
 *
*/

/**
 * Class Caligrafer that loads the entire framework 
 * Framework loader
 * @author Dory A
*/

namespace Caligrafy;
use \Exception as Exception;

class Caligrafer {
    
  /**
   * Caligrafer main 
   */
  public static function run() 
  {
       self::init();
       self::autoload();
       self::dispatch();
   }
   
    
      /**
     * Generates Keys from the command line
     */  
   public static function generateKeys()
   {
       $appKey = generateKey()?? null;
       if (!$appKey) {
           throw new Exception(Errors::get('5000'), 5000);
       }
       $apiKey = encryptDecrypt('encrypt', $appKey, $appKey)?? null;
       return array('APP_KEY' => $appKey, 'API_KEY' => $apiKey);
       
   }
    
    /**
     * Generates Api Key from the command line
     */  
   public static function generateApiKey()
   {
       $appKey = getenv('APP_KEY');
       $apiKey = encryptDecrypt('encrypt', $appKey, $appKey)?? null;
       return $apiKey;
   }
    

    /**
     * Initializes all framework configurations
     */
   private static function init() 
   {
        // Initialize global constants
        require_once 'framework/helpers/initialize.php';

        // Initialize global helpers
        require_once 'framework/helpers/corehelpers.php';
       
        loadConstantFromArray($_ENV); // turn them into constants
       
        //Load framework core settings
        require_once 'framework/settings/coresettings.php';
       
   }

    /**
     * Loads all core framework classes 
     */
    private static function autoload()
    {
        // Load classes
        spl_autoload_register(array(__CLASS__,'load'));
    }

    private static function load()
    {
        self::_require_all(CORE_PATH);
        self::_require_all(DB_PATH);
        self::_require_all(APP_PATH);
        self::_require_all(TEST_PATH);
    }

    /**
     * Dispatches the URL to the route
     */
    private static function dispatch() 
    {   
        // Load configuration files
        include CONFIG_PATH . "config.php";
        
    }

    /**
     * Scan the api path, recursively including all PHP files
     *
     * @param string  $dir
     * @param int     $depth (optional)
     */
    private static function _require_all($dir, $depth=0) {
        if ($depth > 5) {
            return;
        }
        // require all php files
        $scan = glob("$dir" . DIRECTORY_SEPARATOR . "*");
        foreach ($scan as $path) {
            if (preg_match('/\.php$/', $path)) {
                require_once $path;
            }
            elseif (is_dir($path)) {
                self::_require_all($path, $depth+1);
            }
        }
    }
    
}