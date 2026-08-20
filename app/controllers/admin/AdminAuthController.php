<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class AdminAuthController extends Controller
{
    public function login()
    {
        if (is_logged_in() && has_role('sales')) {
            $this->redirect(url('admin'));
        }

        $csrfToken = $this->generateCSRF();
        $this->view('admin/login', ['csrf_token' => $csrfToken]);
    }

    public function doLogin()
    {
        $this->validateCSRF();

        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            flash('error', 'Email and password are required');
            $this->redirect(url('admin/login'));
        }

        $user = $this->db->fetch(
            "SELECT * FROM users WHERE email = ? AND role IN ('superadmin', 'manager', 'sales', 'webmail')",
            [$email]
        );

        if (!$user || !password_verify($password, $user['password'])) {
            flash('error', 'Invalid credentials');
            $this->redirect(url('admin/login'));
        }

        if ($user['status'] !== 'active') {
            flash('error', 'Your account is inactive');
            $this->redirect(url('admin/login'));
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        // Track login
        try {
            $this->db->query(
                "INSERT INTO user_analytics (user_id, ip_address, user_agent, device_type, action) VALUES (?, ?, ?, ?, 'admin_login')",
                [$user['id'], get_client_ip(), get_user_agent(), get_device_type()]
            );
        } catch (\Exception $e) {
            error_log('Analytics tracking failed: ' . $e->getMessage());
        }

        flash('success', 'Welcome to admin panel!');
        
        // Redirect to webmail if webmail role
        if ($user['role'] === 'webmail') {
            $webmailUrl = config('webmail_url');
            $this->redirect($webmailUrl);
        }
        
        $this->redirect(url('admin'));
    }
}
