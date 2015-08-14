<?php
// @model
require_once('model/UserModel.php');

// @repo
require_once('repo/IdentityRepo.php');
require_once('repo/ChatroomRepo.php');

// @service
require_once('service/EmailService.php');

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
        $newUser->setDisplayName($request['username']);
        $newUser->setEmail($request['email']);
        $newUser->setPassword($request['password']);
        $newUser->setUsername($request['username']);
        
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
        $usernameAvailable = $identityRepo->isUsernameAvailable($newUser->username);
        $emailAvailable = $identityRepo->isEmailAvailable($newUser->email);
        
        if (!$usernameAvailable) {
            
            $response['status'] = "0";
            $response['statusMsg'] = "username is not available";
            
        } else if (!$emailAvailable) {
            
            $response['status'] = "0";
            $response['statusMsg'] = "email is not available";
            
        } else {        
            $vCode = generateVCode();
            $newUser->setVerificationCode($vCode);
            $success = $identityRepo->createUser($newUser);
        
            if ($success) {
                $emailService = new EmailService();
                $emailService->sendSignupVerificationEmail($newUser);
                
                $response['status'] = "1";
                $response['statusMsg'] = "user created";
                $response['displayMsg'] = "A verification email has been sent!";
            } else {
                $response['status'] = "0";
                $response['statusMsg'] = "create user failed";
            }
        }        
        
        return $response;
    }
    
    public function checkUserExists($user) 
    {        
        $identityRepo = new IdentityRepo(false);
        $usernameAvailable = $identityRepo->isUsernameAvailable($user);
        
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
        $newUser = new UserModel();
        $newUser->setUsername($username);
        
        $identityRepo = new IdentityRepo(false);
        
        $usernameAvailable = $identityRepo->isUsernameAvailable($newUser);
        
        return $usernameAvailable;
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
    
            $response['status'] = "0";
            $response['statusMsg'] = "username or email does not exist!";
            
        } 
        else {        
            $vCode = $this->generateVCode();    
            
            $emailService = new EmailService();
            $emailService->sendPasswordRecoveryEmail($username, $email, $vCode);
        }
    
        return $response;
    }
    
    public function resetPassword($request, $response)
    {
        $email = isset($request['email']) ? $request['email'] : "";
        $username = isset($request['username']) ? $request['username'] : "";
        $vCode = isset($request['vCode']) ? $request['vCode'] : "";
    
        $identityRepo = new IdentityRepo(true);
        $usernameAvailable = $identityRepo->isUsernameAvailable($username);
        $emailAvailable = $identityRepo->isEmailAvailable($email);
    
        if ($usernameAvailable && $emailAvailable) {
    
            $response['status'] = "0";
            $response['statusMsg'] = "username or email does not exist!";
    
        }
        else {
            $vCode = $this->generateVCode();
    
            $emailService = new EmailService();
            $emailService->sendPasswordRecoveryEmail($username, $email, $vCode);
        }
    
        return $response;
    }
}