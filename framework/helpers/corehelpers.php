<?php

/**
 * Generate encryption key
 * @return string the encryption key
 * @author Dory A.Azar 
 * @version 1.0
 */

use Caligrafy\Auth;
use Caligrafy\Controller;
use Caligrafy\CryptoPayment;
use Caligrafy\Errors;
use Caligrafy\Framework;
use Caligrafy\Loader;
use Caligrafy\Mail;
use Caligrafy\Model;
use Caligrafy\Payment;
use Caligrafy\Request;
use Caligrafy\Route;
use Caligrafy\Validator;
use Caligrafy\View;
use Caligrafy\Database;
use \Exception as Exception;

function generateKey($size = 32)
{
    return base64_encode(openssl_random_pseudo_bytes($size));
}

function generateToken($size = 12) {
    return bin2hex(random_bytes($size));
}

/**
 * Encryption and Decryption methods used for key identification of entities
 * @param string $action to specify whether it is an ecryption or decryption
 * @param string $string to specify the field that needs to be encrypted or decrypted
 * @return string of the encrypted or decrypted field
 * @author Dory A.Azar 
 * @version 1.0
 */
function encryptDecrypt($action, $data, $key = APP_KEY) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    // Remove the base64 encoding from our key
    $encryption_key = base64_decode($key);
    
    
    if( $action == 'encrypt' ) {
        // Generate an initialization vector
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($encrypt_method));
        // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
        $encrypted = openssl_encrypt($data, $encrypt_method, $encryption_key, 0, $iv);
        // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
        $output = base64_encode($encrypted . '::' . $iv);
    }
    else if( $action == 'decrypt' ){
        // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        $output = openssl_decrypt($encrypted_data, $encrypt_method, $encryption_key, 0, $iv);
    }
    
    return $output;
}


 /**
 * Checks if a session expired and if it is the session is terminated
 * @param int $time passes a time argument to override the default session_expiration_time if needed
 * @author Dory A.Azar
 * @version 1.0
 */	
function isSessionExpired($time = SESSION_EXPIRATION_TIME) {
    $expired = false;
    // Expire session within SESSION_EXPIRATION_TIME minutes of inactivity
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $time)) {
        $expired = true;
    }
    return $expired;
}

/**
 * Saves forms in a session array
 * @param array $input_data of all variables saved in form
 * @param string $session_variable identifies the session array where data should be saved
 * @param boolean returns true if save is successful
 * @author Dory A.Azar 
 * @version 1.0
 */	
function saveInSession($input_data, $session_variable) {
    $result = false;
    if (!empty($input_data) || isset($input_data)) {
    $_SESSION[$session_variable] = $input_data;
        $result = true;
    }
    return $result;
}

/**
 * Clears data from a specific session variable
 * @param string $session_variable identifies the session array where data has been saved
 * @param boolean returns true if save is successful
 * @author Dory A.Azar 
 * @version 1.0
 */	
function clearFromSession($session_variable) 
{
    $result = false;
    if (!empty($_SESSION[$session_variable]) || isset($_SESSION[$session_variable])) {
    unset($_SESSION[$session_variable]);
        $result = true;
    }
    return $result;
}


/**
 * Gets/Sets script from session by key name
 * @param string $key defines the keyname to look for in the session variables
 * @author Dory A.Azar 
 * @version 1.0
 */	
function scripts($key, $value = null)
{
    if ($value) {
        $_SESSION['scripts'][$key] = $value;
    }
    return $_SESSION['scripts'][$key]?? null;
}

/**
 * Gets the copyright signature from session
 * @author Dory A.Azar 
 * @version 1.0
 */	
function copyright($value = null)
{
    if ($value) {
        $_SESSION['copyright'] = $value;
    }
    return $_SESSION['copyright']?? '';
}

/**
 * Gets/Sets the keywords 
 * @author Dory A.Azar 
 * @version 1.0
 */	
function keywords($value = null)
{
    if ($value) {
        $_SESSION['keywords'] = $value;
    }
    return $_SESSION['keywords']?? '';
}

/**
 * Gets/Sets a metadata variable by key
 * @author Dory A.Azar 
 * @version 1.0
 */	
function metadata($pairs = array())
{
    foreach ($pairs as $key => $value) {
        if ($value) {
            $_SESSION['metadata'][$key] = $value;
        }   
    }
    return $_SESSION['metadata']?? null;
}

