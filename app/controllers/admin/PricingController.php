<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class PricingController extends Controller
{
    public function index()
    {
        require_role('manager');

        try {
            // Fetch all pricing tiers
            $tiers = $this->db->fetchAll(
                "SELECT * FROM pricing_tiers ORDER BY product_type ASC, min_quantity ASC"
            );

            $this->view('admin/pricing/index', [
                'tiers' => $tiers
            ]);
        } catch (\Exception $e) {
            error_log('Error loading pricing: ' . $e->getMessage());
            flash('error', 'Unable to load pricing information');
            redirect('admin');
        }
    }

    public function tiers()
    {
        require_role('manager');

        $action = $_GET['action'] ?? 'list';
        $id = $_GET['id'] ?? null;
        
        $tier = null;
        if ($action === 'edit' && $id) {
            $tier = $this->db->fetch(
                "SELECT * FROM pricing_tiers WHERE id = ?",
                [$id]
            );
            
            if (!$tier) {
                flash('error', 'Pricing tier not found');
                redirect('admin/pricing');
                return;
            }
        }

        $this->view('admin/pricing/tiers', [
            'action' => $action,
            'tier' => $tier
        ]);
    }

    public function saveTier()
    {
        require_role('manager');

        if (!$this->validateCSRF()) {
            flash('error', 'Invalid request');
            redirect('admin/pricing');
            return;
        }

        try {
            $id = $_POST['id'] ?? null;
            $productType = $_POST['product_type'] ?? '';
            $minQuantity = $_POST['min_quantity'] ?? 0;
            $maxQuantity = $_POST['max_quantity'] ?? null;
            $pricePerUnit = $_POST['price_per_unit'] ?? 0;
            $discountPercent = $_POST['discount_percent'] ?? 0;
            $isActive = isset($_POST['is_active']) ? 1 : 0;

            // Validation
            if (empty($productType) || $minQuantity < 1 || $pricePerUnit <= 0) {
                flash('error', 'Please fill all required fields correctly');
                redirect('admin/pricing/tiers?action=' . ($id ? 'edit&id=' . $id : 'create'));
                return;
            }

            if ($id) {
                // Update existing tier
                $this->db->query(
                    "UPDATE pricing_tiers 
                     SET product_type = ?, min_quantity = ?, max_quantity = ?, 
                         price_per_unit = ?, discount_percent = ?, is_active = ?
                     WHERE id = ?",
                    [$productType, $minQuantity, $maxQuantity, $pricePerUnit, $discountPercent, $isActive, $id]
                );
                flash('success', 'Pricing tier updated successfully');
            } else {
                // Create new tier
                $this->db->query(
                    "INSERT INTO pricing_tiers 
                     (product_type, min_quantity, max_quantity, price_per_unit, discount_percent, is_active) 
                     VALUES (?, ?, ?, ?, ?, ?)",
                    [$productType, $minQuantity, $maxQuantity, $pricePerUnit, $discountPercent, $isActive]
                );
                flash('success', 'Pricing tier created successfully');
            }

            redirect('admin/pricing');
        } catch (\Exception $e) {
            error_log('Error saving pricing tier: ' . $e->getMessage());
            flash('error', 'Failed to save pricing tier: ' . $e->getMessage());
            redirect('admin/pricing/tiers?action=' . ($id ?? 'create'));
        }
    }

    public function deleteTier()
    {
        require_role('manager');

        if (!$this->validateCSRF()) {
            flash('error', 'Invalid request');
            redirect('admin/pricing');
            return;
        }

        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                flash('error', 'Invalid pricing tier');
                redirect('admin/pricing');
                return;
            }

            $this->db->query("DELETE FROM pricing_tiers WHERE id = ?", [$id]);
            flash('success', 'Pricing tier deleted successfully');
        } catch (\Exception $e) {
            error_log('Error deleting pricing tier: ' . $e->getMessage());
            flash('error', 'Failed to delete pricing tier');
        }

        redirect('admin/pricing');
    }

    public function bottleModelPricing()
    {
        require_role('manager');

        try {
            // Load pricing helper
            require_once __DIR__ . '/../../helpers/pricing_helper.php';

            // Fetch all bottle models
            $bottles = $this->db->fetchAll(
                "SELECT * FROM bottle_models WHERE status = 'active' ORDER BY name ASC"
            );

            $this->view('admin/pricing/bottle_models', [
                'bottles' => $bottles
            ]);
        } catch (\Exception $e) {
            error_log('Error loading bottle pricing: ' . $e->getMessage());
            flash('error', 'Unable to load bottle pricing information');
            redirect('admin/pricing');
        }
    }

    public function assignToBottle()
    {
        require_role('manager');
        
        if (!$this->validateCSRF()) {
            flash('error', 'Invalid request');
            redirect('admin/pricing/bottle-models');
            return;
        }

        $bottleId = $_POST['bottle_id'] ?? null;
        $tierId = $_POST['tier_id'] ?? null;

        if (!$bottleId) {
            flash('error', 'Invalid bottle model');
            redirect('admin/pricing/bottle-models');
            return;
        }

        try {
            // Update bottle model with pricing tier
            $result = $this->db->query(
                "UPDATE bottle_models SET pricing_tier_id = ? WHERE id = ?",
                [$tierId, $bottleId]
            );

            if ($result) {
                flash('success', 'Pricing tier assigned successfully');
            } else {
                flash('error', 'Failed to assign pricing tier');
            }
        } catch (\Exception $e) {
            error_log('Error assigning pricing tier: ' . $e->getMessage());
            flash('error', 'An error occurred while assigning pricing');
        }

        redirect('admin/pricing/bottle-models');
    }

    public function rules()
    {
        require_role('manager');

        try {
            $allRules = $this->db->fetchAll(
                "SELECT * FROM pricing_rules ORDER BY created_at DESC"
            );

            $csrfToken = $this->generateCSRF();
            $this->view('admin/pricing/rules', [
                'csrf_token' => $csrfToken,
                'rules' => $allRules
            ]);
        } catch (\Exception $e) {
            error_log('Error loading pricing rules: ' . $e->getMessage());
            flash('error', 'Unable to load pricing rules');
            $this->redirect(url('admin/pricing'));
        }
    }

    public function calculator()
    {
        require_role('manager');

        $result = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quantity = (int)($_POST['quantity'] ?? 1);
            $productType = sanitize($_POST['product_type'] ?? 'bottle');

            $result = $this->calculatePrice($quantity, $productType);
        }

        $csrfToken = $this->generateCSRF();
        $this->view('admin/pricing/calculator', [
            'csrf_token' => $csrfToken,
            'result' => $result
        ]);
    }

    private function calculatePrice($quantity, $productType = 'bottle')
    {
        // Find applicable pricing tier
        $tier = $this->db->fetch(
            "SELECT * FROM pricing_tiers 
             WHERE product_type = ? 
             AND min_quantity <= ? 
             AND (max_quantity >= ? OR max_quantity IS NULL)
             AND is_active = 1
             ORDER BY min_quantity DESC LIMIT 1",
            [$productType, $quantity, $quantity]
        );

        if (!$tier) {
            // Fallback to default pricing
            $tier = [
                'price_per_unit' => 25.00,
                'discount_percent' => 0,
                'min_quantity' => 1,
                'max_quantity' => null
            ];
        }

        // Calculate pricing
        $basePrice = $tier['price_per_unit'] * $quantity;
        $discount = $basePrice * ($tier['discount_percent'] / 100);
        $subtotal = $basePrice - $discount;
        $taxAmount = $subtotal * 0.18; // 18% GST
        $total = $subtotal + $taxAmount;

        return [
            'quantity' => $quantity,
            'product_type' => $productType,
            'price_per_unit' => $tier['price_per_unit'],
            'base_price' => $basePrice,
            'tier_discount' => $discount,
            'discount_percent' => $tier['discount_percent'],
            'subtotal' => $subtotal,
            'tax_rate' => 18.00,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'tier_range' => $tier['min_quantity'] . '-' . ($tier['max_quantity'] ?? '∞')
        ];
    }
}
