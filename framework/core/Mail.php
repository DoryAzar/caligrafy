<?php

/**
 * Mail.php is the file that sends emails from the framework
 * @copyright 2019
 * @author Dory A.Azar
 * @version 1.0
 */

/**
 * The Mail class handles all emails sent from the framework
 * @author Dory A.Azar
 * @version 1.0
 */

namespace Caligrafy;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as MailerException;
use \Exception as Exception;


class Mail {
    
    private $mailHost;
    private $mailPort;
    private $mailUsername;
    private $mailPassword;
    private $mailer;

    public function __construct()
    {
        
        $this->mailHost = MAIL_HOST;
        $this->mailPort = MAIL_PORT;
        $this->mailUsername = MAIL_USERNAME;
        $this->mailPassword = MAIL_PASSWORD;
        
        $this->mailer = new PHPMailer();
        if (!$this->mailer) {
            throw new Exception(Errors::get('4000'), 4000);  
            exit;
        }
        
        //Server settings
		$this->mailer->isSMTP();
		$this->mailer->Mailer = "smtp";
        $this->mailer->SMTPDebug = 0;   
		$this->mailer->SMTPAuth = true;
		$this->mailer->SMTPSecure = 'tls';
        $this->mailer->Port = $this->mailPort;   
        $this->mailer->Host = $this->mailHost;
        $this->mailer->Username = $this->mailUsername;
        $this->mailer->Password = $this->mailPassword;
      
                    
        return $this;
    }
    
    
    public function sendMail($recipients, $emailContent, $from)
    {
        if (empty($recipients) || empty($emailContent) || empty($from)) {
            throw new Exception(Errors::get('4001'), 4001);
        }
        
		$this->recipientEmail = $recipients['email']?? '';
		$this->recipientName = $recipients['name']?? '';
		$this->subject = $emailContent['subject']?? '';
		$this->body = $emailContent['body']?? '';
		$this->fromEmail = $from['email']?? '';
		$this->fromName = $from['name']?? '';
		
		$this->mailer->IsHTML(true);
		if (is_array($this->recipientEmail)) {
			foreach($this->recipientEmail as $recipientEmail) {
				$this->mailer->AddAddress($recipientEmail);
			}
		} else {
			$this->mailer->AddAddress($this->recipientEmail, $this->recipientName);
		}
		$this->mailer->SetFrom($this->fromEmail, $this->fromName);
		//$this->mailer->AddReplyTo("replytoemail", "replyto name");
		//$this->mailer->AddCC("ccemail", "ccname");
		$this->mailer->Subject = $this->subject;
		$content = $this->body;
		
		$this->mailer->MsgHTML($content);
		if(!$this->mailer->Send()) {
			throw new Exception(Errors::get('4001'), 4001);
		} 

        return true;
       
    }
    
    
}
