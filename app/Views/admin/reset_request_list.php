<?= view('layout/header') ?>
<style>
    body {
        font-family: "Poppins", sans-serif;
        background-color: #ff8800ff;
        padding: 40px;
    }
</style>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/index.php/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Reset-Requests</li>
        </ol>
    </nav>





    <!-- Notification -->
    <div class="notification-card">
        <h4>Task Notifications</h4>
        <?php if (!empty($notifications)): ?>
            <ul id="notificationsList">
                <?php foreach ($notifications as $note): ?>
                    <li id="notification-<?= $note['id'] ?>" 
                        style="font-weight:bold; background:#fff3cd; padding:10px; border-radius:4px; margin-bottom:8px;"
                        data-notification-id="<?= $note['id'] ?>">
                        <?php
                        // Normalize legacy links in stored messages:
                        $msg = $note['message'];
                        // Replace project links like /projects/{id} -> /projects/{id}/tasks
                        $msg = preg_replace('#href=[\"\']?/projects/(\d+)[\"\']?#', "href='/projects/$1/tasks'", $msg);
                        // Try to extract a project id from the message (after replacement)
                        $projectId = null;
                        if (preg_match("#/projects/(\d+)/tasks#", $msg, $m)) {
                            $projectId = $m[1];
                        }
                        // Replace old task links /tasks/{id} with /projects/{projectId}/tasks/edit/{taskId}
                        if ($projectId) {
                            $msg = preg_replace('#href=[\"\']?/tasks/(\d+)[\"\']?#', "href='/projects/{$projectId}/tasks/edit/$1'", $msg);
                        } else {
                            // Fallback: link task to projects tasks list (task edit unknown project)
                            $msg = preg_replace('#href=[\"\']?/tasks/(\d+)[\"\']?#', "href='/projects/$1/tasks'", $msg);
                        }

                        echo $msg; // contains HTML
                        ?>
                        <br>
                        <small><?= date('d M Y H:i', strtotime($note['created_at'])) ?></small>
                        <button class="btn btn-xs btn-secondary" style="margin-left:10px; padding:2px 6px; font-size:0.8em;" 
                                onclick="markNotificationRead(<?= $note['id'] ?>, this)">Mark as Read</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No notifications yet.</p>
        <?php endif; ?>
    </div>







    <h2>Reset Requests</h2>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <?php $i = 0; ?>
                <th>ID</th>
                <th>Email</th>
                <th>Status</th>
                <th>Requested At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($requests as $r): ?>
                <tr>
                    <td><?= ++$i ?></td>
                    <td><?= esc($r['email']) ?></td>
                    <td><?= esc($r['status']) ?></td>
                    <td><?= esc($r['created_at']) ?></td>
                    <td>
                        <?php if($r['status'] == 'pending'): ?>
                            <a href="/admin/reset-requests/<?= $r['id'] ?>" class="btn btn-sm btn-primary">Set Password</a>
                            <a href="/admin/reset-requests/reject/<?= $r['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Reject?')">Reject</a>
                        <?php elseif($r['status'] == 'approved'): ?>
                            <a href="/admin/reset-requests/<?= $r['id'] ?>" class="btn btn-sm btn-primary">Set Password</a>
                        <?php else: ?>
                            <span class="text-muted">No action</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function markNotificationRead(notificationId, button) {
    fetch(`/admin/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notifElement = document.getElementById(`notification-${notificationId}`);
            if (notifElement) {
                notifElement.style.opacity = '0';
                notifElement.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    notifElement.remove();
                    const list = document.getElementById('notificationsList');
                    if (list && list.children.length === 0) {
                        list.innerHTML = '<p>No notifications yet.</p>';
                    }
                }, 300);
            }
        }
    })
    .catch(err => console.error('Error deleting notification:', err));
}
</script>

<?= view('layout/footer') ?>

