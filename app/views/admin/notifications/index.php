<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Admin Panel</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
</head>
<body>
    <?php require_once __DIR__ . '/../../layouts/admin.php'; ?>

    <div class="admin-content">
        <div class="container">
            <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; gap: 15px; flex-wrap: wrap;">
                <style>
                    @media (max-width: 768px) {
                        .page-header { flex-direction: column; align-items: flex-start; }
                        .page-header form { width: 100%; }
                        .page-header form button { width: 100%; min-height: 44px; }
                    }
                </style>
                <div>
                    <h1>🔔 Notifications</h1>
                    <p class="text-muted">Stay updated with system activities</p>
                </div>
                
                <?php if (!empty($notifications)): ?>
                    <form action="<?= url('admin/notifications/mark-all-read') ?>" method="POST" style="margin: 0;">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <button type="submit" class="btn btn-secondary">
                            ✓ Mark All as Read
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <?php if (has_flash()): ?>
                <div class="alert alert-<?= flash_type() ?>">
                    <?= get_flash() ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($notifications)): ?>
                <div class="glass-card">
                    <div class="notifications-list">
                        <?php foreach ($notifications as $notification): ?>
                            <div class="notification-item <?= $notification['is_read'] ? 'read' : 'unread' ?>" 
                                 style="padding: 20px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: start; gap: 15px; <?= $notification['is_read'] ? 'opacity: 0.6;' : '' ?>">
                                <style>
                                    @media (max-width: 768px) {
                                        .notification-item { padding: 15px !important; gap: 10px !important; flex-wrap: wrap; }
                                        .notification-icon { font-size: 1.5rem !important; }
                                        .notification-actions { width: 100%; justify-content: flex-end; margin-top: 10px; }
                                        .notification-actions .btn-sm { min-width: 44px; min-height: 44px; }
                                    }
                                </style>
                                
                                <!-- Icon -->
                                <div class="notification-icon" style="font-size: 2rem; flex-shrink: 0;">
                                    <?php
                                    $iconMap = [
                                        'order' => '📦',
                                        'user' => '👤',
                                        'system' => '⚙️',
                                        'email' => '📧',
                                        'info' => 'ℹ️',
                                        'success' => '✅',
                                        'warning' => '⚠️',
                                        'error' => '❌'
                                    ];
                                    echo $iconMap[$notification['type'] ?? 'info'] ?? '🔔';
                                    ?>
                                </div>
                                
                                <!-- Content -->
                                <div class="notification-content" style="flex: 1;">
                                    <div class="notification-title" style="font-weight: 600; margin-bottom: 5px; color: #1e293b;">
                                        <?= htmlspecialchars($notification['title']) ?>
                                    </div>
                                    <div class="notification-message" style="color: #64748b; margin-bottom: 10px;">
                                        <?= htmlspecialchars($notification['message']) ?>
                                    </div>
                                    <div class="notification-time" style="font-size: 0.875rem; color: #94a3b8;">
                                        <?php
                                        $time = strtotime($notification['created_at']);
                                        $diff = time() - $time;
                                        
                                        if ($diff < 60) {
                                            echo 'Just now';
                                        } elseif ($diff < 3600) {
                                            echo floor($diff / 60) . ' minutes ago';
                                        } elseif ($diff < 86400) {
                                            echo floor($diff / 3600) . ' hours ago';
                                        } else {
                                            echo date('M d, Y at h:i A', $time);
                                        }
                                        ?>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="notification-actions" style="display: flex; gap: 10px; flex-shrink: 0;">
                                    <?php if (!$notification['is_read']): ?>
                                        <form action="<?= url('admin/notifications/mark-as-read') ?>" method="POST" style="margin: 0;">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                            <input type="hidden" name="notification_id" value="<?= intval($notification['id']) ?>">
                                            <button type="submit" class="btn btn-sm btn-secondary" title="Mark as read">
                                                ✓
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <form action="<?= url('admin/notifications/delete') ?>" method="POST" 
                                          onsubmit="return confirm('Delete this notification?')" style="margin: 0;">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                        <input type="hidden" name="notification_id" value="<?= intval($notification['id']) ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="glass-card" style="padding: 60px; text-align: center;">
                    <div style="font-size: 4rem; margin-bottom: 20px;">🔕</div>
                    <h3 style="color: #64748b; margin-bottom: 10px;">No Notifications</h3>
                    <p style="color: #94a3b8;">You're all caught up! Check back later for updates.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Auto-refresh unread count -->
    <script>
        function updateUnreadCount() {
            fetch('<?= url('admin/notifications/unread-count') ?>')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    if (badge && data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline-block';
                    } else if (badge) {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching unread count:', error));
        }
        
        // Update every 30 seconds
        setInterval(updateUnreadCount, 30000);
        updateUnreadCount(); // Initial load
    </script>
</body>
</html>
