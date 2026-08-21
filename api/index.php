<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'name' => 'TaskFlow API',
    'endpoint' => '/workspace2/trellov2-back/api/tasks.php',
    'status' => 'ok',
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
