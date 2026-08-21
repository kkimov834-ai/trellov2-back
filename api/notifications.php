<?php
// GET returns notifications; POST marks one or all as read.
if ($_SERVER['REQUEST_METHOD'] === 'GET') require __DIR__ . '/get_notifications.php';
else require __DIR__ . '/read_notifications.php';
