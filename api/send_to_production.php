<?php
require_once __DIR__ . '/bootstrap.php';
$user = requireUser();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(array('error' => 'Method not allowed'), 405);
$data = requestData(); $taskId = (int) ($data['task_id'] ?? 0); if (!$taskId) jsonResponse(array('error' => 'task_id tələb olunur'), 422);
$pdo = db(); $stmt = $pdo->prepare("SELECT id, title, created_by FROM tasks WHERE id=? AND (created_by=? OR ?='admin')"); $stmt->execute(array($taskId, $user['id'], $user['role'])); $task = $stmt->fetch();
if (!$task) jsonResponse(array('error' => 'Sifariş tapılmadı və ya icazə yoxdur'), 404);
$pdo->beginTransaction(); try {
    $pdo->prepare("UPDATE tasks SET status='Pending Approval', rejection_reason=NULL WHERE id=?")->execute(array($taskId));
    $users = $pdo->query('SELECT id FROM users')->fetchAll(); $message = $user['name'] . ' sifarişi istehsalata göndərdi: ' . $task['title'];
    $notice = $pdo->prepare("INSERT INTO notifications (user_id, task_id, message, type) VALUES (?, ?, ?, 'approval_request')"); foreach ($users as $recipient) $notice->execute(array($recipient['id'], $taskId, $message));
    $pdo->commit(); jsonResponse(array('sent' => true));
} catch (Throwable $e) { $pdo->rollBack(); jsonResponse(array('error' => 'Bildiriş göndərilə bilmədi'), 500); }
