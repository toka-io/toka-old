<?php
// @model
require_once('model/UserModel.php');

require_once('repo/IdentityRepo.php');
require_once('repo/ChatroomRepo.php');

require_once('service/EmailService.php');

require_once('utility/TimeUtility.php');

class IdentityService
{
    private $_maxChatrooms = 1;
    
    function __construct()
    {
    }
    
    /*
     * @desc: Activate a user
     */
    public function activateUser($request, $response)
    {
        $user = new UserModel();
        
        $user->setUsername($request['login']);
        $user->setVerificationCode($request['vCode']);
    
        $identityRepo = new IdentityRepo(true);
        $exists = $identityRepo->isUser($user);
        
        if (!$exists) {
            $response['status'] = 0;
            $response['statusMessage'] = "user does not exist";
    
            return $response;
        }
        
        $validVCode = $identityRepo->isValidVerificationCode($user);
        
        if ($validVCode) {
            $success = $identityRepo->activateUser($user);
        
            if ($success) {
                $response['status'] = 1;
                $response['statusMessage'] = "user activated";
            } else {
                $response['status'] = 0;
                $response['statusMessage'] = "activate user failed";
            }
        } else {
            $response['status'] = 0;
            $response['statusMessage'] = "verification code is invalid";
        }
    
        return $response;
    }
    
    /*
     * @desc: Creates a new user if the username is valid and available
     */
    public function createUser($request, $response)
    {
        $newUser = new UserModel();        
        $newUser->setDisplayName($request['username']);
        $newUser->setEmail($request['email']);
        $newUser->setPassword($request['password']);
        $newUser->addSalt();
        $newUser->setUsername($request['username']);
        
        if (!$this->isValidUsername($newUser->username)) {
            $response['status'] = 0;
            $response['statusMessage'] = "user information is invalid";
            
            return $response;
        } else if (!$this->isValidEmail($newUser->email)) {
            $response['status'] = 0;
            $response['statusMessage'] = "user information is invalid";
        
            return $response;
        }
        
        $identityRepo = new IdentityRepo(true);
        $usernameAvailable = $identityRepo->isUsernameAvailable($newUser->username);
        $emailAvailable = $identityRepo->isEmailAvailable($newUser->email);
        
        if (!$usernameAvailable) {
            
            $response['status'] = 0;
            $response['statusMessage'] = "username is not available";
            
        } else if (!$emailAvailable) {
            
            $response['status'] = 0;
            $response['statusMessage'] = "email is not available";
            
        } else {        
            $vCode = $this->generateVCode();
            $newUser->setVerificationCode($vCode);
            $success = $identityRepo->createUser($newUser);
        
            if ($success) {
                $emailService = new EmailService();
                $emailService->sendSignupVerificationEmail($newUser);
                
                $response['status'] = 1;
                $response['statusMessage'] = "user created";
                $response['displayMessage'] = "A verification email has been sent!";
            } else {
                $response['status'] = 0;
                $response['statusMessage'] = "create user failed";
            }
        }        
        
        return $response;
    }
    
    public function userExists($username) 
    {        
        $identityRepo = new IdentityRepo(false);
        $usernameAvailable = $identityRepo->isUsernameAvailable($username);
        
        return !$usernameAvailable;
    }
    
    /*
     * @desc: Deactivates a user
     */
    public function deactivateUser($request, $response)
    {
        $user = new UserModel();
        
        if (isset($_COOKIE['username']))
            $user->setUsername($_COOKIE['username']);
        
        $isLoggedIn = !empty($user->username);
        
        if (!$isLoggedIn) {
            $response['status'] = "0";
            $response['statusMsg'] = "not allowed to deactivate user";
            
            return $response;
        }

        $identityRepo = new IdentityRepo(true);
        $success = $identityRepo->deactivateUser($user);

        if ($success) {
            $response['status'] = "1";
            $response['statusMsg'] = "user deactivated";
        } else {
            $response['status'] = "0";
            $response['statusMsg'] = "deactivate user failed";
        }
            
        return $response;
    }
    
