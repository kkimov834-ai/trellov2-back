<?php
declare(strict_types=1);
const DB_HOST = '127.0.0.1';
const DB_NAME = 'taskflow';
const DB_USER = 'root';
const DB_PASS = '';
const UPLOAD_DIR = __DIR__ . '/uploads/';
const UPLOAD_URL = '/workspace2/trellov2-back/api/uploads/';

function db() {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false));
    }
    return $pdo;
}
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params(array('httponly' => true, 'samesite' => 'Lax'));
        session_start();
    }
}
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}
function requestData() {
    $data = json_decode(file_get_contents('php://input'), true);
    return is_array($data) ? $data : $_POST;
}
