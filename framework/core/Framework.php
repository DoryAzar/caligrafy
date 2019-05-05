<?php 
/**
 * Framework.php
 * Framework loader
 * @author Dory A.Azar
 * @copyright 2019
 * @version 1.0
 *
*/

/**
 * Class Framework that loads the entire framework 
 * Framework loader
 * @author Dory A
*/

class Framework {
    
  /**
   * Framework main 
   */
  public static function run() 
  {
       self::init();
       self::autoload();
       self::dispatch();
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
    }

    /**
     * Dispatches the URL to the route
     */
    private static function dispatch() 
    {   
        // Load configuration files
        include CONFIG_PATH . "config.php";
        
        // Load routes
        require_once CONFIG_PATH . "routing/web.php";
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