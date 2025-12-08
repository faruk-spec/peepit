<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\BottleModel;
use App\Models\BottleSize;
use App\Models\Order;

class OrderController extends Controller
{
    public function step1()
    {
        require_login();

        // Get active bottle models
        $bottleModel = new BottleModel();
        $models = $bottleModel->getActive();

        $csrfToken = $this->generateCSRF();
        $this->view('frontend/order/step1', [
            'csrf_token' => $csrfToken,
            'models' => $models
        ]);
    }

    public function step2()
    {
        require_login();
        $this->validateCSRF();

        $selectedModels = $_POST['bottle_models'] ?? [];
        
        if (empty($selectedModels)) {
            flash('error', 'Please select at least one bottle model');
            $this->redirect(url('order/start'));
        }

        // Store in session
        $_SESSION['order_data']['models'] = $selectedModels;

        // Get bottle sizes
        $bottleSize = new BottleSize();
        $sizes = $bottleSize->getActive();

        $csrfToken = $this->generateCSRF();
        $this->view('frontend/order/step2', [
            'csrf_token' => $csrfToken,
            'sizes' => $sizes,
            'selected_models' => $selectedModels
        ]);
    }

    public function step3()
    {
        require_login();
        $this->validateCSRF();

        $sizeId = $_POST['size_id'] ?? '';
        
        if (empty($sizeId)) {
            flash('error', 'Please select a bottle size');
            $this->redirect(url('order/step2'));
        }

        $_SESSION['order_data']['size_id'] = $sizeId;

        // Get color presets
        $colors = $this->db->fetchAll("SELECT * FROM color_presets WHERE status = 'active'");

        $csrfToken = $this->generateCSRF();
        $this->view('frontend/order/step3', [
            'csrf_token' => $csrfToken,
            'colors' => $colors
        ]);
    }

    public function step4()
    {
        require_login();
        $this->validateCSRF();

        $color = $_POST['color'] ?? '';
        
        if (empty($color)) {
            flash('error', 'Please select a color');
            $this->redirect(url('order/step3'));
        }

        $_SESSION['order_data']['color'] = $color;

        // Get label templates
        $templates = $this->db->fetchAll("SELECT * FROM label_templates WHERE status = 'active'");

        $csrfToken = $this->generateCSRF();
        $this->view('frontend/order/step4', [
            'csrf_token' => $csrfToken,
            'templates' => $templates
        ]);
    }

    public function step5()
    {
        require_login();
        $this->validateCSRF();

        // Handle label upload or template selection
        $labelDesign = $_POST['label_design'] ?? '';
        $labelImage = null;

        if (isset($_FILES['label_image']) && $_FILES['label_image']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['label_image'], __DIR__ . '/../../public/uploads/labels');
            if (isset($upload['success'])) {
                $labelImage = $upload['filename'];
            }
        }

        $_SESSION['order_data']['label_design'] = $labelDesign;
        $_SESSION['order_data']['label_image'] = $labelImage;

        // Get pricing
        $sizeId = $_SESSION['order_data']['size_id'];
        $bottleSize = new BottleSize();

        $csrfToken = $this->generateCSRF();
        $this->view('frontend/order/step5', [
            'csrf_token' => $csrfToken,
            'size_id' => $sizeId
        ]);
    }

    public function step6()
    {
        require_login();
        $this->validateCSRF();

        $quantity = intval($_POST['quantity'] ?? 0);
        
        if ($quantity < 1) {
            flash('error', 'Please enter a valid quantity');
            $this->redirect(url('order/step5'));
        }

        $_SESSION['order_data']['quantity'] = $quantity;

        // Calculate price
        $sizeId = $_SESSION['order_data']['size_id'];
        $bottleSize = new BottleSize();
        $unitPrice = $bottleSize->getPricing($sizeId, $quantity);
        $totalPrice = $unitPrice * $quantity;

        $_SESSION['order_data']['unit_price'] = $unitPrice;
        $_SESSION['order_data']['total_price'] = $totalPrice;

        $csrfToken = $this->generateCSRF();
        $this->view('frontend/order/step6', [
            'csrf_token' => $csrfToken
        ]);
    }

    public function step7()
    {
        require_login();
        $this->validateCSRF();

        $deliveryData = [
            'address' => sanitize($_POST['address'] ?? ''),
            'city' => sanitize($_POST['city'] ?? ''),
            'state' => sanitize($_POST['state'] ?? ''),
            'pincode' => sanitize($_POST['pincode'] ?? ''),
            'phone' => sanitize($_POST['phone'] ?? ''),
        ];

        if (empty($deliveryData['address']) || empty($deliveryData['city'])) {
            flash('error', 'Please fill all required delivery details');
            $this->redirect(url('order/step6'));
        }

        $_SESSION['order_data']['delivery'] = $deliveryData;

        $csrfToken = $this->generateCSRF();
        $this->view('frontend/order/step7', [
            'csrf_token' => $csrfToken,
            'order_data' => $_SESSION['order_data']
        ]);
    }

    public function submit()
    {
        require_login();
        $this->validateCSRF();

        if (!isset($_SESSION['order_data'])) {
            flash('error', 'Invalid order data');
            $this->redirect(url('order/start'));
        }

        try {
            $orderData = $_SESSION['order_data'];
            $userId = user_id();

            $orderModel = new Order();
            $orderNumber = $orderModel->generateOrderNumber();

            // Create order
            $orderId = $this->db->query(
                "INSERT INTO orders (user_id, order_number, total_amount, delivery_address, delivery_city, delivery_state, delivery_pincode, delivery_phone, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')",
                [
                    $userId,
                    $orderNumber,
                    $orderData['total_price'],
                    $orderData['delivery']['address'],
                    $orderData['delivery']['city'],
                    $orderData['delivery']['state'],
                    $orderData['delivery']['pincode'],
                    $orderData['delivery']['phone']
                ]
            );

            $orderId = $this->db->lastInsertId();

            // Create order items
            foreach ($orderData['models'] as $modelId) {
                $this->db->query(
                    "INSERT INTO order_items (order_id, bottle_model_id, bottle_size_id, color, label_design, label_image, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    [
                        $orderId,
                        $modelId,
                        $orderData['size_id'],
                        $orderData['color'],
                        $orderData['label_design'] ?? null,
                        $orderData['label_image'] ?? null,
                        $orderData['quantity'],
                        $orderData['unit_price'],
                        $orderData['total_price']
                    ]
                );
            }

            // Clear order session
            unset($_SESSION['order_data']);

            // Send email notifications (implement later with PHPMailer)
            $this->sendOrderEmails($orderId, $orderNumber);

            flash('success', "Order placed successfully! Your order number is: {$orderNumber}");
            $this->redirect(url('my-orders'));
        } catch (\Exception $e) {
            error_log('Order submission error: ' . $e->getMessage());
            flash('error', 'Failed to place order. Please try again.');
            $this->redirect(url('order/step7'));
        }
    }

    private function sendOrderEmails($orderId, $orderNumber)
    {
        // TODO: Implement PHPMailer email sending
        // 1. Send confirmation to customer
        // 2. Send notification to admin
        try {
            $this->db->query(
                "INSERT INTO email_logs (to_email, subject, body, status) VALUES (?, ?, ?, 'pending')",
                [
                    current_user()['email'],
                    "Order Confirmation - {$orderNumber}",
                    "Your order has been placed successfully."
                ]
            );
        } catch (\Exception $e) {
            error_log('Email logging error: ' . $e->getMessage());
        }
    }
}
