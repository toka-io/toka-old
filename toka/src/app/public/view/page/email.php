<?php
require_once(__DIR__ . '/../../../service/EmailService.php');
require_once(__DIR__ . '/../../../model/UserModel.php');

$user = new UserModel();
$user->username = "jayfenglin";
$user->email = "jayfenglin@gmail.com";
$user->generateVCode();

echo $user->vCode;

//$emailService = new EmailService();
//$emailService->sendSignupVerificationEmail($user);