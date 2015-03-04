<?php

/*
 * @desc: Service that handles all outgoing emails from the application
 */
class EmailService
{
    function __construct()
    {
    }
    
    function sendTestEmail() 
    {
        $to      = 'andytlim@gmail.com';
        $subject = 'the subject';
        $message = 'hello';
        $headers = 'From: noreply@toka.io' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
        
        mail($to, $subject, $message, $headers);
    }
}