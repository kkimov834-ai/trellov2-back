<?php
require_once dirname(__DIR__) . '/bootstrap.php';
$user = currentUser(); if (!$user) jsonResponse(array('user' => null), 401); jsonResponse(array('user' => $user));
