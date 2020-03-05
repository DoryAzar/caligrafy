<?php

use Caligrafy\Controller;

class AuthController extends Controller {
    
    
    public function index()
    {
        if (!authorized()) {
            return view('Auth/login');
            exit;
        }
        redirect('/home');
    }
    
    public function registerForm()
    {
        if (authorized()) {
            redirect('/home');
        }
        return view('Auth/register');
        exit;       
    }
    
    
    public function home()
    {
        if (authorized()) {
            return view('default/index', array('authorized' => authorized()));   
            exit;
        }
        redirect('/login');

    }
    
    /*
     *Register
     */
    public function register()
    {
        $this->associate('User', 'users');
        $parameters = $this->request->parameters;
        $validate = $this->validator->check($parameters, array('username' => 'required|alpha_numeric|max_len, 100',
                                                               'passcode' => 'required|alpha_numeric|max_len,20',
                                                               'confirmpasscode' => 'required|alpha_numeric|max_len,20'
                                                              ));
        $user = $this->search('username', $parameters['username']);
        $userInput = (Object)$parameters;
   
        
        //confirm password
        if ($parameters['passcode'] != $parameters['confirmpasscode']) {
            return view('/Auth/register', array('error' => true, 'status' => 'danger', 'message_header' => 'Whoops, something is not right', 'message' => 'The passwords you entered don\'t match. Make sure that the password and the confirm password fields match.', 'errors' => ['passcode' => 'passwords don\'t match'], 'user' => $userInput));
            exit;
        }
        
        // invalid inputs
        if ($validate !== true) {
            return view('/Auth/register', array('error' => true, 'status' => 'danger', 'message_header' => 'Whoops, something is not right', 'message' => 'Some of the inputs are invalid. Make sure all the required inputs are entered properly', 'errors' => $validate, 'user' => $userInput ));
            exit;
        }
        
        //check if user exists
        if ($user && !empty($user[0])) {
            return view('/Auth/register', array('error' => true, 'status' => 'danger', 'message_header' => 'Whoops, something is not right', 'message' => 'You are already registered', 'errors' => ['username' => 'The username already exists in the system'], 'user' => $userInput));
            exit;            
        }
        
        $user = new User();
        $user->username = $parameters['username'];
        $user->passcode = encryptDecrypt('encrypt', $parameters['passcode']);
        $this->save($user);
        redirect('/login');

    }
    
    
    /*
     * Login
     */
    public function login()
    {
        $this->associate('User', 'users');
        $parameters = $this->request->parameters;
        $user = $this->search('username', $parameters['username']);
        if($user && !empty($user[0]) && isset($user[0]->id)) {
            $pass = encryptDecrypt('decrypt', $user[0]->passcode);
            if ( $pass == $parameters['passcode']) {
                authorize($user[0]);
                redirect('/home');
                exit;
            }
        } 
        return view('/Auth/login', array('error' => true, 'status' => 'danger', 'message_header' => 'Whoops, something is not right', 'message' => 'Invalid credentials' ));
        exit;
    }
    
    /*
     * Logout
     */
    public function logout()
    {
        // Make a call out to the core helpher function that terminates the user info from the session
        logout('/home');
    }
	
	/*
     * Not Authorized Login page
     */
	public function notAuthorized()
	{
		return view('/Auth/login', array('error' => true, 'status'=>'danger', 'message_header' => 'Restricted Page','message' => 'You have not been granted permission to access this page'));
		exit;
	}
    
    
}