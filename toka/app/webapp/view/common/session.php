<?php
/**
 * @desc: This file provides global session-based objects for all view pages
 */

if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}