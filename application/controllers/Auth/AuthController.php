<?php

use Caligrafy\Controller;

class AuthController extends Controller {
    
    
    public function index()
    {
        return view('/default/index', array('authorized' => authorized()));
    }
    
    public function register()
    {
        $this->associate('User', 'users');
        $parameters = $this->request->parameters;
        $validate = $this->validator->check($parameters, array('username' => 'required|alpha_numeric|max_len, 100',
                                                   'passcode' => 'required|alpha_numeric|max_len,20'));
        if ($validate !== true) {
            return view('/Auth/register', array('error' => true, 'status' => 'danger', 'message_header' => 'Whoops, something is not right', 'message' => 'Some of the inputs are invalid. Make sure all the required inputs are entered properly', 'errors' => $validate ));
            exit;
        }
        $user = new User();
        $user->username = $parameters['username'];
        $user->passcode = encryptDecrypt('encrypt', $parameters['passcode']);
        $this->save($user);
        redirect('/login');

    }
    
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
    
    public function logout()
    {
        // Make a call out to the core helpher function that terminates the user info from the session
        logout('/home');
    }
    
    
}