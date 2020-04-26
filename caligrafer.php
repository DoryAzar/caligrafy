#!/usr/bin/php
<?php
/**
 * index.php
 * Run time where all magic happens
 * @author Dory A.Azar
 * @copyright 2019
 * @version 1.0
 *
*/

use Caligrafy\Caligrafer;

require_once "framework/core/Caligrafer.php";

// load external vendors
require_once 'vendor/autoload.php';
       
// load environment variables
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->overload();

Caligrafer::run();

$defaultMsg = "\n 3 functions are available for you: generatekeys, generateapikey and create <project_name> \n\n";
if($argc < 2) {
    print($defaultMsg);
    exit;
}

switch(strtolower($argv[1])) {
        
    case 'generatekeys':
        try {
           $keys = Caligrafer::generateKeys(); 
           $appKey = isset($keys['APP_KEY'])? $keys['APP_KEY'] : null;
           $apiKey = isset($keys['API_KEY'])? $keys['API_KEY'] : null;
            print ("\n APP_KEY=".$appKey);
            print("\n API_KEY=".$apiKey);
            print("\n\n");
        } catch (Exception $e) {
            print($e->getMessage());
        }
        break;
    case 'generateapikey':
        $apiKey = Caligrafer::generateApiKey();
        print("\n API_KEY=".$apiKey);
        print("\n\n");
        break;
	case 'ls':
		system('ls -la', $retValue);
		print($retValue);
		break;
	case 'create':
		if (isset($argv[2])) {
			system('cp -r framework/librairies/app ./'.$argv[2], $retValue);
			print($retValue);
		}
		break;
        
    default:
        print($defaultMsg);
}





