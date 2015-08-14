<!--  Chatfeed Popup -->
<?php if ($identityService->isUserLoggedIn() && !isset($_GET['embed'])) { 
?>   
<div class="modal fade" id="chatfeed" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <iframe src=""></iframe>
        </div>
    </div>
</div>
<?php
} 
?>