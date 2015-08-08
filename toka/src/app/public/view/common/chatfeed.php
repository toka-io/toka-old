<!--  Chatfeed Popup -->
<?php if ($identityService->isUserLoggedIn()) { 
?>   
<div class="modal fade" id="chatfeed" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <iframe src="/chatroom/<?php echo $user->username; ?>?embed=1&target=_blank"></iframe>
        </div>
    </div>
</div>
<?php
} 
?>