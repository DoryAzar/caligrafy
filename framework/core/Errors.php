<?php
/**
 * Errors.php
 * Class that handles all the error and exception messages in the system
 * @author Dory A.Azar
 * @copyright 2019
 * @version 1.0
 *
*/

/**
 * The Errors Class is the dictionary of all the error messages in the system
 * @version 1.0
*/

namespace Caligrafy;

class Errors
{
    /**
     * @var array of error messages with codes
     * @property array of error messages with codes
    */
    private static $_errors = array(
        
        // 1xxx: Routing and Loading errors Errors
        1000 => '[ERROR 1000] Invalid Http Request Method ',
        1001 => '[ERROR 1001] Invalid Controller or Action ',
        1002 => '[ERROR 1002] Invalid library ',
        1003 => '[ERROR 1003] Property cannot be found ',
        1004 => '[ERROR 1004] Access Denied',
        
        //2xxx: Database Errors
        2000 => '[ERROR 2000] Database Connection Error ',
        2001 => '[ERROR 2001] Database Security Error - Not allowed to perform such an operation ',
        2002 => '[ERROR 2002] Database Query Error ',
        2003 => '[ERROR 2003] Database Query Error - Something went wrong in executing the query',
        
        
        //3xxx: Request Errors
        3000 => '[ERROR 3000] Security Error - Cross Site Forgery', 
        
        //4xxx: Mail Errors
        4000 => '[ERROR 4000] Mail Error - Mailer could not be initialized',
        4001 => '[ERROR 4001] Mail Error - Mail Content is not compliant',
        
        
        //5xxx: Caligrafer Errors
        5000 => '[ERROR 5000] Key Generator Error - Generator could not generate keys'
    );
    
    
    /** 
     * Get error message based on error code
     * @param string $errorCode identifies the error code
     * @return string identifying the error message
    */    
    public static function get($errorCode) {
        return self::$_errors[$errorCode];
    }
    
    public static function set($errorMessage, $errorCode) {
        self::$_errors[$errorCode] = !isset(self::$_errors[$errorCode])? $errorMessage : self::$_errors[$errorCode]." - ".$errorMessage;
        return self::$_errors[$errorCode];
    }
}
