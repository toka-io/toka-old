<!--  Chatfeed Popup -->
<?php if (IdentityService::isUserLoggedIn() && !isset($_GET['embed'])) { 
?>   
<div class="modal fade" id="chatfeed" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <iframe src="about:blank"></iframe>
        </div>
    </div>
</div>
<?php
} 
?>