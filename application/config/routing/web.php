<?php
/**
 * web.php
 * Web Routes are defined in this file
 * @author Dory A.Azar
 * @copyright 2019
 * @version 1.0
 *
*/

use Caligrafy\Auth;
use Caligrafy\Route;
use \Exception as Exception;

try {
    
    // API ACCESS - add comments if you don't want to allow API access on routes
    Auth::activateAPI();
    
    
    // AUTHENTICATION - Remove comment if you have an authentication implemented
    //Auth::authentication('User', 'users');
    
    
    // ROUTE DEFINITION: get, post, put and delete
    Route::get('/', function() { return view('default/index'); });
    Route::get('/helloworld', function() { echo "Hello World"; });
    
    
    // Auth Routes - Uncomment only if AUTHENTICATION activated above
    //Route::get('/home', 'AuthController@home');
    //Route::get('/login', 'AuthController');
    //Route::get('/logout', 'AuthController@logout');
    //Route::get('/register', 'AuthController@registerForm');
    //Route::post('/login', 'AuthController@login');
    //Route::post('/register', 'AuthController@register');
    
    // Bot Routes - Uncomments only if you want to build a Watson bot
    //Route::get('/bot', function() { return view('Client/app', array('bot' => 'index'));});
    //Route::post('/bot/communicate', 'WatsonController@communicate');
    //Route::post('/bot/{appId}', 'WatsonController@connect');
    //Route::delete('/bot', 'WatsonController@end');   
    //
    
    // MUST NOT BE REMOVED - Routes need to be run after being defined 
    Route::run(); 
}
catch (Exception $e) {
    
    // Error handling 
    echo $e->getMessage();
}



