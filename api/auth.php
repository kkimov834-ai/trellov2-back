<?php
// Compatibility router: POST action=login|register|logout, GET action=me.
$action = $_GET['action'] ?? $_POST['action'] ?? 'me';
$routes = array('login' => __DIR__ . '/auth/login.php', 'register' => __DIR__ . '/auth/register.php', 'logout' => __DIR__ . '/auth/logout.php', 'me' => __DIR__ . '/auth/me.php');
if (!isset($routes[$action])) { http_response_code(404); echo json_encode(array('error' => 'Unknown auth action')); exit; }
require $routes[$action];
