<?php
require_once('model/UserModel.php');
require_once('repo/IdentityRepo.php');
require_once('repo/ChatroomRepo.php');
require_once('service/EmailService.php');

class IdentityService
{
    const MAX_CHATROOMS = 1;
    
    /*
     * @desc: Activate a user
     */
    public static function activateUser($request, $response)
    {
        $user = new UserModel();
        
        $user->setUsername($request['login']);
        $user->setVerificationCode($request['vCode']);
    
        $identityRepo = new IdentityRepo(true);
        $exists = $identityRepo->isUser($user);
        
        if (!$exists) {
            $response['status'] = ResponseCode::NOT_FOUND;
            $response['message'] = "user does not exist";
    
            return $response;
        }
        
        $validVCode = $identityRepo->isValidVerificationCode($user);
        
        if ($validVCode) {
            $success = $identityRepo->activateUser($user);
        
            if ($success) {
                $response['status'] = ResponseCode::SUCCESS;
                $response['message'] = "user activated";
            } else {
                $response['status'] = ResponseCode::INTERNAL_SERVER_ERROR;
                $response['message'] = "activate user failed";
            }
        } else {
            $response['status'] = ResponseCode::UNAUTHORIZED;
            $response['message'] = "verification code is invalid";
        }
    
        return $response;
    }
    
    /*
     * @desc: Creates a new user if the username is valid and available
     */
    public static function createUser($request, $response)
    {
        $newUser = new UserModel();        
        $newUser->setDisplayName($request['username']);
        $newUser->setEmail($request['email']);
        $newUser->setPassword($request['password']);
        $newUser->addSalt();
        $newUser->setUsername($request['username']);
        
        if (!self::isValidUsername($newUser->username)) {
            $response['status'] = ResponseCode::BAD_REQUEST;
            $response['message'] = "user information is invalid";
            $response['displayMessage'] = "Bad request. Please talk to support if this issues continues.";
            
            return $response;
        } else if (!self::isValidEmail($newUser->email)) {
            $response['status'] = ResponseCode::BAD_REQUEST;
            $response['message'] = "user information is invalid";
            $response['displayMessage'] = "Bad request. Please talk to support if this issues continues.";
        
            return $response;
        }
        
        $identityRepo = new IdentityRepo(true);
        $usernameAvailable = $identityRepo->isUsernameAvailable($newUser->username);
        $emailAvailable = $identityRepo->isEmailAvailable($newUser->email);
        
        if (!$usernameAvailable) {
            
            $response['status'] = ResponseCode::INTERNAL_SERVER_ERROR;
            $response['message'] = "username is not available";
            $response['displayMessage'] = "Username is not available.";
            
        } else if (!$emailAvailable) {
            
            $response['status'] = ResponseCode::INTERNAL_SERVER_ERROR;
            $response['message'] = "email is not available";
            $response['displayMessage'] = "Email is not available.";
            
        } else {        
            $vCode = KeyGen::getRandomKey(12);
            $newUser->setVerificationCode($vCode);
            $success = $identityRepo->createUser($newUser);
        
            if ($success) {
                EmailService::sendSignupVerificationEmail($newUser);
                
                $response['status'] = ResponseCode::SUCCESS;
                $response['message'] = "user created";
                $response['displayMessage'] = "A verification email has been sent!";
            } else {
                $response['status'] = ResponseCode::INTERNAL_SERVER_ERROR;
                $response['message'] = "create user failed";
                $response['displayMessage'] = "An occured while signing up, please try again.";
            }
        }        
        
        return $response;
    }
    
    /*
     * @desc: Deactivates a user
     */
    public static function deactivateUser($request, $response)
    {
        $user = new UserModel();
        
        if (isset($_COOKIE['username']))
            $user->setUsername($_COOKIE['username']);
        
        $isLoggedIn = !empty($user->username);
        
        if (!$isLoggedIn) {
            $response['status'] = ResponseCode::UNAUTHORIZED;
            $response['message'] = "not allowed to deactivate user";
            
            return $response;
        }

        $identityRepo = new IdentityRepo(true);
        $success = $identityRepo->deactivateUser($user);

        if ($success) {
            $response['status'] = ResponseCode::SUCCESS;
            $response['message'] = "user deactivated";
        } else {
            $response['status'] = ResponseCode::INTERNAL_SERVER_ERROR;
            $response['message'] = "deactivate user failed";
        }
            
        return $response;
    }
    
