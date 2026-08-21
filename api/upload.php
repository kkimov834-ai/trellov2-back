<?php
require_once __DIR__ . '/bootstrap.php';
requireUser();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['image']['tmp_name'])) jsonResponse(array('error' => 'image faylı tələb olunur'), 422);
$mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES['image']['tmp_name']); $allowed = array('image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif','image/webp'=>'webp');
if (!isset($allowed[$mime]) || $_FILES['image']['size'] > 5242880) jsonResponse(array('error' => 'Dəstəklənməyən və ya çox böyük fayl'), 422);
if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true); $name = bin2hex(random_bytes(12)) . '.' . $allowed[$mime]; move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $name);
jsonResponse(array('image_url' => UPLOAD_URL . $name), 201);