/**
 * Gets/Sets a session variable by key
 * @author Dory A.Azar 
 * @version 1.0
 */	
function session($key, $value = null)
{
    if ($value) {
        $_SESSION[$key] = $value;
    }
    return $_SESSION[$key]?? null;
}

/**
 * Generic view function that instantiates a View class
 * @author Dory A.Azar 
 * @version 1.0
 */	
function view($viewName, $viewData = array())
{
    return new View($viewName, $viewData);
}

/**
 * Generic view function that instantiates a View class
 * @author Dory A.Azar 
 * @version 1.0
 */	
function api($viewData = array())
{
    return new View(null, $viewData);
}

/**
 * Looks for a key in a multidimensional array
 * @param array define the array to be searched
 * @param string defines the key to be searched
 * @return boolean if at least one instance is found
 * @author Dory A.Azar 
 * @version 1.0
 */

function findKey($array, $keySearch)
{
    // check if it's even an array
    if (!is_array($array)) return false;
    // key exists
    if (array_key_exists($keySearch, $array)) return true;
    // key isn't in this array, go deeper
    foreach($array as $key => $val)
    {
        // return true if it's found
        if (findKey($val, $keySearch)) return true;
    }
    return false;
}


/**
 * Filters the result depending on package chosen
 * @param int $array defines the array to be filtered
 * @param array $allowed defines what the package allows
 * @return array the filtered results
 * @author Dory A.Azar 
 * @version 1.0
 */

function recursive_filter(&$array, $allowed) {
    foreach($array as $key => &$value) {
        if (!is_array($value)) {
            if (!(in_array($key, $allowed))) {
                unset($array[$key]);
            }
        } else {
            recursive_filter($value, $allowed);
            if (sizeof($value) == 0) {
                unset($array[$key]);
            }
        }
    }
    return $array;
}

/**
 * Define constants from 2D array
 * @param array defines the array to turn into constant definition
 * @return boolean if action was executed
 */

function loadConstantFromArray($array) {
    $result = true;
    if (!is_array($array) || empty($array)) {
        $result = false;
    }
    array_map(function($k, $v){ define($k, $v); }, array_keys($array), array_values($array));
    return $result;
}

/**
 * Terminates a session
 * @param $url to redirect the user upon session termination
 * @author Dory A.Azar 
 * @version 1.0
 */	

function terminateSession($url)
{
    session_unset();
    header('Location: '.$url);
}


/**
 * Function that gets all the super global variables needed for a page to function
 * @param string $superGlobal determines which super variable to fetch information from
 * @return array $result returns an array of all the fetched information desired
 * @version 1.0
 */	

function getGlobalInformation($superGlobal = 'session')
{
    $result = array();
	switch ($superGlobal) {
	    case 'post':
			$result = $_POST;
			unset($_POST);
			break;
	    case 'get':
			$result = $_GET;
			unset($_GET);
			break;
	    case 'files':
			$result = $_FILES;
			unset($_FILES);
			break;
	    default:
			$result = $_SESSION;
			break;
		    
	}
    return $result;
}


/**
 * Uploads a file from a form into the server
 * @param array $picture defines the picture attributes array
 * @return string the resulting public URL of the uploaded file
 * @author Dory A.Azar 
 * @version 1.0
 */

function uploadFile($picture)
{
	$resultUrl = '';

    if (empty($picture) || (isset($picture['error']) && $picture['error'] != 0 )) {
        return $resultUrl;
        exit;
    }
    
    // get the extension of the picture
    $extension = isset($picture['name'])? pathinfo($picture['name'])['extension']: '';

    // if an extension is valid
    if (isset($extension)) {

        //generate a new filename 
        $newfilename = generateToken(12).".".$extension;

        // specify the new document location
        $permanentLocation = UPLOAD_PATH.$newfilename;

        // proceed with the move and if successful return the public url to it
        if (move_uploaded_file($picture['tmp_name'], $permanentLocation)) {
            $resultUrl = $_SESSION['uploadsUrl'].$newfilename;
        };

    }       
	return $resultUrl;
}

/**
 * Deletes a file given its public path
 * @param string $url of the image public url
 * @return boolean of the result
 * @author Dory A.Azar 
 * @version 1.0
 */

function deleteFile($url) 
{
    $path = parse_url($url, PHP_URL_PATH);
    $filename = basename($path);
    if(!$filename) {
        return false;
    }
    unlink(UPLOAD_PATH.$filename);
    return true;
}


