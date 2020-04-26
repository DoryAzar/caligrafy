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
if (!file_exists('.env')) {
	system("cp framework/settings/.env.example .env");
}
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->overload();

Caligrafer::run();

$defaultMsg = "\n 3 functions are available for you: 
				\n - generatekeys: generates an APP and an API pair of keys that you can put in your app's environment variable 
				\n - generateapikey: generates an API key that you can provide to any third party desiring to access your services
				\n - create <project_name>: scaffolds a Vue project
				\n - initialize: Initializes and signs your project
				\n\n";
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
	case 'initialize':
		try {
		   print("\n\n Preparing and signing the project for usage (We might need you to authenticate you) \n");
		   $file = '.env';
           $keys = Caligrafer::generateKeys(); 
           $appKey = isset($keys['APP_KEY'])? $keys['APP_KEY'] : null;
           $apiKey = isset($keys['API_KEY'])? $keys['API_KEY'] : null;
		   $input = "APP_KEY=".$appKey."\n"."API_KEY=".$apiKey."\n". file_get_contents($file);
		   $vueInput = "VUE_APP_APP_KEY=".$appKey."\n"."VUE_APP_API_KEY=".$apiKey."\n";
		   file_put_contents($file, $input);
		   file_put_contents(LIB_PATH . 'app/' . $file, $vueInput);
		   system('sudo chmod -R 777 public/uploads');
		   system('sudo rm -R .git');
		   print("\n Application initialized successfully");
		   print ("\n APP_KEY=".$appKey);
		   print("\n API_KEY=".$apiKey);
		   print("\n\n");
        } catch (Exception $e) {
            print($e->getMessage());
        }
		break;
	case 'create':
		if (isset($argv[2])) {
			system('cp -r framework/librairies/app ./'.$argv[2], $retValue);
			print("\n Project created in the Caligrafy root folder. \n\n Type cd ".$argv[2]. " to access it");
		}
		break;
        
    default:
        print($defaultMsg);
}





