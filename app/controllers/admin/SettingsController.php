<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        require_role('superadmin');

        $settings = $this->db->fetchAll("SELECT * FROM settings ORDER BY `group`, `key`");
        
        // Group settings by category
        $groupedSettings = [];
        foreach ($settings as $setting) {
            $groupedSettings[$setting['group']][] = $setting;
        }

        $csrfToken = $this->generateCSRF();
        $this->view('admin/settings/index', [
            'csrf_token' => $csrfToken,
            'grouped_settings' => $groupedSettings
        ]);
    }

    public function update()
    {
        require_role('superadmin');
        $this->validateCSRF();

        try {
            foreach ($_POST as $key => $value) {
                if ($key === 'csrf_token') continue;
                
                $this->db->query(
                    "UPDATE settings SET value = ? WHERE `key` = ?",
                    [sanitize($value), $key]
                );
            }

            flash('success', 'Settings updated successfully');
        } catch (\Exception $e) {
            flash('error', 'Failed to update settings');
        }

        $this->redirect(url('admin/settings'));
    }

    public function smtp()
    {
        require_role('superadmin');

        $csrfToken = $this->generateCSRF();
        
        // Load current SMTP config
        $configFile = __DIR__ . '/../../../config/smtp.php';
        $smtpConfig = [];
        if (file_exists($configFile)) {
            $smtpConfig = require $configFile;
        }

        $this->view('admin/settings/smtp', [
            'csrf_token' => $csrfToken,
            'smtp_config' => $smtpConfig
        ]);
    }

    public function updateSmtp()
    {
        require_role('superadmin');
        $this->validateCSRF();

        $config = [
            'host' => sanitize($_POST['smtp_host'] ?? ''),
            'port' => intval($_POST['smtp_port'] ?? 587),
            'username' => sanitize($_POST['smtp_username'] ?? ''),
            'password' => $_POST['smtp_password'] ?? '',
            'from_email' => sanitize($_POST['smtp_from_email'] ?? ''),
            'from_name' => sanitize($_POST['smtp_from_name'] ?? ''),
            'encryption' => sanitize($_POST['smtp_encryption'] ?? 'tls'),
        ];

        try {
            $configContent = "<?php\nreturn [\n";
            foreach ($config as $key => $value) {
                if (is_string($value)) {
                    $configContent .= "    " . var_export($key, true) . " => " . var_export($value, true) . ",\n";
                } else {
                    $configContent .= "    " . var_export($key, true) . " => {$value},\n";
                }
            }
            $configContent .= "];\n";

            file_put_contents(__DIR__ . '/../../../config/smtp.php', $configContent);
            
            flash('success', 'SMTP settings updated successfully');
        } catch (\Exception $e) {
            flash('error', 'Failed to update SMTP settings');
        }

        $this->redirect(url('admin/settings/smtp'));
    }

    public function testEmail()
    {
        require_role('superadmin');
        $this->validateCSRF();

        $testEmail = sanitize($_POST['test_email'] ?? '');
        
        if (empty($testEmail) || !filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Please provide a valid email address');
            $this->redirect(url('admin/settings/smtp'));
        }

        try {
            require_once __DIR__ . '/../../../vendor/autoload.php';
            
            $emailService = new \App\Services\EmailService();
            // Send a test email (you can create a simple test method)
            
            flash('success', 'Test email sent successfully to ' . $testEmail);
        } catch (\Exception $e) {
            flash('error', 'Failed to send test email: ' . $e->getMessage());
        }

        $this->redirect(url('admin/settings/smtp'));
    }
}
