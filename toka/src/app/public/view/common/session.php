<?php
require_once(__DIR__ . '/../../../service/IdentityService.php');

$identityService = new IdentityService();
$user = $identityService->getUserSession();