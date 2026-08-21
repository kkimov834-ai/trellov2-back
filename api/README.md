# OrderFlow PHP API

All endpoints return JSON. Authentication uses a secure PHP session cookie. The Vite dev proxy exposes these URLs under `/api`.

## Main routes

- `POST /auth.php?action=register|login|logout`, `GET /auth.php?action=me`
- `GET|POST|PUT|DELETE /tasks.php`
- `POST /workflow.php?action=send_to_production`
- `POST /workflow.php?action=approve_task` or `reject_task`
- `GET|POST /notifications.php`
- `GET /sse_stream.php`

The more explicit files (`auth/`, `send_to_production.php`, `approve_task.php`, `events.php`) remain available too. Approval is intentionally role-free: every authenticated user can accept or reject a request, and every user receives the request notification.
