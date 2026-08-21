<?php
require_once __DIR__.'/bootstrap.php'; $u=requireUser();
if ($u['role']==='manager') { $s=db()->query('SELECT n.*,t.title,t.target_stage FROM notifications n JOIN tasks t ON t.id=n.task_id ORDER BY n.created_at DESC LIMIT 50'); }
else { $s=db()->prepare('SELECT n.*,t.title,t.target_stage FROM notifications n JOIN tasks t ON t.id=n.task_id WHERE n.target_role=? ORDER BY n.created_at DESC LIMIT 50'); $s->execute([$u['role']]); }
$items=$s->fetchAll(); $unread=0; foreach($items as $item) $unread+=(int)$item['is_read']===0?1:0; jsonResponse(['notifications'=>$items,'unread'=>$unread]);