/**
 * Checks if a string is a json
 * @param string $string defines the string to be checked
 * @return boolean true if it is a json
 * @author Dory A.Azar 
 * @version 1.0
 */

function isJson($string)
{
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}


/**
 * Sanitizes a string from html tags
 * @param string $string defines the string to be sanitized
 * @return string the sanitized screen
 * @author Dory A.Azar 
 * @version 1.0
 */

function sanitize($string)
{
    return htmlentities($string, ENT_QUOTES, "UTF-8");
}


/**
 * Accessor to the request information
 * @return Request the request object information
 * @author Dory A.Azar 
 * @version 1.0
 */

function request()
{
    return new Request();
}


/**
 * Accessor connects a controller to the model
 * @return Model with connection to database established
 * @author Dory A.Azar 
 * @version 1.0
 */

function connect()
{
   return Database::connect();  
}


/**
 * Accessor to require more librairies and helpers classes and files
 * @author Dory A.Azar 
 * @version 1.0
 */

function import($filename)
{
   if(file_exists(LIB_PATH . $filename . '.php')) {
       require_once LIB_PATH . "$filename.php";
   } else if(file_exists(HELPER_PATH . $filename . '.php')) {
       require_once HELPER_PATH . "$filename.php";
   } else {
       throw new Exception(Errors::get(1002), 1002);
       exit;
   }
}


function parsePath($path)
{
    $pathArray = (isset($path) && $path !='/' && $path != '')? explode('/', preg_replace('/\s+/','', $path)) : array();
    return $pathArray;
}


/**
 * Accessor to the route information
 * @return String the route information
 * @author Dory A.Azar 
 * @version 1.0
 */

function currentRoute()
{
    return Route::$currentRoute;
}

/**
 * Accessor to the list of variables bound to the route
 * @return Array bound variables from the route
 * @author Dory A.Azar 
 * @version 1.0
 */
function currentBind() {
    return Route::$currentBind;
}

/**
 * Checks if strings starts with a character and removes it
 * @return String with the character removed
 * @author Dory A.Azar 
 * @version 1.0
 */

function startWith($haystack, $needle) {
    return substr($haystack, 0, strlen($needle)) === $needle;
}

/**
 * Checks if strings ends with a character and removes it
 * @return String with the character removed
 * @author Dory A.Azar 
 * @version 1.0
 */

function endWith($haystack, $needle) {
    return substr($haystack, -1, strlen($needle)) === $needle;
}

/**
 * Sanitizes the path by getting rid of beginning and ending slashes
 * @return String the new path without the slashes
 * @author Dory A.Azar 
 * @version 1.0
 */

function sanitizePath($path)
{
    $path = startWith($path, '/')? substr($path, 1) : $path;
    $path = endWith($path, '/')? substr($path, 0, -1) : $path;
    return $path;
}

function associate($controller, $modelname, $table) 
{
    return get_parent_class($controller) == 'Caligrafy\Controller'? $controller($modelname, $table) : false;
}

function bindRouteToVar($route, $uri, $pattern = ROUTE_ARG_PATTERN)
{
    $routeArray = parsePath($route);
    $uriArray = parsePath($uri);
           
    $bind = array();
    
    foreach($routeArray as $key => $value) {
       if (preg_match($pattern, preg_replace('/\s+/','', $value), $matches)) {
           if (sizeof($routeArray) == sizeof($uriArray)) {
                $bind[$matches[1]] = $uriArray[$key];   
           }
       } 
    }
    return $bind;
}


/**
 * Parses a path
 * @return parsed information
 * @author Dory A.Azar 
 * @version 1.0
 */
function parseRoute($path, $pattern = ROUTE_ARG_PATTERN)
{
    $matches = array();
    preg_match_all($pattern, preg_replace('/\s+/','', $path), $matches);
    if(!empty($matches) && sizeof($matches) < 2) {
       $matches = $matches[sizeof($matches) - 1];
    } else {
        $matches = array_combine($matches[1], $matches[2]);
    }
    return $matches;
}

/**
 * load all files in user-created directories and subdirectories
 * @author Dory A.Azar 
 * @version 1.0
 */
function _require_all($path, $depth=0) 
{
    $dirhandle = @opendir($path);
    if ($dirhandle === false) return;
    while (($file = readdir($dirhandle)) !== false) {
        if ($file !== '.' && $file !== '..') {
            $fullfile = $path . '/' . $file;
            if (is_dir($fullfile)) {
                _require_all($fullfile, $depth+1);
            } else if (strlen($fullfile)>4 && substr($fullfile,-4) == '.php') {
                require_once $fullfile;
            }
        }
    }

    closedir($dirhandle);
}   

