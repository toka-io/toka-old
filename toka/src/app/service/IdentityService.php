<?php
// @model
require_once(__DIR__ . '/../model/UserModel.php');

// @repo
require_once(__DIR__ . '/../repo/IdentityRepo.php');

// @service
require_once(__DIR__ . '/../service/EmailService.php');

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
        $user->setVerificationCode($request['v_code']);
    
        $identityRepo = new IdentityRepo(true);
        $exists = $identityRepo->isUser($user);
        
        if (!$exists) {
            $response['status'] = "0";
            $response['statusMsg'] = "user does not exist";
    
            return $response;
        }
        
        $validVCode = $identityRepo->isValidVerificationCode($user);
        
        if ($validVCode) {
            $success = $identityRepo->activateUser($user);
        
            if ($success) {
                $response['status'] = "1";
                $response['statusMsg'] = "user activated";
            } else {
                $response['status'] = "0";
                $response['statusMsg'] = "activate user failed";
            }
        } else {
            $response['status'] = "0";
            $response['statusMsg'] = "verification code is invalid";
        }
    
        return $response;
    }
    
    /*
     * @desc: Creates a new user if the username is valid and available
     */
    public function createUser($request, $response)
    {
        $newUser = new UserModel();
        
        if (isset($request['data']['email']))
            $newUser->setEmail($request['data']['email']);
        
        if (isset($request['data']['password']))
            $newUser->setPassword($request['data']['password']);
        
        if (isset($request['data']['username']))
            $newUser->setDisplayName($request['data']['username']);
        
        if (isset($request['data']['username']))
            $newUser->setUsername($request['data']['username']);
        
        if (!$newUser->isValidUsername()) {
            $response['status'] = "0";
            $response['statusMsg'] = "user information is invalid";
            
            return $response;
        } else if (!$newUser->isValidEmail()) {
            $response['status'] = "0";
            $response['statusMsg'] = "user information is invalid";
        
            return $response;
        }
        
        $newUser->addSalt();
        
        $identityRepo = new IdentityRepo(true);
        $usernameAvailable = $identityRepo->isUsernameAvailable($newUser);
        $emailAvailable = $identityRepo->isEmailAvailable($newUser);
        
        if (!$usernameAvailable) {
            
            $response['status'] = "0";
            $response['statusMsg'] = "username is not available";
            
        } else if (!$emailAvailable) {
            
            $response['status'] = "0";
            $response['statusMsg'] = "email is not available";
            
        } else {        
            $newUser->generateVCode();
            $success = $identityRepo->createUser($newUser);
        
            if ($success) {
                $emailService = new EmailService();
                $emailService->sendSignupVerificationEmail($newUser);
                
                $response['status'] = "1";
                $response['statusMsg'] = "user created";
            } else {
                $response['status'] = "0";
                $response['statusMsg'] = "create user failed";
            }
        }        
        
        return $response;
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
    
        $user->deactivateUser();

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
    
    public function getChatroomsByOwner($user) 
    {
        $chatroomRepo = new ChatroomRepo(false);
        
        $chatrooms = $chatroomRepo->getChatroomsByOwner($user);
        
        return $chatrooms;
    }
    
    public function hasMaxChatrooms($user)
    {
        $count = count($this->getChatroomsByOwner($user));

        return $count >= $this->_maxChatrooms;
    }
    
    /*
     * @desc: Logs a user in and creates a session for the user
     */
    public function login($request, $response)
    {        
        $user = new UserModel();
        
        if (isset($request['data']['password']))
            $user->setPassword($request['data']['password']);
        
        if (isset($request['data']['username']))
            $user->setUsername($request['data']['username']);
    
        // Check if username exists...

        $identityRepo = new IdentityRepo(true);
        
        $active = $identityRepo->isActive($user);
        
        if (!$active) {
            $response['status'] = '0';
            $response['statusMsg'] = "user account has not been activated";
            
            return $response;
        }
        
        $passwordMatch = $identityRepo->checkUserPassword($user);        
        
        if ($passwordMatch === true) {
            session_start();
            
            // session_regenerate_id(true); Enable this if we do timeouts for logins or if we are paranoid about session hijacking
            
            array_push($user->sessions, session_id());
            
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                
                $numberOfDays = 30;
                $expirationDate = time() + 60 * 60 * 24 * $numberOfDays;
                
                setcookie(
                    "username",
                    $user->username,
                    $expirationDate,
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
    public function logout($request, $response)
    { 
        $user = new UserModel();
        
        if (isset($_COOKIE['username']))
            $user->setUsername($_COOKIE['username']);

        $identityRepo = new IdentityRepo(true);
        $success = $identityRepo->logout($user);

        // if ($success) {
            session_start();
            
            // Unset all of the session variables.
            $_SESSION = array();

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
            
            // Finally, destroy the session.
            session_destroy();
            
            $response['status'] = "1";
            $response['statusMsg'] = "user logout successful";
            $response['sessiondID'] = session_id();
        // } else {
            $response['status'] = "0";
            $response['statusMsg'] = "user login failed";
        // }

        return $response;
    }
}