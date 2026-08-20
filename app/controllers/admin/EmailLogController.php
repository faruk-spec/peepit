<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class EmailLogController extends Controller
{
    public function index()
    {
        require_role('manager');

        try {
            $logs = $this->db->fetchAll(
                "SELECT * FROM email_logs ORDER BY created_at DESC LIMIT 100"
            );
            
            $csrfToken = $this->generateCSRF();
            $this->view('admin/email-logs/index', [
                'csrf_token' => $csrfToken,
                'logs' => $logs
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching email logs: ' . $e->getMessage());
            flash('error', 'Unable to load email logs.');
            $this->view('admin/email-logs/index', ['csrf_token' => $this->generateCSRF(), 'logs' => []]);
        }
    }

    public function viewLog($id)
    {
        require_role('manager');

        try {
            $log = $this->db->fetch("SELECT * FROM email_logs WHERE id = ?", [$id]);

            if (!$log) {
                flash('error', 'Email log not found');
                $this->redirect(url('admin/email-logs'));
            }

            $csrfToken = $this->generateCSRF();
            $this->view('admin/email-logs/view', [
                'csrf_token' => $csrfToken,
                'log' => $log
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching email log: ' . $e->getMessage());
            flash('error', 'Unable to load email log');
            $this->redirect(url('admin/email-logs'));
        }
    }

    public function delete($id)
    {
        require_role('superadmin');
        $this->validateCSRF();

        try {
            $this->db->query("DELETE FROM email_logs WHERE id = ?", [$id]);
            flash('success', 'Email log deleted successfully');
        } catch (\Exception $e) {
            error_log('Error deleting email log: ' . $e->getMessage());
            flash('error', 'Failed to delete email log');
        }

        $this->redirect(url('admin/email-logs'));
    }

    public function clear()
    {
        require_role('superadmin');
        $this->validateCSRF();

        try {
            $this->db->query("DELETE FROM email_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
            flash('success', 'Old email logs cleared successfully');
        } catch (\Exception $e) {
            error_log('Error clearing email logs: ' . $e->getMessage());
            flash('error', 'Failed to clear email logs');
        }

        $this->redirect(url('admin/email-logs'));
    }
}
