<?php
// @model
require_once('../../model/ChatroomModel.php');
require_once('../../model/UserModel.php');

// @repo
require_once('../../repo/IdentityRepo.php');
require_once('../../repo/ChatroomRepo.php');

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
    public function createChatroom($response)
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
        
        if (isset($_POST['categoryName']))
            $newChatroom->setCategoryName($_POST['categoryName']);
        
        if (isset($_POST['chatroomName']))
            $newChatroom->setChatroomName($_POST['chatroomName']);
        
        if (isset($_POST['chatroomType']))
            $newChatroom->setChatroomType($_POST['chatroomType']);
        
        if (isset($_POST['guesting']))
            $newChatroom->setGuesting($_POST['guesting']);
        
        if (isset($_POST['maxSize']))
            $newChatroom->setMaxSize($_POST['maxSize']);
        
        $newChatroom->setOwner($user->username);
            
        $chatroomRepo = new ChatroomRepo();
        $createChatroomSuccess = $chatroomRepo->createChatroom($newChatroom);
        
        $identityRepo = new IdentityRepo();
        $addChatroomSuccess = $identityRepo->addChatroom($user, $newChatroom);
        
        if ($createChatroomSuccess && $addChatroomSuccess) {
            $response['status'] = '1';
            $response['statusMsg'] = "chatroom created";
        } else {
            $response['status'] = '0';
            $response['statusMsg'] = "create chatroom failed";
        }
        
        return $response;
    }
    
    /*
     * @note: You do not need to be a user to enter chatrooms, but if you are, we need to add
     * the user to the lists on both ends...
     * also, add guesting later...
     */
    public function enterChatroom($response)
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
        
        if (isset($_POST['chatroomID']))
            $chatroom->setChatroomID($_POST['chatroomID']);
    
        $chatroomRepo = new ChatroomRepo();
        $addUserSuccess = $chatroomRepo->addUser($chatroom, $user);

        $identityRepo = new IdentityRepo();
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
     * @note: You do not need to be a user to leave chatrooms, but if you are, we need to add
     * the user to the lists on both ends...
     */
    public function leaveChatroom($response)
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
        
        if (isset($_POST['chatroomID']))
            $chatroom->setChatroomID($_POST['chatroomID']);
    
        $chatroomRepo = new ChatroomRepo();
        $addUserSuccess = $chatroomRepo->removeUser($chatroom, $user);

        $identityRepo = new IdentityRepo();
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
    public function modUser($response)
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
        
        if (isset($_POST['userToMod']))
            $userToMod->setUsername($_POST['userToMod']);
    
        $chatroom = new ChatroomModel();
    
        if (isset($_POST['chatroomID']))
            $chatroom->setChatroomID($_POST['chatroomID']);
         
        $chatroomRepo = new ChatroomRepo();
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
    public function unmodUser($response)
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
        
        if (isset($_POST['userToUnmod']))
            $userToUnmod->setUsername($_POST['userToUnmod']);
    
        $chatroom = new ChatroomModel();
    
        if (isset($_POST['chatroomID']))
            $chatroom->setChatroomID($_POST['chatroomID']);
    
        $chatroomRepo = new ChatroomRepo();
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
    public function updateChatroom($response)
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
        
        if (isset($_POST['chatroomID']))
            $chatroom->setChatroomID($_POST['chatroomID']);
        
        if (isset($_POST['chatroomName']))
            $chatroom->setChatroomName($_POST['chatroomName']);
        
        if (isset($_POST['chatroomType']))
            $chatroom->setChatroomType($_POST['chatroomType']);
        
        if (isset($_POST['guesting']))
            $chatroom->setGuesting($_POST['guesting']);
        
        if (isset($_POST['maxSize']))
            $chatroom->setMaxSize($_POST['maxSize']);
       
        $chatroomRepo = new ChatroomRepo();
        $updateChatroomSuccess = $chatroomRepo->updateChatroom($chatroom);

        if ($updateChatroomSuccess) {
            $response['status'] = "1";
            $response['statusMsg'] = "chatroom updated";
        } else {
            $response['status'] = "0";
            $response['statusMsg'] = "update chatroom failed";
        }
    
        return $response;
    }
}