<?php
if ($identityService->isUserLoggedIn($user) && $user->username === $chatroom->owner) {
?>
    <div id="chatroom-title-update-chatroom">
        <div data-toggle="tooltip" data-original-title="Update Chatroom">
            <div id="chatroom-update-chatroom-icon" data-toggle="modal" data-target="#update-chatroom-form">
                <img src="/assets/images/icons/settings.svg" class="img-responsive" />
            </div>
        </div>
    </div>
<?php 
}
