<?php
// @model
require_once(__DIR__ . '/../model/ChatroomModel.php');
require_once(__DIR__ . '/../model/UserModel.php');

// @repo
require_once(__DIR__ . '/../repo/IdentityRepo.php');
require_once(__DIR__ . '/../repo/ChatroomRepo.php');

// @service
require_once(__DIR__ . '/../service/IdentityService.php');

/*
 * @note: Should we check whether a user exists when making the request? Double check...
 */
class ChatroomService
{
    function __construct()
    {
    }
    
    /*
     * @note: Should we validate if the category exists? Double check...
     */
    public function createChatroom($request, $response)
    {
        $user = new UserModel();
        
        if (isset($_COOKIE['username']))
            $user->setUsername($_COOKIE['username']);
        
        $isLoggedIn = !empty($user->username);
        
        if (!$isLoggedIn) {
            $response['status'] = "0";
            $response['statusMsg'] = "not allowed to create chatroom";
            
            return $response;
        }
        
        $newChatroom = new ChatroomModel();
        
        if (isset($request['data']['categoryName']))
            $newChatroom->setCategoryName($request['data']['categoryName']);
        
        if (isset($request['data']['chatroomName']))
            $newChatroom->setChatroomName($request['data']['chatroomName']);
        
        if (isset($request['data']['chatroomType']))
            $newChatroom->setChatroomType($request['data']['chatroomType']);
        
        if (isset($request['data']['guesting']))
            $newChatroom->setGuesting($request['data']['guesting']);
        
        if (isset($request['data']['info']))
            $newChatroom->setInfo($request['data']['info']);
        
        if (isset($request['data']['maxSize']))
            $newChatroom->setMaxSize($request['data']['maxSize']);
        
        if (isset($request['data']['tags']))
            $newChatroom->setTags($request['data']['tags']);
        
        if (!$newChatroom->isValidChatroomName()) {
            $response['status'] = "0";
            $response['statusMsg'] = "not valid chatroom title";
            return $response;
        } else if (!$newChatroom->isValidCategoryName()) {            
            $response['status'] = "0";
            $response['statusMsg'] = "not valid category";
            return $response;
        } else if (!$newChatroom->isValidTags()) {
            $response['status'] = "0";
            $response['statusMsg'] = "too many tags";
            return $response;
        }
        
        $identityService = new IdentityService(true);
        
        if ($identityService->hasMaxChatrooms($user)) {
            $response['status'] = "0";
            $response['statusMsg'] = "user has reached chatroom limit";
            return $response;
        }            
        
        $newChatroom->generateChatroomID();
        $newChatroom->setOwner($user->username);
        
        $chatroomRepo = new ChatroomRepo(true);
        $createChatroomSuccess = $chatroomRepo->createChatroom($newChatroom);
        
        if ($createChatroomSuccess) {
            $response['status'] = '1';
            $response['statusMsg'] = "chatroom created";
            $response['chatroomID'] = $newChatroom->chatroomID;
        } else {
            $response['status'] = '0';
            $response['statusMsg'] = "create chatroom failed";
        }
        
        return $response;
    }
    
    /*
     * @note: DEPRECATED, handled by chata
     */
    public function enterChatroom($request, $response)
    {    
        $user = new UserModel();
        
        if (isset($_COOKIE['username']))
            $user->setUsername($_COOKIE['username']);
        
        $isLoggedIn = !empty($user->username);
        
        if (!$isLoggedIn) {
            $response['status'] = "0";
            $response['statusMsg'] = "not allowed to enter chatroom";
            
            return $response;
        }
        
        $chatroom = new ChatroomModel();
        
        if (isset($request['data']['chatroomID']))
            $chatroom->setChatroomID($request['data']['chatroomID']);
    
        $chatroomRepo = new ChatroomRepo(true);
        $addUserSuccess = $chatroomRepo->addUser($chatroom, $user);

        $identityRepo = new IdentityRepo(true);
        $addChatroomSuccess = $identityRepo->addChatroom($user, $chatroom);

        if ($addUserSuccess && $addChatroomSuccess) {
            $response['status'] = "1";
            $response['statusMsg'] = $user->username . " entered chatroom " . $chatroom->chatroomID;
        } else {
            $response['status'] = "0";
            $response['statusMsg'] = $user->username . " unable to enter chatroom";
        }
    
        return $response;
    }
    
