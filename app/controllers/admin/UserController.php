<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class UserController extends Controller
{
    public function index()
    {
        require_role('manager');

        try {
            $users = $this->db->fetchAll(
                "SELECT id, name, email, phone, role, status, created_at FROM users ORDER BY created_at DESC"
            );
            
            $csrfToken = $this->generateCSRF();
            $this->view('admin/users/index', [
                'csrf_token' => $csrfToken,
                'users' => $users
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching users: ' . $e->getMessage());
            flash('error', 'Unable to load users.');
            $this->view('admin/users/index', ['csrf_token' => $this->generateCSRF(), 'users' => []]);
        }
    }

    public function edit($id)
    {
        require_role('manager');

        try {
            $user = $this->db->fetch(
                "SELECT id, name, email, phone, role, status FROM users WHERE id = ?",
                [$id]
            );

            if (!$user) {
                flash('error', 'User not found');
                $this->redirect(url('admin/users'));
            }

            $csrfToken = $this->generateCSRF();
            $this->view('admin/users/edit', [
                'csrf_token' => $csrfToken,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            error_log('Error fetching user: ' . $e->getMessage());
            flash('error', 'Unable to load user');
            $this->redirect(url('admin/users'));
        }
    }

    public function update($id)
    {
        require_role('manager');
        $this->validateCSRF();

        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $role = sanitize($_POST['role'] ?? 'customer');
        $status = sanitize($_POST['status'] ?? 'active');

        if (empty($name) || empty($email)) {
            flash('error', 'Name and email are required');
            $this->redirect(url("admin/users/edit/{$id}"));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Invalid email format');
            $this->redirect(url("admin/users/edit/{$id}"));
        }

        if (!in_array($role, ['customer', 'sales', 'manager', 'superadmin'])) {
            flash('error', 'Invalid role');
            $this->redirect(url("admin/users/edit/{$id}"));
        }

        try {
            // Check if email exists for another user
            $existing = $this->db->fetch(
                "SELECT id FROM users WHERE email = ? AND id != ?",
                [$email, $id]
            );

            if ($existing) {
                flash('error', 'Email already in use by another user');
                $this->redirect(url("admin/users/edit/{$id}"));
            }

            $this->db->query(
                "UPDATE users SET name = ?, email = ?, phone = ?, role = ?, status = ? WHERE id = ?",
                [$name, $email, $phone, $role, $status, $id]
            );

            flash('success', 'User updated successfully');
            $this->redirect(url('admin/users'));
        } catch (\Exception $e) {
            error_log('Error updating user: ' . $e->getMessage());
            flash('error', 'Failed to update user');
            $this->redirect(url("admin/users/edit/{$id}"));
        }
    }

    public function delete($id)
    {
        require_role('superadmin');
        $this->validateCSRF();

        try {
            // Don't allow deleting yourself
            if ($id == user_id()) {
                flash('error', 'You cannot delete your own account');
                $this->redirect(url('admin/users'));
            }

            $this->db->query("DELETE FROM users WHERE id = ?", [$id]);
            flash('success', 'User deleted successfully');
        } catch (\Exception $e) {
            error_log('Error deleting user: ' . $e->getMessage());
            flash('error', 'Failed to delete user. User may have associated orders.');
        }

        $this->redirect(url('admin/users'));
    }
}
