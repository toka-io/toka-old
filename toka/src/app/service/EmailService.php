<?php
require_once('vendor/autoload.php');

/*
 * @desc: Service that handles all outgoing emails from the application
 */
class EmailService
{
    function __construct()
    {
    }
    
    function sendSignupVerificationEmail($user) 
    {
        $sender = "no-reply@toka.io";
        $recipient = $user->email;
        $messageID = time() .'-' . md5($sender . $recipient) . '@toka.io';
        
        $to      = $recipient;
        $subject = "Verify your Toka account";
        $message = file_get_contents(__DIR__ . '../../resource/email/verify_email_template.php');
        $message = str_replace('${username}', $user->username, $message);
        $message = str_replace('${vCode}', $user->vCode, $message);
        
        $headers = "From: Toka <" . $sender .">\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html;charset=utf-8" . "\r\n";
        $headers .= "Date: " . date_default_timezone_set('UTC') . "\r\n";
        $headers .= "Message-ID: <" . $messageID . ">\r\n";
        
        mail($to, $subject, $message, $headers);
    }
}