    public function generateVCode()
    {
        return bin2hex(openssl_random_pseudo_bytes(12));
    }
    
    /*
     * $user: UserModel
     */
    public function getChatroomsByOwner($user) 
    {
        $chatroomRepo = new ChatroomRepo(false);
        
        $chatrooms = $chatroomRepo->getChatroomsByOwner($user);
        
        return $chatrooms;
    }
    
    public function getUserSession() 
    {
        $user = new UserModel();
        
        if (isset($_COOKIE['username']))
            $user->setUsername($_COOKIE['username']);
        
        return $user;
    }
    
    /*
     * $user: UserModel
     */
    public function hasMaxChatrooms($user)
    {
        $count = count($this->getChatroomsByOwner($user));

        return $count >= $this->_maxChatrooms;
    }
    
    public function isUserLoggedIn()
    {
        return isset($_COOKIE['sessionID']) && isset($_COOKIE['username']);
    }
    
    /*
     * $user: string
     */
    public function isUsernameAvailable($username) 
    {        
        $identityRepo = new IdentityRepo(false);
        
        $usernameAvailable = $identityRepo->isUsernameAvailable($username);
        
        return $usernameAvailable;
    }
    
    public function isValidEmail($email)
    {
        $val = preg_match("/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/", $email);
    
        return ($val === 1) ? true: false;
    }
    
    /*
     * @desc: Enforce password strength
     */
    public function isValidPassword($password)
    {
        return strlen($password) >= 5;
    }
    
    public function isValidUsername($username)
    {
        $val = preg_match("/^[a-z0-9_]{3,25}$/", $username);
    
        return ($val === 1) ? true : false;
    }
    
