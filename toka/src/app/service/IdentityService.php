<?php
require_once(__DIR__ . '/../repo/IdentityRepo.php');
require_once(__DIR__ . '/../model/UserModel.php');

class IdentityService
{
    function __construct()
    {
    }
    
    /*
     * @desc: Creates a new user if the username is valid and available
     */
    public function createUser($response)
    {
        $newUser = new UserModel();
        
        if (isset($_POST['email']))
            $newUser->setEmail($_POST['email']);
        
        if (isset($_POST['password']))
            $newUser->setPassword($_POST['password']);
        
        if (isset($_POST['username']))
            $newUser->setUsername($_POST['username']);
        
        if (!$newUser->isValidUsername()) {
            $response['status'] = "0";
            $response['statusMsg'] = "user information is invalid";
            
            return $response;
        }
        
        $newUser->activateUser();
        $newUser->addSalt();
        
        $identityRepo = new IdentityRepo();
        $success = $identityRepo->createUser($newUser);
        
        if ($success) {
            $response['status'] = "1";
            $response['statusMsg'] = "user created";
        } else {
            $response['status'] = "0";
            $response['statusMsg'] = "create user failed";
        }
        
        return $response;
    }
    
    /*
     * @desc: Deactivates a user
     */
    public function deactivateUser($response)
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

        $identityRepo = new IdentityRepo();
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
    
    /*
     * @desc: Logs a user in and creates a session for the user
     */
    public function login($response)
    {        
        $user = new UserModel();
        
        if (isset($_POST['password']))
            $user->setPassword($_POST['password']);
        
        if (isset($_POST['username']))
            $user->setUsername($_POST['username']);
    
        // Check if username exists...

        $identityRepo = new IdentityRepo();
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
    public function logout($response)
    { 
        $user = new UserModel();
        
        if (isset($_COOKIE['username']))
            $user->setUsername($_COOKIE['username']);

        $identityRepo = new IdentityRepo();
        $success = $identityRepo->logout($user);

        if ($success) {
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
        } else {
            $response['status'] = "0";
            $response['statusMsg'] = "user login failed";
        }

        return $response;
    }
}