/**
 * Singularizes regular plurals
 * @author Dory A.Azar 
 * @version 1.0
 */
function singularize($word) 
{
    return substr($word, 0, -1);
}

/**
 * Injects a hidden html input to change the method
 * @author Dory A.Azar 
 * @version 1.0
 */
function methodField($method)
{
    echo "<input type='hidden' name='_method' value='$method'>";
}

/**
 * Injects a csrf field into the form
 * @author Dory A.Azar 
 * @version 1.0
 */
function csrf()
{
    echo "<input type='hidden' name='_token' value='".session('token')."'>";
}

/**
 * Method that authorizes a logged in user to perform actions
 * @author Dory A.Azar 
 * @version 1.0
 */
function authorize($user)
{
    return Auth::authorize($user);
}

/**
 * Method that checks if a session user is allowed to perform actions
 * @author Dory A.Azar 
 * @version 1.0
 */
function authorized()
{
    return Auth::authorized();
}

/**
 * Method used upon logout to unauthorize a user in session from performing actions
 * @author Dory A.Azar 
 * @version 1.0
 */
function unauthorize()
{
    return Auth::unauthorize();
}

/**
 * Method that retrieves the user currently logged in
 * @author Dory A.Azar 
 * @version 1.0
 */
function user()
{
    return Auth::user();
}

/**
 * Method that checks if the user in session has permissions to access 
 * @return boolean of whether or not the user is valid and allowed to perform actions
*/
function guard($permissionAttribute, $acceptedPermission, $loginUrl = '/')
{
    return Auth::guard($permissionAttribute, $acceptedPermission, $loginUrl = '/');
}

/**
 * Method that logs the user out
*/  
function logout($redirectUrl = '/')
{
    return Auth::logout($redirectUrl);
}


/**
 * Method that instantiates the Claviska Simple Image library that manipulate pictures 
 * @author Dory A.Azar 
 * @version 1.0
 */
function imageCanva()
{
  try {
      // Return a new claviska image
      return new \claviska\SimpleImage();

    } catch(Exception $err) {
      // Handle errors
      echo $err->getMessage();
    }
}

/**
 * Loads a script into the UI
 * @param array inputs needed to generate the script
 * @return string HTML or TXT fully formatted
 * @author Dory A.Azar 
 * @version 1.0
 */

function load_script($params){

        
	//set the root
    $path = session('jsUrl').$params['script_file'].".html";

	//grab the template content
	$script = file_get_contents($path);
			    
		
	//return the script
	return $script;

}

/**
 * Reroutes to another url
 * @author Dory A.Azar 
 * @version 1.0
 */
function redirect($url)
{
    header('Location: '. session('home') . sanitizePath($url) );
}



/**
 * Get RelativeTime
 * @author Dory A.Azar 
 * @version 1.0
 */
function getRelativeTime($datetime, $depth=1) {

    $units = array(
        "year"=>31104000,
        "month"=>2592000,
        "week"=>604800,
        "day"=>86400,
        "hour"=>3600,
        "minute"=>60,
        "second"=>1
    );

    $plural = "s";
    $conjugator = " and ";
    $separator = ", ";
    $suffix1 = " ago";
    $suffix2 = " left";
    $now = "now";
    $empty = "";

    # DO NOT EDIT BELOW

    $timediff = time()-strtotime($datetime);
    if ($timediff == 0) return $now;
    if ($depth < 1) return $empty;

    $max_depth = count($units);
    $remainder = abs($timediff);
    $output = "";
    $count_depth = 0;
    $fix_depth = true;

    foreach ($units as $unit=>$value) {
        if ($remainder>$value && $depth-->0) {
            if ($fix_depth) {
                $max_depth -= ++$count_depth;
                if ($depth>=$max_depth) $depth=$max_depth;
                $fix_depth = false;
            }
            $u = (int)($remainder/$value);
            $remainder %= $value;
            $pluralise = $u>1?$plural:$empty;
            $separate = $remainder==0||$depth==0?$empty:
                            ($depth==1?$conjugator:$separator);
            $output .= "{$u} {$unit}{$pluralise}{$separate}";
        }
        $count_depth++;
    }
    return $output.($timediff<0?$suffix2:$suffix1);
}
    