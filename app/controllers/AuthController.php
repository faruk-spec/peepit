<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class AuthController extends Controller
{
    public function register()
    {
        if (is_logged_in()) {
            $this->redirect(url());
        }
        
        $csrfToken = $this->generateCSRF();
        $this->view('frontend/register', ['csrf_token' => $csrfToken]);
    }

    public function doRegister()
    {
        $this->validateCSRF();

        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validation
        if (empty($name) || empty($email) || empty($password)) {
            flash('error', 'All fields are required');
            set_old($_POST);
            $this->redirect(url('register'));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Invalid email address');
            set_old($_POST);
            $this->redirect(url('register'));
        }

        if (strlen($password) < 8) {
            flash('error', 'Password must be at least 8 characters');
            set_old($_POST);
            $this->redirect(url('register'));
        }

        if ($password !== $confirmPassword) {
            flash('error', 'Passwords do not match');
            set_old($_POST);
            $this->redirect(url('register'));
        }

        // Check if email exists
        $existing = $this->db->fetch("SELECT id FROM users WHERE email = ?", [$email]);
        if ($existing) {
            flash('error', 'Email already registered');
            set_old($_POST);
            $this->redirect(url('register'));
        }

        // Create user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        try {
            $userId = $this->db->query(
                "INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'user')",
                [$name, $email, $phone, $hashedPassword]
            );

            // Track analytics
            $this->trackRegistration($userId);

            // Auto login
            $_SESSION['user_id'] = $this->db->lastInsertId();
            $_SESSION['user_role'] = 'user';
            $_SESSION['user'] = [
                'id' => $_SESSION['user_id'],
                'name' => $name,
                'email' => $email,
                'role' => 'user'
            ];

            flash('success', 'Registration successful! Welcome to Peepit.');
            clear_old();
            $this->redirect(url());
        } catch (\Exception $e) {
            flash('error', 'Registration failed. Please try again.');
            set_old($_POST);
            $this->redirect(url('register'));
        }
    }

    public function login()
    {
        if (is_logged_in()) {
            $this->redirect(url());
        }

        $csrfToken = $this->generateCSRF();
        $this->view('frontend/login', ['csrf_token' => $csrfToken]);
    }

    public function doLogin()
    {
        $this->validateCSRF();

        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            flash('error', 'Email and password are required');
            $this->redirect(url('login'));
        }

        $user = $this->db->fetch("SELECT * FROM users WHERE email = ? AND role = 'user'", [$email]);

        if (!$user || !password_verify($password, $user['password'])) {
            flash('error', 'Invalid credentials');
            $this->redirect(url('login'));
        }

        if ($user['status'] !== 'active') {
            flash('error', 'Your account is inactive');
            $this->redirect(url('login'));
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
        $this->trackLogin($user['id']);

        flash('success', 'Welcome back!');
        $this->redirect(url());
    }

    public function logout()
    {
        session_destroy();
        $this->redirect(url('login'));
    }

    private function trackRegistration($userId)
    {
        try {
            $this->db->query(
                "INSERT INTO user_analytics (user_id, ip_address, user_agent, device_type, action) VALUES (?, ?, ?, ?, 'registration')",
                [$userId, get_client_ip(), get_user_agent(), get_device_type()]
            );
        } catch (\Exception $e) {
            // Log error but don't fail registration
            error_log('Analytics tracking failed: ' . $e->getMessage());
        }
    }

    private function trackLogin($userId)
    {
        try {
            $this->db->query(
                "INSERT INTO user_analytics (user_id, ip_address, user_agent, device_type, action) VALUES (?, ?, ?, ?, 'login')",
                [$userId, get_client_ip(), get_user_agent(), get_device_type()]
            );
        } catch (\Exception $e) {
            error_log('Analytics tracking failed: ' . $e->getMessage());
        }
    }
}