    /*
     * @note:
     */
    public function getChatroom($request, $response)
    {
        $chatroom = new ChatroomModel();
    
        if (isset($request['data']['chatroomID']))
            $chatroom->setChatroomID($request['data']['chatroomID']);
    
        $chatroomRepo = new ChatroomRepo(false);
    
        $data = array();
        $data = $chatroomRepo->getChatroomByID($chatroom->chatroomID);
    
        if (!isset($data['error'])) {
            $response['status'] = "1";
            $response['statusMsg'] = "chatroom " . $chatroom->chatroomID . " retrieved";
            $response['data'] = $data;
        } else {
            $response['status'] = "0";
            $response['statusMsg'] = "chatroom " . $chatroom->chatroomID . " could not be retrieved";
        }
    
        return $response;
    }
    
    public function getChatroomIDFromUrl($url)
    {
        if(preg_match("/\/([a-zA-Z0-9-_]+)$/", $url, $matches))
            return $matches[1];
        else
            return NULL;
    }
    
    /*
     * @note: DEPRECATED, handled by chata
     */
    public function leaveChatroom($request, $response)
    {
        $user = new UserModel();
        
        if (isset($_COOKIE['username']))
            $user->setUsername($_COOKIE['username']);
        
        $isLoggedIn = !empty($user->username);
        
        if (!$isLoggedIn) {
            $response['status'] = "0";
            $response['statusMsg'] = "not allowed to leave chatroom--wait, how did you even enter one in the first place??";
            
            return $response;
        }
    
        $chatroom = new ChatroomModel();
        
        if (isset($request['data']['chatroomID']))
            $chatroom->setChatroomID($request['data']['chatroomID']);
    
        $chatroomRepo = new ChatroomRepo(true);
        $addUserSuccess = $chatroomRepo->removeUser($chatroom, $user);

        $identityRepo = new IdentityRepo(true);
        $addChatroomSuccess = $identityRepo->removeChatroom($user, $chatroom);

        if ($addUserSuccess && $addChatroomSuccess) {
            $response['status'] = "1";
            $response['statusMsg'] = $user->username . " left chatroom " . $chatroom->chatroomID;
        } else {
            $response['status'] = "0";
            $response['statusMsg'] = $user->username . " unable to leave chatroom";
        }
    
        return $response;
    }
    
    /*
     * @note: 
     */
    public function modUser($request, $response)
    {
        $user = new UserModel();
        
        if (isset($_COOKIE['username']))
            $user->setUsername($_COOKIE['username']);
    
        $isLoggedIn = !empty($user->username);
    
        if (!$isLoggedIn) {
            $response['status'] = "0";
            $response['statusMsg'] = "not allowed to mod users";
    
            return $response;
        }
        
        $userToMod = new UserModel();
        
        if (isset($request['data']['userToMod']))
            $userToMod->setUsername($request['data']['userToMod']);
    
        $chatroom = new ChatroomModel();
    
        if (isset($request['data']['chatroomID']))
            $chatroom->setChatroomID($request['data']['chatroomID']);
         
        $chatroomRepo = new ChatroomRepo(true);
        $updateChatroomSuccess = $chatroomRepo->addMod($chatroom, $userToMod);
    
        if ($updateChatroomSuccess) {
            $response['status'] = "1";
            $response['statusMsg'] = $userToMod->username . " was modded in chatroom " . $chatroom->chatroomID;
        } else {
            $response['status'] = "0";
            $response['statusMsg'] = $userToMod->username . " could not be modded in chatroom " . $chatroom->chatroomID;
        }
    
        return $response;
    }
    
