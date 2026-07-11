<?php
// router.php
if (is_file($_SERVER['DOCUMENT_ROOT'] . $_SERVER['SCRIPT_NAME'])) {
    // Tell PHP to serve the static file directly
    return false;
}

// Otherwise, require Symfony's front controller
require_once $_SERVER['DOCUMENT_ROOT'] . '/index.php';
