<?php 
class TestController extends Controller
{
    public function get($request, $response) {
        $match = array();

        if (RequestMapping::map('test\/wss', $request['uri'], $match)) {
            $request['data']['chatroomId'] = 'test';
            
            $response = ChatroomService::getChatroom($request, $response);
            $chatroom = $response['data'];
            
            $chatroom->chatroomId = $match[1];
            $chatroom->chatroomType = Chatroom::CHATROOM_TYPE_NORMAL;
            
            if (empty($chatroom->chatroomName)) {
                $userExists = IdentityService::userExists($chatroom->chatroomId);
            
                if ($userExists) {
                    $chatroom->chatroomName = "@" . $chatroom->chatroomId;
                    $chatroom->chatroomType = Chatroom::CHATROOM_TYPE_USER;
                } else {
                    $chatroom->chatroomName = "#" . $chatroom->chatroomId;
                    $chatroom->chatroomType = Chatroom::CHATROOM_TYPE_HASHTAG;
                }
            }
            
            $settings = array();
            if (IdentityService::isUserLoggedIn()) {
                $user =  unserialize($_SESSION['user']);;
                IdentityService::updateRecentRooms($user->username, $chatroom);
                $settings = SettingsService::getUserSettingsByUsername($user->username);
                $_SESSION['user'] = serialize(IdentityService::getUserSession());
            }
            
            $metadataCache = MetadataService::getMetadataArchive(100);
            include("internal/test/wss-test.php");
        }
        else if (RequestMapping::map('test\/bot', $request['uri'], $match))
            include("internal/test/bot-test.php");
        else
            parent::redirect404();      
    }
}