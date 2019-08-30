<?php
/**
 * Auth.php
 * Class that defines the properties and methods of an authentication mechanism for UI and API
 * @author Dory A.Azar
 * @copyright 2019
 * @version 1.0
 *
*/

/**
 * Class that defines the properties and methods of an authentication mechanism for UI and API
 * @version 1.0
 *
*/

namespace Caligrafy;

class Auth {
    

    public static $api = false;
    private static $authModel;
    
    /**
     * Activate API by creating an API token
     * @return boolean of success of completion of the method
    */
    public static function activateAPI()
    {
        self::$api = true;
    }
    
    /**
     * Activate User Authentication - instructs the framework that an authentication can be used
     * @param $modelname defines the name of the model associated with the Authentication
     * @param $tablename defines the name of the database table associated with the Authentication
     * @return boolean of success of completion of the method
    */
    public static function authentication($modelname, $tablename) 
    {
        self::$authModel = new $modelname($tablename);
        return self::$authModel? true : false;

    }
 
    /**
     * Method used upon login to authorize the logged in user
     * @param Model $user defines the user model that needs to be authorized
     * @return boolean of success of completion of the method
    */
    public static function authorize($user) {
        $userEncrypt = null;
        if ($user instanceof self::$authModel) {
            $userEncrypt = encryptDecrypt('encrypt', json_encode(array('userData' => $user, 'timestamp' => time())))?? null;
            session('__authorization', $userEncrypt);
        }
        return $userEncrypt? true : false;
    } 
    
    /**
     * Method used upon logout to unauthorize all users
    */
    public static function unauthorize() {
        clearFromSession('__authorization');
    }
    
    /**
     * Getter that returns the API token
    */
    public static function api()
    {
        return self::$api?? false;
    }
    
    /**
     * Method that checks if the user in session is authorized to perform actions
     * @return boolean of whether or not the user is valid and allowed to perform actions
    */
    public static function authorized()
    {
        $sessionAuth = isset($_SESSION['__authorization'])? json_decode(encryptDecrypt('decrypt', session('__authorization')))?? null : null;
        $sessionAuthUser = $sessionAuth? $sessionAuth->userData?? null : null;
        
        // find the user in the database
        $user = $sessionAuthUser? json_decode(json_encode((self::$authModel)->find($sessionAuthUser->id)))?? null : null;

        return $user && $user == $sessionAuthUser;
    }
    
    /**
     * Method that checks if the user in session has permissions to access 
     * @return boolean of whether or not the user is valid and allowed to perform actions
    */
    public static function guard($permissionAttribute, $acceptedPermission, $loginUrl = '/')
    { 
        if (!self::authorized()) {
            redirect($loginUrl);
            exit;
        }
        
        $user = self::user();
        if(!$user || $user->$permissionAttribute < $acceptedPermission) {
            redirect($loginUrl);
            exit;
        }
        return;
    }
      
    /**
     * Method that logs the user out
    */  
    public static function logout($redirectUrl = '/')
    {
        self::unauthorize();
        session_destroy();
        redirect($redirectUrl);
        exit;
    }
  
    /**
     * Method that returns the currently logged in user
     * @return currently logged in user
    */
    public static function user()
    {
        $sessionAuth = isset($_SESSION['__authorization'])? json_decode(encryptDecrypt('decrypt', session('__authorization')), true)?? null : null;
        $sessionAuthUser = !empty($sessionAuth) && isset($sessionAuth['userData'])? $sessionAuth['userData'] : null;
        $user = $sessionAuthUser? (self::$authModel)->arrayToModel($sessionAuthUser) : null;
        return $user?? null;
    }
    
    
}