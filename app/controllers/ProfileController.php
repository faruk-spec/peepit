<?php

namespace App\Controllers;

use App\Core\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        require_login();
        
        $user = current_user();
        
        $csrfToken = $this->generateCSRF();
        $this->view('frontend/profile', [
            'csrf_token' => $csrfToken,
            'user' => $user
        ]);
    }
    
    public function update()
    {
        require_login();
        $this->validateCSRF();
        
        $userId = user_id();
        $name = sanitize($_POST['name'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($name)) {
            flash('error', 'Name is required.');
            $this->redirect(url('profile'));
        }
        
        try {
            // Update basic info
            $this->db->query(
                "UPDATE users SET name = ?, phone = ? WHERE id = ?",
                [$name, $phone, $userId]
            );
            
            // Handle password change if provided
            if (!empty($newPassword)) {
                if (empty($currentPassword)) {
                    flash('error', 'Current password is required to change password.');
                    $this->redirect(url('profile'));
                }
                
                // Verify current password
                $user = $this->db->fetch("SELECT password FROM users WHERE id = ?", [$userId]);
                if (!password_verify($currentPassword, $user['password'])) {
                    flash('error', 'Current password is incorrect.');
                    $this->redirect(url('profile'));
                }
                
                if (strlen($newPassword) < 8) {
                    flash('error', 'New password must be at least 8 characters.');
                    $this->redirect(url('profile'));
                }
                
                if ($newPassword !== $confirmPassword) {
                    flash('error', 'New passwords do not match.');
                    $this->redirect(url('profile'));
                }
                
                // Update password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $this->db->query(
                    "UPDATE users SET password = ? WHERE id = ?",
                    [$hashedPassword, $userId]
                );
            }
            
            // Update session
            $_SESSION['user']['name'] = $name;
            
            flash('success', 'Profile updated successfully.');
            $this->redirect(url('profile'));
        } catch (\Exception $e) {
            error_log('Profile update error: ' . $e->getMessage());
            flash('error', 'Failed to update profile. Please try again.');
            $this->redirect(url('profile'));
        }
    }
}
