<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class NotificationsController extends Controller
{
    public function index()
    {
        require_role('sales');
        
        try {
            $userId = user_id();
            
            // Get all notifications
            $notifications = $this->db->fetchAll(
                "SELECT * FROM notifications 
                 WHERE user_id = ? OR user_id IS NULL
                 ORDER BY created_at DESC 
                 LIMIT 50",
                [$userId]
            );
            
            $csrfToken = $this->generateCSRF();
            $this->view('admin/notifications/index', [
                'csrf_token' => $csrfToken,
                'notifications' => $notifications
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching notifications: ' . $e->getMessage());
            flash('error', 'Unable to load notifications.');
            $this->view('admin/notifications/index', [
                'csrf_token' => $this->generateCSRF(),
                'notifications' => []
            ]);
        }
    }
    
    public function markAsRead()
    {
        require_role('sales');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/notifications');
        }
        
        $this->validateCSRF();
        
        try {
            $notificationId = intval($_POST['notification_id'] ?? 0);
            $userId = user_id();
            
            $this->db->execute(
                "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?",
                [$notificationId, $userId]
            );
            
            flash('success', 'Notification marked as read.');
        } catch (\Exception $e) {
            error_log('Error marking notification as read: ' . $e->getMessage());
            flash('error', 'Unable to update notification.');
        }
        
        redirect('admin/notifications');
    }
    
    public function markAllAsRead()
    {
        require_role('sales');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/notifications');
        }
        
        $this->validateCSRF();
        
        try {
            $userId = user_id();
            
            $this->db->execute(
                "UPDATE notifications SET is_read = 1 WHERE user_id = ? OR user_id IS NULL",
                [$userId]
            );
            
            flash('success', 'All notifications marked as read.');
        } catch (\Exception $e) {
            error_log('Error marking all notifications as read: ' . $e->getMessage());
            flash('error', 'Unable to update notifications.');
        }
        
        redirect('admin/notifications');
    }
    
    public function delete()
    {
        require_role('sales');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/notifications');
        }
        
        $this->validateCSRF();
        
        try {
            $notificationId = intval($_POST['notification_id'] ?? 0);
            $userId = user_id();
            
            $this->db->execute(
                "DELETE FROM notifications WHERE id = ? AND user_id = ?",
                [$notificationId, $userId]
            );
            
            flash('success', 'Notification deleted.');
        } catch (\Exception $e) {
            error_log('Error deleting notification: ' . $e->getMessage());
            flash('error', 'Unable to delete notification.');
        }
        
        redirect('admin/notifications');
    }
    
    public function getUnreadCount()
    {
        require_role('sales');
        
        try {
            $userId = user_id();
            
            $count = $this->db->fetch(
                "SELECT COUNT(*) as count FROM notifications 
                 WHERE (user_id = ? OR user_id IS NULL) AND is_read = 0",
                [$userId]
            )['count'] ?? 0;
            
            header('Content-Type: application/json');
            echo json_encode(['count' => $count]);
            exit;
        } catch (\Exception $e) {
            error_log('Error fetching unread count: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['count' => 0]);
            exit;
        }
    }
}