    public static function getRecentRoomsByUsername($username) {
        $identityRepo = new IdentityRepo(false);
        $reversed = array_reverse($identityRepo->getRecentRoomsByUsername($username));
        
        return $reversed;
    }
    
    public static function getUserSession() 
    {
        $user = new UserModel();
        
        if (isset($_COOKIE['username']))
            $user->setUsername($_COOKIE['username']);
        
        $user->chatrooms = ChatroomService::getChatroomsByOwner($user); // Get chatrooms owned by user
        $user->hasMaxChatrooms = self::hasMaxChatrooms($user); // Can user create more chatrooms?
        $user->hasChatrooms = false; // Does user have a chatroom?
        $user->recentRooms = self::getRecentRoomsByUsername($user->username);        
        
        if (!empty($user->chatrooms)) {
            $user->homeChatroom = Model::mapToObject(new ChatroomModel(), $user->chatrooms["0"]);
            $user->hasChatrooms = true;
        }
        
        return $user;
    }
    
    /*
     * $user: UserModel
     */
    public static function hasMaxChatrooms($user)
    {
        $chatroomService = new ChatroomService();
        $count = count($chatroomService->getChatroomsByOwner($user));
        
        return $count >= self::MAX_CHATROOMS;
    }
    
    public static function isUserLoggedIn()
    {
        return isset($_COOKIE['sessionID']) && isset($_COOKIE['username']);
    }
    
    /*
     * $user: string
     */
    public static function isUsernameAvailable($username) 
    {        
        $identityRepo = new IdentityRepo(false);
        
        $usernameAvailable = $identityRepo->isUsernameAvailable($username);
        
        return $usernameAvailable;
    }
    
    public static function isValidEmail($email)
    {
        $val = preg_match("/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/", $email);
    
        return ($val === 1) ? true: false;
    }
    
    /*
     * @desc: Enforce password strength
     */
    public static function isValidPassword($password)
    {
        return strlen($password) >= 5;
    }
    
    public static function isValidUsername($username)
    {
        $val = preg_match("/^[a-z0-9_]{3,25}$/", $username);
    
        return ($val === 1) ? true : false;
    }
    
    /*
     * @desc: Logs a user in and creates a session for the user
     * $data: array
     * $response: array
     */
    public static function login($data, $response)
    {        
        $user = new UserModel();        
        $user->setPassword($data['password']);
        $user->setUsername($data['username']);
    
        // Check if user has been activated
        $identityRepo = new IdentityRepo(true);        
        $active = $identityRepo->isActive($user);
        
        if (!$active) {
            $response['status'] = ResponseCode::UNAUTHORIZED;
            $response['message'] = "user account has not been activated";
            
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
                $response['status'] = ResponseCode::SUCCESS;
                $response['message'] = "user login successful";
                $response['sessionID'] = session_id();
            } else {
                $response['status'] = ResponseCode::INTERNAL_SERVER_ERROR;
                $response['message'] = "user login failed";
            }
            
        } else {
            $response['status'] = ResponseCode::UNAUTHORIZED;
            $response['message'] = "username does not exist or password is incorrect";
        }
    
