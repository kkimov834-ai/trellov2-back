<?php
require_once dirname(__DIR__) . '/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error'=>'Method not allowed'],405);
$d=requestData();
$email=strtolower(trim((string)($d['email']??'')));
$aliases=['manager@taskflow'=>'manager','istehsalat@taskflow'=>'istehsalat_ustasi','cilalama@taskflow'=>'cilalama_ustasi','boyalama@taskflow'=>'boyalama_ustasi','anbar@taskflow'=>'anbar_ustasi'];
if (!isset($aliases[$email])) jsonResponse(['error'=>'Düzgün email daxil edin: manager@taskflow, istehsalat@taskflow, cilalama@taskflow, boyalama@taskflow və ya anbar@taskflow'],422);
try {
    $s=db()->prepare('SELECT id,name,email,role,password FROM users WHERE role=?');
    $s->execute([$aliases[$email]]); $u=$s->fetch();
    if (!$u || !password_verify((string)($d['password']??''),$u['password'])) jsonResponse(['error'=>'Şifrə yanlışdır'],401);
    unset($u['password']); session_regenerate_id(true); $_SESSION['user_id']=(int)$u['id']; jsonResponse(['user'=>$u,'token'=>session_id()]);
} catch (Throwable $e) {
    jsonResponse(['error'=>'Database schema köhnədir. phpMyAdmin-də trellov2-back/database/schema.sql faylını taskflow bazasına import edin.'],500);
}
