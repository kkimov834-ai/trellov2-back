<?php
require_once __DIR__ . '/config.php';
$requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigins = array_filter(array_map('trim', explode(',', getenv('FRONTEND_ORIGINS') ?: 'http://localhost:5173,http://127.0.0.1:5173')));
if (in_array($requestOrigin, $allowedOrigins, true)) header('Access-Control-Allow-Origin: ' . $requestOrigin);
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
