<?php
require_once(__DIR__ . '/../../../service/IdentityService.php');

$identityService = new IdentityService();
$response = $identityService->logout();

header("Location: http://" . $_SERVER['SERVER_NAME']);