    /*
     * @desc: Logs a user in and creates a session for the user
     * $data: array
     * $response: array
     */
    public function login($data, $response)
    {        
        $user = new UserModel();        
        $user->setPassword($data['password']);
        $user->setUsername($data['username']);
    
        // Check if user has been activated
        $identityRepo = new IdentityRepo(true);        
        $active = $identityRepo->isActive($user);
        
        if (!$active) {
            $response['status'] = '0';
            $response['statusMsg'] = "user account has not been activated";
            
            return $response;
        }
        
        $passwordMatch = $identityRepo->checkUserPassword($user);        
        
        if ($passwordMatch === true) {            
            // session_regenerate_id(true); Enable this if we do timeouts for logins or if we are paranoid about session hijacking
            
            array_push($user->sessions, session_id());
            
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                
                // $numberOfDays = 30;
                // $expirationDate = time() + 60 * 60 * 24 * $numberOfDays;
                
                setcookie(
                    "username",
                    $user->username,
                    0,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            
            $success = $identityRepo->login($user);
            
            if ($success) {                
                $response['status'] = "1";
                $response['statusMsg'] = "user login successful";
                $response['sessiondID'] = session_id();
            } else {
                $response['status'] = '0';
                $response['statusMsg'] = "user login failed";
            }
            
        } else {
            $response['status'] = "0";
            $response['statusMsg'] = "username does not exist or password is incorrect";
        }
    
        return $response;
    }
    
    /*
     * @desc: Logs a user out and destroys all sessions
     */
    public function logout()
    { 
        $user = new UserModel();        
        $user->setUsername($_COOKIE['username']);

        $identityRepo = new IdentityRepo(true);
        $identityRepo->logout($user);

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            
            setcookie(
                session_name(),
                "",
                time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
            setcookie(
                "username",
                "",
                time() - 42000,
                $params["path"], "toka.io",
                $params["secure"], $params["httponly"]
            );
        }
        
        // Finally, destroy the user session.
        unset($_SESSION['user']);
    }
    
    public function recoverPassword($request, $response)
    {    
        $email = isset($request['email']) ? $request['email'] : "";
        $username = isset($request['username']) ? $request['username'] : "";
    
        $identityRepo = new IdentityRepo(true);
        $usernameAvailable = $identityRepo->isUsernameAvailable($username);
        $emailAvailable = $identityRepo->isEmailAvailable($email);
    
        if ($usernameAvailable && $emailAvailable) {
    
            $response['status'] = 0;
            $response['statusMessage'] = "username or email does not exist!";
            
        } 
        else {        
            $vCode = $this->generateVCode();
            
            if (empty($email))
                $email = $identityRepo->getEmailByUsername($username);
            if (empty($username))
                $username = $identityRepo->getUsernameByEmail($email);
            
            $identityRepo->updatePasswordVCode($email, $vCode);
            
            $emailService = new EmailService();
            $emailService->sendPasswordRecoveryEmail($username, $email, $vCode);
            
            $response['status'] = 1;
            $response['statusMessage'] = "password recovery email sent";
            $response['displayMessage'] = "An email has been sent with the instructions to reset your password.";
        }
    
        return $response;
    }
    
    public function resetPassword($request, $response)
    {        
        if (isset($request['username']) && isset($request['vCode']) && isset($request['password'])) {
            $user = new UserModel();
            $user->username = $request['username'];
            $user->password = $request['password'];
            $vCode = $request['vCode'];            
        }
        
        $identityRepo = new IdentityRepo(true);        
        $userOld = $identityRepo->getUserByUsername($user->username);
        
        $timestamp = TimeUtility::convertMongoDateToDate($userOld->passwordVCode['createdDate']);
        $min = TimeUtility::getMinuteDifferenceFromNow($timestamp);        
        
        if ($vCode !== $userOld->passwordVCode['code']) {
            $response = array(
                    'status' => 0,
                    'statusMessage' => 'invalid verficiation code',
                    'displayMessage' => 'Invalid password reset request.'
            );
        }
        else if ($min > 120) {
            $response = array(
                    'status' => 0,
                    'statusMessage' => 'expired',
                    'displayMessage' => 'The password recovery request has expired.'
            );
        }
        else {
            
            $user->salt = $userOld->salt;
            $user->addSalt();            
            
            $success = $identityRepo->updatePassword($user);
            if ($success) {
                $response = array(
                    'status' => 1,
                    'statusMessage' => 'success',
                    'displayMessage' => 'Password has been reset successfully.'
                );
            }
            else {
                $response = array(
                    'status' => 0,
                    'statusMessage' => 'database error',
                    'displayMessage' => 'The server could not process the request at this time. Please try again.'
                );
            }            
            
        }
    
        return $response;
    }
    
    public function updateRecentRooms($username, $room)
    {           
        $identityRepo = new IdentityRepo(true);
    
        return $identityRepo->updateRecentRooms($username, $room);
    }
    
    public function validatePasswordRecoveryRequest($request) 
    {        
        if (isset($request['login']) && isset($request['vCode'])) {
            $username = $request['login'];
            $vCode = $request['vCode'];
        }
        else
            return array(
                        'status' => 0,
                        'statusMessage' => 'invalid request',
                        'displayMessage' => 'Missing request parameters.'
                   );
        
        $result = array();
            
        $identityRepo = new IdentityRepo(false);        
        $document = $identityRepo->getPasswordVCodeByUsername($username);
        
        if (!empty($document)) {
            
            if ($vCode != $document['passwordVCode']['code'])
                return array(
                            'status' => 0,
                            'statusMessage' => 'invalid code',
                            'displayMessage' => 'The verficiation code is invalid.'
                       );
            
            $timestamp = TimeUtility::convertMongoDateToDate($document['passwordVCode']['createdDate']);
            $min = TimeUtility::getMinuteDifferenceFromNow($timestamp);
            
            if ($min <= 120) {
                $result = array(
                        'status' => 1,
                        'statusMessage' => 'valid'
                );
            }
            else {
                $result = array(
                        'status' => 0,
                        'statusMessage' => 'expired',
                        'displayMessage' => 'The password recovery request has expired.'
                );
            }
        } else {
            $result = array(
                    'status' => 0,
                    'statusMessage' => 'The user does not exist or toka broke!'
            );
        }            
        
        return $result;
    }
}