        return $response;
    }
    
    /*
     * @desc: Logs a user out and destroys all sessions
     */
    public static function logout()
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
    
    public static function recoverPassword($request, $response)
    {    
        $email = isset($request['email']) ? $request['email'] : "";
        $username = isset($request['username']) ? $request['username'] : "";
    
        $identityRepo = new IdentityRepo(true);
        $usernameAvailable = $identityRepo->isUsernameAvailable($username);
        $emailAvailable = $identityRepo->isEmailAvailable($email);
    
        if ($usernameAvailable && $emailAvailable) {
    
            $response['status'] = ResponseCode::NOT_FOUND;
            $response['message'] = "username or email does not exist";
            $response['displayMessage'] = "Username or email does not exist!";
            
        } 
        else {        
            $vCode = KeyGen::getRandomKey(12);
            
            if (empty($email))
                $email = $identityRepo->getEmailByUsername($username);
            if (empty($username))
                $username = $identityRepo->getUsernameByEmail($email);
            
            $identityRepo->updatePasswordVCode($email, $vCode);
            
            EmailService::sendPasswordRecoveryEmail($username, $email, $vCode);
            
            $response['status'] = ResponseCode::SUCCESS;
            $response['message'] = "password recovery email sent";
            $response['displayMessage'] = "An email has been sent with the instructions to reset your password.";
        }
    
        return $response;
    }
    
    public static function resetPassword($request, $response)
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
                    'status' => ResponseCode::UNAUTHORIZED,
                    'message' => 'invalid verficiation code',
                    'displayMessage' => 'Invalid password reset request.'
            );
        }
        else if ($min > 120) {
            $response = array(
                    'status' => ResponseCode::INTERNAL_SERVER_ERROR,
                    'message' => 'expired',
                    'displayMessage' => 'The password recovery request has expired.'
            );
        }
        else {
            
            $user->salt = $userOld->salt;
            $user->addSalt();            
            
            $success = $identityRepo->updatePassword($user);
            if ($success) {
                $response = array(
                    'status' => ResponseCode::SUCCESS,
                    'message' => 'success',
                    'displayMessage' => 'Password has been reset successfully.'
                );
            }
            else {
                $response = array(
                    'status' => ResponseCode::INTERNAL_SERVER_ERROR,
                    'message' => 'database error',
                    'displayMessage' => 'The server could not process the request at this time. Please try again.'
                );
            }            
            
        }
    
        return $response;
    }
    
    public static function updateRecentRooms($username, $chatroom)
    {           
        $identityRepo = new IdentityRepo(true);
        $room = array();
        switch ($chatroom->chatroomType) {
            case ChatroomModel::CHATROOM_TYPE_NORMAL:
                $room['name'] = $chatroom->chatroomName;
                break;
            default:
                $room['name'] = $chatroom->chatroomId;
        }
        $room['link'] = $chatroom->chatroomId;        
    
        return $identityRepo->updateRecentRooms($username, $room);
    }
    
    public static function userExists($username)
    {
        $identityRepo = new IdentityRepo(false);
        $usernameAvailable = $identityRepo->isUsernameAvailable($username);
    
        return !$usernameAvailable;
    }
    
    public static function validatePasswordRecoveryRequest($request) 
    {        
        if (isset($request['login']) && isset($request['vCode'])) {
            $username = $request['login'];
            $vCode = $request['vCode'];
        }
        else
            return array(
                        'status' => ResponseCode::BAD_REQUEST,
                        'message' => 'invalid request',
                        'displayMessage' => 'Missing request parameters.'
                   );
        
        $result = array();
            
        $identityRepo = new IdentityRepo(false);        
        $document = $identityRepo->getPasswordVCodeByUsername($username);
        
        if (!empty($document)) {
            
            if ($vCode != $document['passwordVCode']['code'])
                return array(
                            'status' => ResponseCode::UNAUTHORIZED,
                            'message' => 'invalid code',
                            'displayMessage' => 'The verficiation code is invalid.'
                       );
            
            $timestamp = TimeUtility::convertMongoDateToDate($document['passwordVCode']['createdDate']);
            $min = TimeUtility::getMinuteDifferenceFromNow($timestamp);
            
            if ($min <= 120) {
                $result = array(
                        'status' => ResponseCode::SUCCESS,
                        'message' => 'valid'
                );
            }
            else {
                $result = array(
                        'status' => ResponseCode::INTERNAL_SERVER_ERROR,
                        'message' => 'expired',
                        'displayMessage' => 'The password recovery request has expired.'
                );
            }
        } else {
            $result = array(
                    'status' => ResponseCode::INTERNAL_SERVER_ERROR,
                    'message' => 'The user does not exist or toka broke!'
            );
        }            
        
        return $result;
    }
}