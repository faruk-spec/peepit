<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class BulkOperationsController extends Controller
{
    public function index()
    {
        require_role('manager');
        
        // Get statistics for display
        try {
            $productCount = $this->db->fetch("SELECT COUNT(*) as count FROM bottle_models")['count'] ?? 0;
            $orderCount = $this->db->fetch("SELECT COUNT(*) as count FROM orders")['count'] ?? 0;
            $customerCount = $this->db->fetch("SELECT COUNT(*) as count FROM users WHERE role = 'customer'")['count'] ?? 0;
        } catch (\Exception $e) {
            error_log('Error fetching stats: ' . $e->getMessage());
            $productCount = 0;
            $orderCount = 0;
            $customerCount = 0;
        }
        
        $csrfToken = $this->generateCSRF();
        $this->view('admin/bulk-operations/index', [
            'csrf_token' => $csrfToken,
            'product_count' => $productCount,
            'order_count' => $orderCount,
            'customer_count' => $customerCount
        ]);
    }
    
    public function importProducts()
    {
        require_role('manager');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/bulk-operations');
        }
        
        $this->validateCSRF($_POST['csrf_token'] ?? '');
        
        try {
            if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
                flash('error', 'Please upload a valid CSV file.');
                redirect('admin/bulk-operations');
            }
            
            $file = $_FILES['csv_file'];
            $tmpName = $file['tmp_name'];
            
            // Parse CSV
            $handle = fopen($tmpName, 'r');
            $headers = fgetcsv($handle);
            
            $imported = 0;
            $errors = [];
            
            while (($data = fgetcsv($handle)) !== false) {
                try {
                    // Expecting: name, description, base_price, capacity, status
                    if (count($data) >= 5) {
                        $this->db->execute(
                            "INSERT INTO bottle_models (name, description, base_price, capacity, status, created_at) 
                             VALUES (?, ?, ?, ?, ?, NOW())",
                            [$data[0], $data[1], $data[2], $data[3], $data[4]]
                        );
                        $imported++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($imported + count($errors) + 2) . ": " . $e->getMessage();
                }
            }
            
            fclose($handle);
            
            if ($imported > 0) {
                flash('success', "Successfully imported {$imported} products." . (count($errors) > 0 ? " " . count($errors) . " errors occurred." : ""));
            } else {
                flash('error', 'No products were imported. ' . implode(', ', $errors));
            }
            
        } catch (\Exception $e) {
            error_log('Error importing products: ' . $e->getMessage());
            flash('error', 'An error occurred during import.');
        }
        
        redirect('admin/bulk-operations');
    }
    
    public function exportProducts()
    {
        require_role('manager');
        
        try {
            $products = $this->db->fetchAll(
                "SELECT id, name, description, base_price, capacity, status, created_at 
                 FROM bottle_models 
                 ORDER BY id ASC"
            );
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="products_export_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            // Write headers
            fputcsv($output, ['ID', 'Name', 'Description', 'Base Price', 'Capacity', 'Status', 'Created Date']);
            
            // Write data
            foreach ($products as $product) {
                fputcsv($output, [
                    $product['id'],
                    $product['name'],
                    $product['description'],
                    $product['base_price'],
                    $product['capacity'],
                    $product['status'],
                    $product['created_at']
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (\Exception $e) {
            error_log('Error exporting products: ' . $e->getMessage());
            flash('error', 'Unable to export products.');
            redirect('admin/bulk-operations');
        }
    }
    
    public function exportOrders()
    {
        require_role('manager');
        
        try {
            $orders = $this->db->fetchAll(
                "SELECT o.*, u.name as customer_name, u.email as customer_email
                 FROM orders o
                 LEFT JOIN users u ON o.user_id = u.id
                 ORDER BY o.created_at DESC"
            );
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="orders_export_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            // Write headers
            fputcsv($output, ['Order ID', 'Customer Name', 'Customer Email', 'Status', 'Total Amount', 'Created Date']);
            
            // Write data
            foreach ($orders as $order) {
                fputcsv($output, [
                    $order['id'],
                    $order['customer_name'],
                    $order['customer_email'],
                    $order['status'],
                    $order['total_amount'],
                    $order['created_at']
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (\Exception $e) {
            error_log('Error exporting orders: ' . $e->getMessage());
            flash('error', 'Unable to export orders.');
            redirect('admin/bulk-operations');
        }
    }
    
    public function exportCustomers()
    {
        require_role('manager');
        
        try {
            $customers = $this->db->fetchAll(
                "SELECT id, name, email, role, status, created_at 
                 FROM users 
                 WHERE role = 'customer'
                 ORDER BY created_at DESC"
            );
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="customers_export_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            // Write headers
            fputcsv($output, ['ID', 'Name', 'Email', 'Role', 'Status', 'Registered Date']);
            
            // Write data
            foreach ($customers as $customer) {
                fputcsv($output, [
                    $customer['id'],
                    $customer['name'],
                    $customer['email'],
                    $customer['role'],
                    $customer['status'],
                    $customer['created_at']
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (\Exception $e) {
            error_log('Error exporting customers: ' . $e->getMessage());
            flash('error', 'Unable to export customers.');
            redirect('admin/bulk-operations');
        }
    }
}
