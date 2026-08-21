<?php
require_once dirname(__DIR__) . '/bootstrap.php';
$_SESSION = array(); session_destroy(); jsonResponse(array('success' => true));
