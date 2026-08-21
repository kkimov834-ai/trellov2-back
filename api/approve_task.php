<?php
require_once __DIR__ . '/bootstrap.php';
$user = requireUser();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(array('error' => 'Method not allowed'), 405);
$data = requestData(); $taskId = (int) ($data['task_id'] ?? 0); $action = $data['action'] ?? (($_GET['action'] ?? '') === 'reject_task' ? 'reject' : ''); $reason = trim($data['reason'] ?? '');
if (!$taskId || !in_array($action, array('approve','reject'), true)) jsonResponse(array('error' => 'task_id və action tələb olunur'), 422);
if ($action === 'reject' && $reason === '') jsonResponse(array('error' => 'İmtina səbəbi tələb olunur'), 422);
$pdo = db(); $stmt = $pdo->prepare('SELECT id, title, created_by FROM tasks WHERE id=?'); $stmt->execute(array($taskId)); $task = $stmt->fetch(); if (!$task) jsonResponse(array('error' => 'Sifariş tapılmadı'), 404);
$status = $action === 'approve' ? 'In Production' : 'Rejected'; $pdo->beginTransaction(); try {
    $stmt = $pdo->prepare('UPDATE tasks SET status=?, rejection_reason=?, approved_by=?, approved_at=NOW() WHERE id=?'); $stmt->execute(array($status, $action === 'reject' ? $reason : null, $user['id'], $taskId));
    if (!empty($task['created_by'])) { $message = $action === 'approve' ? 'Sifarişiniz istehsalata qəbul edildi: ' . $task['title'] : 'Sifarişiniz imtina edildi: ' . $task['title'] . ' — ' . $reason; $n = $pdo->prepare("INSERT INTO notifications (user_id, task_id, message, type) VALUES (?, ?, ?, ?)"); $n->execute(array($task['created_by'], $taskId, $message, $action === 'approve' ? 'approved' : 'rejected')); }
    $pdo->commit(); jsonResponse(array('status' => $status));
} catch (Throwable $e) { $pdo->rollBack(); jsonResponse(array('error' => 'Approval əməliyyatı uğursuz oldu'), 500); }
