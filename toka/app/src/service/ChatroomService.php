<?php
require_once('model/ChatroomModel.php');
require_once('model/UserModel.php');
require_once('repo/IdentityRepo.php');
require_once('repo/ChatroomRepo.php');
require_once('service/IdentityService.php');

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
            $response['status'] = ResponseCode::UNAUTHORIZED;
            $response['message'] = "not allowed to create chatroom";
            
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
        
        if (!$this->isValidChatroomName($newChatroom->chatroomName)) {
            $response['status'] = ResponseCode::BAD_REQUEST;
            $response['message'] = "not valid chatroom title";
            return $response;
        } else if (!$this->isValidCategoryName($newChatroom->categoryName)) {            
            $response['status'] = ResponseCode::BAD_REQUEST;
            $response['message'] = "not valid category";
            return $response;
        } else if (!$this->isValidTags($newChatroom->tags)) {
            $response['status'] = ResponseCode::BAD_REQUEST;
            $response['message'] = "too many tags";
            return $response;
        }
        
        $identityService = new IdentityService(true);
        
        if ($identityService->hasMaxChatrooms($user)) {
            $response['status'] = ResponseCode::INTERNAL_SERVER_ERROR;
            $response['message'] = "user has reached chatroom limit";
            return $response;
        }            
        
        $newChatroom->setChatroomId($this->generateChatroomId());
        $newChatroom->setOwner($user->username);
        
        $chatroomRepo = new ChatroomRepo(true);
        $createChatroomSuccess = $chatroomRepo->createChatroom($newChatroom);
        
        if ($createChatroomSuccess) {
            $response['status'] = ResponseCode::SUCCESS;
            $response['message'] = "chatroom created";
            $response['chatroomId'] = $newChatroom->chatroomId;
        } else {
            $response['status'] = ResponseCode::INTERNAL_SERVER_ERROR;
            $response['message'] = "create chatroom failed";
        }
        
        return $response;
    }
    
    public function generateChatroomId()
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-";
        $charactersLength = strlen($characters);
        $randomString = "";
        for ($i = 0; $i < 11; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    /*
     * @note:
     */
    public function getChatroom($request, $response)
    {
        $chatroom = new ChatroomModel();
    
        if (isset($request['data']['chatroomId']))
            $chatroom->setChatroomId($request['data']['chatroomId']);
    
        $chatroomRepo = new ChatroomRepo(false);
    
        $chatroom = $chatroomRepo->getChatroomById($chatroom->chatroomId);
    
        if (!isset($data['error'])) {
            $response['status'] = ResponseCode::SUCCESS;
            $response['message'] = "chatroom " . $chatroom->chatroomId . " retrieved";
            $response['data'] = $chatroom;
        } else {
            $response['status'] = ResponseCode::INTERNAL_SERVER_ERROR;
            $response['message'] = "chatroom " . $chatroom->chatroomId . " could not be retrieved";
        }
    
        return $response;
    }
    
    public function getChatroomsByOwner($user)
    {
        $chatroomRepo = new ChatroomRepo(false);
    
        $chatrooms = $chatroomRepo->getChatroomsByOwner($user);
    
        return $chatrooms;
    }
    
    public function getChatroomType($chatroomId) {
        
        return;
    }
    
    public function isValidCategoryName($categoryName)
    {
        return $categoryName !== "";
    }
    
    public function isValidChatroomName($chatroomName)
    {
        $len = strlen($chatroomName);
    
        return $len > 0  && $len <= 100;
    }
    
    public function isValidTags($tags)
    {
        return count($tags) <= 5;
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
            $response['status'] = ResponseCode::UNAUTHORIZED;
            $response['message'] = "not allowed to mod users";
    
            return $response;
        }
        
        $userToMod = new UserModel();
        
        if (isset($request['data']['userToMod']))
            $userToMod->setUsername($request['data']['userToMod']);
    
        $chatroom = new ChatroomModel();
    
        if (isset($request['data']['chatroomId']))
            $chatroom->setChatroomId($request['data']['chatroomId']);
         
        $chatroomRepo = new ChatroomRepo(true);
        $updateChatroomSuccess = $chatroomRepo->addMod($chatroom, $userToMod);
    
        if ($updateChatroomSuccess) {
            $response['status'] = ResponseCode::SUCCESS;
            $response['message'] = $userToMod->username . " was modded in chatroom " . $chatroom->chatroomId;
        } else {
            $response['status'] = ResponseCode::INTERNAL_SERVER_ERROR;
            $response['message'] = $userToMod->username . " could not be modded in chatroom " . $chatroom->chatroomId;
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
            $response['status'] = ResponseCode::UNAUTHORIZED;
            $response['message'] = "not allowed to unmod user";
    
            return $response;
        }
        
        $userToUnmod = new UserModel();
        
        if (isset($request['data']['userToUnmod']))
            $userToUnmod->setUsername($request['data']['userToUnmod']);
    
        $chatroom = new ChatroomModel();
    
        if (isset($request['data']['chatroomId']))
            $chatroom->setChatroomId($request['data']['chatroomId']);
    
        $chatroomRepo = new ChatroomRepo(true);
        $addUserSuccess = $chatroomRepo->removeUser($chatroom, $userToUnmod);
    
        if ($addUserSuccess) {
            $response['status'] = ResponseCode::SUCCESS;
            $response['message'] = $userToUnmod->username . " was unmodded in chatroom " . $chatroom->chatroomId;
        } else {
            $response['status'] = ResponseCode::INTERNAL_SERVER_ERROR;
            $response['message'] = $userToUnmod->username . " could not be unmodded in chatroom " . $chatroom->chatroomId;
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
            $response['status'] = ResponseCode::UNAUTHORIZED;
            $response['message'] = "not allowed to update chatroom";
            
            return $response;
        }
    
        $chatroom = new ChatroomModel();
        
        if (isset($request['data']['categoryName']))
            $chatroom->setCategoryName($request['data']['categoryName']);
        
        if (isset($request['data']['chatroomId']))
            $chatroom->setChatroomId($request['data']['chatroomId']);
        
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
        
        if (!$this->isValidChatroomName($chatroom->chatroomName)) {
            $response['status'] = ResponseCode::BAD_REQUEST;
            $response['message'] = "not valid chatroom title";
            return $response;
        } else if (!$this->isValidCategoryName($chatroom->categoryName)) {            
            $response['status'] = ResponseCode::BAD_REQUEST;
            $response['message'] = "not valid category";
            return $response;
        } else if (!$this->isValidTags($chatroom->tags)) {
            $response['status'] = ResponseCode::BAD_REQUEST;
            $response['message'] = "too many tags";
            return $response;
        }
        
        $chatroomRepo = new ChatroomRepo(true);
        $updateChatroomSuccess = $chatroomRepo->updateChatroom($chatroom);

        if ($updateChatroomSuccess) {
            $response['status'] = ResponseCode::SUCCESS;
            $response['message'] = "chatroom updated";
            $response['chatroomId'] = $chatroom->chatroomId;
        } else {
            $response['status'] = ResponseCode::INTERNAL_SERVER_ERROR;
            $response['message'] = "update chatroom failed";
        }
    
        return $response;
    }
}