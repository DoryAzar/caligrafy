<?php

class MailController extends Controller {
    
    public function index()
    {
        $mail = new Mail();
        $mail->sendMail(array(
            'from' => ['name' => 'Dory', 'address' => 'dory.azar@gmail.com'],
            'to' => ['name' => 'Dory', 'address' => 'hello@world.com'],
            'reply_to' => ['name' => 'Dory', 'address' => 'hello@world.com'],
            'bcc' => ['name' => 'yay', 'address' => 'hello3@world2.com'],
            'cc' => ['name' => 'youpi', 'address' => 'hello2@world2.com'],
            'attachment' => ['name' => 'file1', 'address' => PUBLIC_PATH.'images/resources/logo.png']
            
        ), array('hello'));
        
        dump($mail);
        
    }
    
    
}