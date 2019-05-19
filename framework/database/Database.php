<?php
/**
 * Database.php is the file that handles all database global functionalities
 * @copyright 2019
 * @author Dory A.Azar 
 * @version 1.0
 */

/**
 * The Database class handles all database connections and global functions that interface directly with the database
 * @author Dory A.Azar 
 * @version 1.0
 */

namespace Caligrafy;
use PDO;
use \Exception as Exception;

class Database {
    // DB Params
    private static $db_activate = DB_ACTIVATE;
    private static $db_adapter = DB_CONNECTION;
    private static $host = DB_HOST;
    private static $db_name = DB_DATABASE;
    private static $username = DB_USERNAME;
    private static $password = DB_PASSWORD;
    private static $connection;
    private static $renderJson = false;

    /**
     * Connects to the database
     * @return the database connection
     * @author Dory A.Azar 
     * @version 1.0
     */    
    public static function connect() 
    {
        self::$connection = null;
        if (strtolower(self::$db_activate) == 'true') {
            self::$connection = new PDO(self::$db_adapter.':host='. self::$host .';dbname='. self::$db_name, self::$username, self::$password);
            self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        return self::$connection;
    }


    /**
     * Executes PDO Query
     * @param string $query defines the query to be executed
     * @param array $args defines the argument that need to be bound to the query
     * @return rendered result in array or json format
     * @author Dory A.Azar 
     * @version 1.0
     */  
    public static function execute($query, $args =array()) 
    {
            if (!self::$connection) {
                throw new Exception(Errors::get('2003'), 2003);
            }
            $stmt = self::$connection->prepare($query);
            if(!$stmt || !is_array($args)) {
                throw new Exception(Errors::get('2003'), 2003);
            }
            $stmt->execute($args);
            return self::render($stmt);
    }


    /**
     * Result rendered
     * @param array $result defines the result coming from the database in an array format
     * @return rendered result in array or json format depending on the setting
     * @author Dory A.Azar 
     * @version 1.0
     */  
    public static function render($result)
    {      
        // create the JSON array
        $jsonarray = array();
        while ($row = $result->fetch())
        {
            $jsonarray[] = $row;

        }
        if (self::$renderJson) {
            return json_encode($jsonarray);
        }
        else
        {
            return json_decode(json_encode($jsonarray), true);
        } 
    }

    /**
     * Forces the result to an array
     * @param array $result defines the result coming in either a json or array format
     * @return array $result the result rendered as an array
     * @author Dory A.Azar 
     * @version 1.0
     */  
    public static function toArray($result)
    {
        $render = $result;
        if (!is_array($result) && self::is_Json($result)) 
        {
            $render = json_decode($result, true);
        }
        return $render;
    }


    /**
     * Checks if a string i a JSON
     * @param string $string defines the string to be checked
     * @return boolean if the string is a JSON
     * @author Dory A.Azar 
     * @version 1.0
     */ 
    public static function is_Json($string) 
    {
         json_decode($string);
         return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Gets Last Inserted Id
     * @return int Last Inserted Id
     * @author Dory A.Azar 
     * @version 1.0
     */ 
    public static function lastInsertId() 
    {
        return self::$connection->lastInsertId();
    }
    
    
}