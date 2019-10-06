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
        
        $this->mailer = new PHPMailer;
        if (!$this->mailer) {
            throw new Exception(Errors::get('4000'), 4000);  
            exit;
        }
        
        //Server settings
        $this->mailer->SMTPDebug = 2;                                       
        $this->mailer->isSMTP();
        $this->mailer->Host = $this->mailHost;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->mailUsername;
        $this->mailer->Password = $this->mailPassword;
        $this->mailer->SMTPSecure = 'tls';
        $this->mailer->Port = $this->mailPort;     
                    
        return $this;
    }
    
    
    public function sendMail($recipients, $content)
    {
        if (empty($recipients)) {
            throw new Exception(Errors::get('4001'), 4001);
        }
        
        // adding recipients with name
        $this->mailer->setFrom($recipients['from']);
        $this->mailer->addAddress($recipients['to']);
        
        
        //$this->add($recipients['from'], 'setFrom')
        //     ->add($recipients['to'], 'addAddress')
        //     ->add($recipients['reply_to'], 'addReplyTo')
        //     ->add($recipients['cc'], 'addCC')
        //     ->add($recipients['bcc'], 'addBCC')
        //     ->add($recipients['attachment'], 'addAttachment');
        
        // add Content
        $this->mailer->isHTML(true);
        $this->mailer->Subject = 'Here is the subject';
        $this->mailer->Body    = 'This is the HTML message body <b>in bold!</b>';
        $this->mailer->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
        
        if(!$this->mailer->send()) {
            return $this;
            exit;
        }       

        return true;
        
    }
    
    
}
