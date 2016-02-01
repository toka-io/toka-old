<?php
$tokadownService = new TokadownService();
echo (!empty(trim($chatroom->info))) ? $tokadownService->render($chatroom->info) : $tokadownService->render("#Welcome to hashtag rooms!\n This chatroom behaves like any other chatroom but with no owner. Have fun chatting :3"); 