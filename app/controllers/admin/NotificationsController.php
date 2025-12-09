<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class NotificationsController extends Controller
{
    public function index()
    {
        require_role('sales');
        
        try {
            // Get all notifications
            $notifications = $this->db->fetchAll(
                "SELECT * FROM notifications 
                 WHERE user_id = ? OR user_id IS NULL
                 ORDER BY created_at DESC 
                 LIMIT 50",
                [user()->id]
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
        
        $this->validateCSRF($_POST['csrf_token'] ?? '');
        
        try {
            $notificationId = intval($_POST['notification_id'] ?? 0);
            
            $this->db->execute(
                "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?",
                [$notificationId, user()->id]
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
        
        $this->validateCSRF($_POST['csrf_token'] ?? '');
        
        try {
            $this->db->execute(
                "UPDATE notifications SET is_read = 1 WHERE user_id = ? OR user_id IS NULL",
                [user()->id]
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
        
        $this->validateCSRF($_POST['csrf_token'] ?? '');
        
        try {
            $notificationId = intval($_POST['notification_id'] ?? 0);
            
            $this->db->execute(
                "DELETE FROM notifications WHERE id = ? AND user_id = ?",
                [$notificationId, user()->id]
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
            $count = $this->db->fetch(
                "SELECT COUNT(*) as count FROM notifications 
                 WHERE (user_id = ? OR user_id IS NULL) AND is_read = 0",
                [user()->id]
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
