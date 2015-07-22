<?php
/**
 * @desc: This file provides global session-based objects for all view pages
 */

require_once(__DIR__ . '/../../../model/ChatroomModel.php');

require_once(__DIR__ . '/../../../service/IdentityService.php');

$identityService = new IdentityService();

if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);

    // move this logic to getUserSession()!!
    $data = $identityService->getChatroomsByOwner($user); // Get chatrooms owned by user
    $hasMaxChatroom = $identityService->hasMaxChatrooms($user); // Can user create more chatrooms?
    $hasChatroom = false; // Does user have a chatroom?
    $userChatroom = new ChatroomModel();
    
    if (!empty($data)) {
        $mongoObj = $data["0"];
        $userChatroom->bindMongo($mongoObj);
        $hasChatroom = true;
    }
}