<?php
require_once __DIR__ . '/config.php';
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;
startSession();
function currentUser() {
    if (empty($_SESSION['user_id'])) return null;
    $stmt = db()->prepare('SELECT id, name, email, role, created_at FROM users WHERE id = ?');
    $stmt->execute(array((int) $_SESSION['user_id']));
    return $stmt->fetch() ?: null;
}
function requireUser() {
    $user = currentUser();
    if (!$user) jsonResponse(array('error' => 'Authentication required'), 401);
    return $user;
}
function requireManager() {
    $user = func_get_args()[0] ?? currentUser();
    if (!$user || $user['role'] !== 'manager') jsonResponse(array('error' => 'Yalnız manager sifariş yarada bilər'), 403);
}
