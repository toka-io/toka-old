<?php
/*
 * @desc: Service that handles all outgoing emails from the application
 */
class EmailService
{
    function __construct()
    {
    }
    
    function sendPasswordRecoveryEmail($username, $email, $vCode)
    {
        $sender = "no-reply@toka.io";
        $recipient = $email;
        $messageID = time() .'-' . md5($sender . $recipient) . '@toka.io';
    
        $to      = $recipient;
        $subject = "Toka Password Reset";
        $message = file_get_contents('resource/email/pw_recovery_template.php', true);
        $message = str_replace('${username}', $username, $message);
        $message = str_replace('${vCode}', $vCode, $message);
    
        $headers = "From: Toka <" . $sender .">\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html;charset=utf-8" . "\r\n";
        $headers .= "Date: " . date_default_timezone_set('UTC') . "\r\n";
        $headers .= "Message-ID: <" . $messageID . ">\r\n";
    
        mail($to, $subject, $message, $headers);
    }
    
    function sendSignupVerificationEmail($user) 
    {
        $sender = "no-reply@toka.io";
        $recipient = $user->email;
        $messageID = time() .'-' . md5($sender . $recipient) . '@toka.io';
        
        $to      = $recipient;
        $subject = "Verify your Toka account";
        $message = file_get_contents('resource/email/verify_template.php', true);
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