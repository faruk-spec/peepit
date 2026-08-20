<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Core\Database;

class EmailService
{
    private $mailer;
    private $db;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->db = Database::getInstance();
        $this->configureMailer();
    }

    private function configureMailer()
    {
        $configFile = __DIR__ . '/../../config/smtp.php';
        if (!file_exists($configFile)) {
            throw new \Exception('SMTP configuration not found');
        }

        $config = require $configFile;

        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = $config['host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $config['username'];
            $this->mailer->Password = $config['password'];
            $this->mailer->SMTPSecure = $config['encryption'];
            $this->mailer->Port = $config['port'];

            // Default sender
            $this->mailer->setFrom($config['from_email'], $config['from_name']);
        } catch (Exception $e) {
            error_log('Mailer configuration error: ' . $e->getMessage());
            throw new \Exception('Failed to configure email service');
        }
    }

    public function sendOrderConfirmation($orderId, $userEmail, $userName, $orderNumber)
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($userEmail, $userName);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = "Order Confirmation - {$orderNumber}";
            $this->mailer->Body = $this->getOrderConfirmationTemplate($orderId, $userName, $orderNumber);

            $this->mailer->send();
            $this->logEmail($userEmail, $this->mailer->Subject, $this->mailer->Body, 'sent');
            return true;
        } catch (Exception $e) {
            $this->logEmail($userEmail, $this->mailer->Subject, $this->mailer->Body, 'failed', $e->getMessage());
            error_log('Email send error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendAdminOrderNotification($orderId, $orderNumber)
    {
        try {
            $adminEmail = config('contact_email');
            
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($adminEmail);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = "New Order Received - {$orderNumber}";
            $this->mailer->Body = $this->getAdminOrderTemplate($orderId, $orderNumber);

            $this->mailer->send();
            $this->logEmail($adminEmail, $this->mailer->Subject, $this->mailer->Body, 'sent');
            return true;
        } catch (Exception $e) {
            $this->logEmail($adminEmail, $this->mailer->Subject, $this->mailer->Body, 'failed', $e->getMessage());
            error_log('Admin email send error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendLabelApprovalRequest($orderId, $adminEmail)
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($adminEmail);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = "Label Design Approval Required";
            $this->mailer->Body = $this->getLabelApprovalTemplate($orderId);

            $this->mailer->send();
            $this->logEmail($adminEmail, $this->mailer->Subject, $this->mailer->Body, 'sent');
            return true;
        } catch (Exception $e) {
            $this->logEmail($adminEmail, $this->mailer->Subject, $this->mailer->Body, 'failed', $e->getMessage());
            return false;
        }
    }

    private function getOrderConfirmationTemplate($orderId, $userName, $orderNumber)
    {
        $appName = config('app_name', 'Peepit');
        $appUrl = config('app_url');

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
                .content { padding: 30px; background: #f7fafc; }
                .button { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; }
                .footer { text-align: center; padding: 20px; color: #718096; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🚰 {$appName}</h1>
                    <p>Order Confirmation</p>
                </div>
                <div class='content'>
                    <h2>Thank you, {$userName}!</h2>
                    <p>Your order has been received and is being processed.</p>
                    <p><strong>Order Number:</strong> {$orderNumber}</p>
                    <p>We will notify you once your order is ready for delivery.</p>
                    <p style='text-align: center; margin-top: 30px;'>
                        <a href='{$appUrl}/my-orders' class='button'>View Order Details</a>
                    </p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " {$appName}. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    private function getAdminOrderTemplate($orderId, $orderNumber)
    {
        $appUrl = config('app_url');

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2d3748; color: white; padding: 20px; }
                .content { padding: 20px; background: #fff; border: 1px solid #e2e8f0; }
                .button { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Order Received</h2>
                </div>
                <div class='content'>
                    <p>A new order has been placed.</p>
                    <p><strong>Order Number:</strong> {$orderNumber}</p>
                    <p>Please review and process this order.</p>
                    <p style='margin-top: 20px;'>
                        <a href='{$appUrl}/admin/orders/view/{$orderId}' class='button'>View Order</a>
                    </p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    private function getLabelApprovalTemplate($orderId)
    {
        $appUrl = config('app_url');

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .content { padding: 20px; background: #fff; border: 1px solid #e2e8f0; }
                .button { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='content'>
                    <h2>Label Design Approval Required</h2>
                    <p>A custom label design requires your approval before production.</p>
                    <p style='margin-top: 20px;'>
                        <a href='{$appUrl}/admin/orders/view/{$orderId}' class='button'>Review Label</a>
                    </p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    private function logEmail($to, $subject, $body, $status, $error = null)
    {
        try {
            $this->db->query(
                "INSERT INTO email_logs (to_email, subject, body, status, error_message) VALUES (?, ?, ?, ?, ?)",
                [$to, $subject, $body, $status, $error]
            );
        } catch (\Exception $e) {
            error_log('Email logging error: ' . $e->getMessage());
        }
    }
}
