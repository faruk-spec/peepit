<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class PricingController extends Controller
{
    public function index()
    {
        require_role('manager');

        try {
            // Fetch all active pricing tiers
            $bottleTiers = $this->db->fetchAll(
                "SELECT * FROM pricing_tiers WHERE product_type = 'bottle' AND is_active = 1 ORDER BY min_quantity ASC"
            );
            
            $labelTiers = $this->db->fetchAll(
                "SELECT * FROM pricing_tiers WHERE product_type = 'label' AND is_active = 1 ORDER BY min_quantity ASC"
            );
            
            $printingTiers = $this->db->fetchAll(
                "SELECT * FROM pricing_tiers WHERE product_type = 'printing' AND is_active = 1 ORDER BY min_quantity ASC"
            );
            
            // Fetch active rules
            $activeRules = $this->db->fetchAll(
                "SELECT * FROM pricing_rules WHERE is_active = 1 ORDER BY created_at DESC LIMIT 10"
            );
            
            // Calculate statistics
            $stats = [
                'total_tiers' => $this->db->fetch("SELECT COUNT(*) as count FROM pricing_tiers WHERE is_active = 1")['count'],
                'total_rules' => $this->db->fetch("SELECT COUNT(*) as count FROM pricing_rules WHERE is_active = 1")['count'],
                'avg_bottle_price' => $this->db->fetch("SELECT AVG(price_per_unit) as avg FROM pricing_tiers WHERE product_type = 'bottle' AND is_active = 1")['avg'],
            ];

            $csrfToken = $this->generateCSRF();
            $this->view('admin/pricing/index', [
                'csrf_token' => $csrfToken,
                'bottle_tiers' => $bottleTiers,
                'label_tiers' => $labelTiers,
                'printing_tiers' => $printingTiers,
                'active_rules' => $activeRules,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            error_log('Error loading pricing: ' . $e->getMessage());
            flash('error', 'Unable to load pricing information');
            $this->redirect(url('admin'));
        }
    }

    public function tiers()
    {
        require_role('manager');

        try {
            $allTiers = $this->db->fetchAll(
                "SELECT * FROM pricing_tiers ORDER BY product_type, min_quantity ASC"
            );

            $csrfToken = $this->generateCSRF();
            $this->view('admin/pricing/tiers', [
                'csrf_token' => $csrfToken,
                'tiers' => $allTiers
            ]);
        } catch (\Exception $e) {
            error_log('Error loading pricing tiers: ' . $e->getMessage());
            flash('error', 'Unable to load pricing tiers');
            $this->redirect(url('admin/pricing'));
        }
    }

    public function saveTier()
    {
        require_role('manager');
        $this->validateCSRF();

        $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
        $productType = sanitize($_POST['product_type'] ?? 'bottle');
        $minQuantity = (int)($_POST['min_quantity'] ?? 1);
        $maxQuantity = !empty($_POST['max_quantity']) ? (int)$_POST['max_quantity'] : null;
        $pricePerUnit = (float)($_POST['price_per_unit'] ?? 0);
        $discountPercent = (float)($_POST['discount_percent'] ?? 0);
        $description = sanitize($_POST['description'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($pricePerUnit <= 0) {
            flash('error', 'Price per unit must be greater than zero');
            $this->redirect(url('admin/pricing/tiers'));
        }

        try {
            if ($id) {
                // Update existing tier
                $this->db->query(
                    "UPDATE pricing_tiers SET 
                        product_type = ?, min_quantity = ?, max_quantity = ?, 
                        price_per_unit = ?, discount_percent = ?, description = ?, is_active = ?
                     WHERE id = ?",
                    [$productType, $minQuantity, $maxQuantity, $pricePerUnit, $discountPercent, $description, $isActive, $id]
                );
                flash('success', 'Pricing tier updated successfully');
            } else {
                // Create new tier
                $this->db->query(
                    "INSERT INTO pricing_tiers (product_type, min_quantity, max_quantity, price_per_unit, discount_percent, description, is_active)
                     VALUES (?, ?, ?, ?, ?, ?, ?)",
                    [$productType, $minQuantity, $maxQuantity, $pricePerUnit, $discountPercent, $description, $isActive]
                );
                flash('success', 'Pricing tier created successfully');
            }

            $this->redirect(url('admin/pricing/tiers'));
        } catch (\Exception $e) {
            error_log('Error saving pricing tier: ' . $e->getMessage());
            flash('error', 'Failed to save pricing tier');
            $this->redirect(url('admin/pricing/tiers'));
        }
    }

    public function deleteTier($id)
    {
        require_role('manager');

        try {
            // Soft delete by setting is_active = 0
            $this->db->query("UPDATE pricing_tiers SET is_active = 0 WHERE id = ?", [$id]);
            flash('success', 'Pricing tier deactivated successfully');
        } catch (\Exception $e) {
            error_log('Error deleting pricing tier: ' . $e->getMessage());
            flash('error', 'Failed to deactivate pricing tier');
        }

        $this->redirect(url('admin/pricing/tiers'));
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