    /*
     * @note: 
     */
    public function unmodUser($request, $response)
    {
        $user = new UserModel();
    
        if (isset($_COOKIE['username']))
            $user->setUsername($_COOKIE['username']);
    
        $isLoggedIn = !empty($user->username);
    
        if (!$isLoggedIn) {
            $response['status'] = "0";
            $response['statusMsg'] = "not allowed to unmod user";
    
            return $response;
        }
        
        $userToUnmod = new UserModel();
        
        if (isset($request['data']['userToUnmod']))
            $userToUnmod->setUsername($request['data']['userToUnmod']);
    
        $chatroom = new ChatroomModel();
    
        if (isset($request['data']['chatroomID']))
            $chatroom->setChatroomID($request['data']['chatroomID']);
    
        $chatroomRepo = new ChatroomRepo(true);
        $addUserSuccess = $chatroomRepo->removeUser($chatroom, $userToUnmod);
    
        if ($addUserSuccess) {
            $response['status'] = "1";
            $response['statusMsg'] = $userToUnmod->username . " was unmodded in chatroom " . $chatroom->chatroomID;
        } else {
            $response['status'] = "0";
            $response['statusMsg'] = $userToUnmod->username . " could not be unmodded in chatroom " . $chatroom->chatroomID;
        }
    
        return $response;
    }
    
    /*
     * @note: If guesting and max size are somehow missing or set incorrectly, the default values will be applied
     */
    public function updateChatroom($request, $response)
    {
        $user = new UserModel();
        
        if (isset($_COOKIE['username']))
            $user->setUsername($_COOKIE['username']);
        
        $isLoggedIn = !empty($user->username);
        
        if (!$isLoggedIn) {
            $response['status'] = "0";
            $response['statusMsg'] = "not allowed to update chatroom";
            
            return $response;
        }
    
        $chatroom = new ChatroomModel();
        
        if (isset($request['data']['categoryName']))
            $chatroom->setCategoryName($request['data']['categoryName']);
        
        if (isset($request['data']['chatroomID']))
            $chatroom->setChatroomID($request['data']['chatroomID']);
        
        if (isset($request['data']['chatroomName']))
            $chatroom->setChatroomName($request['data']['chatroomName']);
        
        if (isset($request['data']['chatroomType']))
            $chatroom->setChatroomType($request['data']['chatroomType']);
        
        if (isset($request['data']['guesting']))
            $chatroom->setGuesting($request['data']['guesting']);
        
        if (isset($request['data']['info']))
            $chatroom->setInfo($request['data']['info']);
        
        if (isset($request['data']['maxSize']))
            $chatroom->setMaxSize($request['data']['maxSize']);
        
        if (isset($request['data']['tags']))
            $chatroom->setTags($request['data']['tags']);
        
        if (!$chatroom->isValidChatroomName()) {
            $response['status'] = "0";
            $response['statusMsg'] = "not valid chatroom title";
            return $response;
        } else if (!$chatroom->isValidCategoryName()) {            
            $response['status'] = "0";
            $response['statusMsg'] = "not valid category";
            return $response;
        } else if (!$chatroom->isValidTags()) {
            $response['status'] = "0";
            $response['statusMsg'] = "too many tags";
            return $response;
        }
        
        $chatroomRepo = new ChatroomRepo(true);
        $updateChatroomSuccess = $chatroomRepo->updateChatroom($chatroom);

        if ($updateChatroomSuccess) {
            $response['status'] = "1";
            $response['statusMsg'] = "chatroom updated";
            $response['chatroomID'] = $chatroom->chatroomID;
        } else {
            $response['status'] = "0";
            $response['statusMsg'] = "update chatroom failed";
        }
    
        return $response;
    }
}