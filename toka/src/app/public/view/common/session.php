<?php
/**
 * @desc: This file provides global session-based objects for all view pages
 */

require_once(__DIR__ . '/../../../model/ChatroomModel.php');

require_once(__DIR__ . '/../../../service/IdentityService.php');

$identityService = new IdentityService();

if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}