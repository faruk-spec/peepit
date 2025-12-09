<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\BottleModel;

class BottleController extends Controller
{
    public function index()
    {
        require_role('manager');

        $bottleModel = new BottleModel();
        $bottles = $bottleModel->all();

        $csrfToken = $this->generateCSRF();
        $this->view('admin/bottles/index', [
            'csrf_token' => $csrfToken,
            'bottles' => $bottles
        ]);
    }

    public function create()
    {
        require_role('manager');

        $csrfToken = $this->generateCSRF();
        $this->view('admin/bottles/create', ['csrf_token' => $csrfToken]);
    }

    public function store()
    {
        require_role('manager');
        $this->validateCSRF();

        $name = sanitize($_POST['name'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');

        if (empty($name)) {
            flash('error', 'Bottle name is required');
            set_old($_POST);
            $this->redirect(url('admin/bottles/create'));
        }

        // Handle image upload
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['image'], __DIR__ . '/../../../public/uploads/bottles');
            if (isset($upload['success'])) {
                $image = $upload['filename'];
            } else {
                $errorMsg = isset($upload['error']) ? $upload['error'] : 'Image upload failed';
                flash('error', 'Image Upload Error: ' . $errorMsg);
                set_old($_POST);
                $this->redirect(url('admin/bottles/create'));
            }
        } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Handle other upload errors
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File too large (exceeds server limit)',
                UPLOAD_ERR_FORM_SIZE => 'File too large (exceeds form limit)', 
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'File upload stopped by extension',
            ];
            $errorMsg = isset($errorMessages[$_FILES['image']['error']]) 
                ? $errorMessages[$_FILES['image']['error']] 
                : 'Unknown upload error (code: ' . $_FILES['image']['error'] . ')';
            flash('error', 'Image Upload Error: ' . $errorMsg);
            set_old($_POST);
            $this->redirect(url('admin/bottles/create'));
        }

        try {
            $bottleModel = new BottleModel();
            $bottleModel->create([
                'name' => $name,
                'description' => $description,
                'image' => $image,
                'status' => $status
            ]);

            flash('success', 'Bottle model created successfully');
            clear_old();
            $this->redirect(url('admin/bottles'));
        } catch (\Exception $e) {
            flash('error', 'Failed to create bottle model');
            set_old($_POST);
            $this->redirect(url('admin/bottles/create'));
        }
    }

    public function edit($id)
    {
        require_role('manager');

        $bottleModel = new BottleModel();
        $bottle = $bottleModel->find($id);

        if (!$bottle) {
            flash('error', 'Bottle model not found');
            $this->redirect(url('admin/bottles'));
        }

        $csrfToken = $this->generateCSRF();
        $this->view('admin/bottles/edit', [
            'csrf_token' => $csrfToken,
            'bottle' => $bottle
        ]);
    }

    public function update($id)
    {
        require_role('manager');
        $this->validateCSRF();

        $bottleModel = new BottleModel();
        $bottle = $bottleModel->find($id);

        if (!$bottle) {
            flash('error', 'Bottle model not found');
            $this->redirect(url('admin/bottles'));
        }

        $name = sanitize($_POST['name'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');

        if (empty($name)) {
            flash('error', 'Bottle name is required');
            $this->redirect(url("admin/bottles/edit/{$id}"));
        }

        $image = $bottle['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['image'], __DIR__ . '/../../../public/uploads/bottles');
            if (isset($upload['success'])) {
                // Delete old image
                if ($image && file_exists(__DIR__ . "/../../../public/uploads/bottles/{$image}")) {
                    unlink(__DIR__ . "/../../../public/uploads/bottles/{$image}");
                }
                $image = $upload['filename'];
            } else {
                $errorMsg = isset($upload['error']) ? $upload['error'] : 'Image upload failed';
                flash('error', 'Image Upload Error: ' . $errorMsg);
                $this->redirect(url("admin/bottles/edit/{$id}"));
            }
        } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Handle other upload errors
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File too large (exceeds server limit)',
                UPLOAD_ERR_FORM_SIZE => 'File too large (exceeds form limit)',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'File upload stopped by extension',
            ];
            $errorMsg = isset($errorMessages[$_FILES['image']['error']])
                ? $errorMessages[$_FILES['image']['error']]
                : 'Unknown upload error (code: ' . $_FILES['image']['error'] . ')';
            flash('error', 'Image Upload Error: ' . $errorMsg);
            $this->redirect(url("admin/bottles/edit/{$id}"));
        }

        try {
            $bottleModel->update($id, [
                'name' => $name,
                'description' => $description,
                'image' => $image,
                'status' => $status
            ]);

            flash('success', 'Bottle model updated successfully');
            $this->redirect(url('admin/bottles'));
        } catch (\Exception $e) {
            flash('error', 'Failed to update bottle model');
            $this->redirect(url("admin/bottles/edit/{$id}"));
        }
    }

    public function delete($id)
    {
        require_role('manager');
        $this->validateCSRF();

        $bottleModel = new BottleModel();
        $bottle = $bottleModel->find($id);

        if (!$bottle) {
            flash('error', 'Bottle model not found');
            $this->redirect(url('admin/bottles'));
        }

        try {
            // Delete image
            if ($bottle['image'] && file_exists(__DIR__ . "/../../../public/uploads/bottles/{$bottle['image']}")) {
                unlink(__DIR__ . "/../../../public/uploads/bottles/{$bottle['image']}");
            }

            $bottleModel->delete($id);
            flash('success', 'Bottle model deleted successfully');
        } catch (\Exception $e) {
            flash('error', 'Failed to delete bottle model. It may be in use.');
        }

        $this->redirect(url('admin/bottles'));
    }

    public function pricing($id)
    {
        require_role('manager');

        $bottleModel = new BottleModel();
        $bottle = $bottleModel->find($id);

        if (!$bottle) {
            flash('error', 'Bottle model not found');
            $this->redirect(url('admin/bottles'));
        }

        // Get all pricing tiers
        $pricingTiers = $this->db->fetchAll(
            "SELECT * FROM pricing_tiers WHERE product_type = 'bottle' AND is_active = 1 ORDER BY min_quantity ASC"
        );

        // Get current pricing assignment
        $currentPricing = $this->db->fetch(
            "SELECT pricing_tier_id FROM bottle_models WHERE id = ?",
            [$id]
        );

        // Get custom pricing tiers for this bottle
        $customTiers = $this->db->fetchAll(
            "SELECT * FROM bottle_model_pricing WHERE bottle_model_id = ? ORDER BY min_quantity ASC",
            [$id]
        );

        $csrfToken = $this->generateCSRF();
        $this->view('admin/bottles/pricing', [
            'csrf_token' => $csrfToken,
            'bottle' => $bottle,
            'pricingTiers' => $pricingTiers,
            'currentPricingTierId' => $currentPricing['pricing_tier_id'] ?? null,
            'customTiers' => $customTiers
        ]);
    }

    public function savePricing($id)
    {
        require_role('manager');
        $this->validateCSRF();

        $bottleModel = new BottleModel();
        $bottle = $bottleModel->find($id);

        if (!$bottle) {
            flash('error', 'Bottle model not found');
            $this->redirect(url('admin/bottles'));
        }

        $pricingTierId = sanitize($_POST['pricing_tier_id'] ?? '');

        // Allow NULL for general pricing
        if ($pricingTierId === '' || $pricingTierId === 'general') {
            $pricingTierId = null;
        }

        try {
            $this->db->query(
                "UPDATE bottle_models SET pricing_tier_id = ? WHERE id = ?",
                [$pricingTierId, $id]
            );

            flash('success', 'Pricing assigned successfully');
        } catch (\Exception $e) {
            error_log('Failed to assign pricing: ' . $e->getMessage());
            flash('error', 'Failed to assign pricing. Please try again.');
        }

        $this->redirect(url("admin/bottles/{$id}/pricing"));
    }

    public function savePricingTier($id)
    {
        require_role('manager');
        $this->validateCSRF();

        $bottleModel = new BottleModel();
        $bottle = $bottleModel->find($id);

        if (!$bottle) {
            flash('error', 'Bottle model not found');
            $this->redirect(url('admin/bottles'));
        }

        $minQty = (int)($_POST['min_quantity'] ?? 0);
        $maxQty = !empty($_POST['max_quantity']) ? (int)$_POST['max_quantity'] : null;
        $price = (float)($_POST['price_per_unit'] ?? 0);
        $discount = (float)($_POST['discount_percent'] ?? 0);
        $isActive = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;
        $validFrom = !empty($_POST['valid_from']) ? sanitize($_POST['valid_from']) : null;
        $validUntil = !empty($_POST['valid_until']) ? sanitize($_POST['valid_until']) : null;

        // Validation
        if ($minQty < 1) {
            flash('error', 'Minimum quantity must be at least 1');
            $this->redirect(url("admin/bottles/{$id}/pricing"));
        }

        if ($maxQty && $maxQty <= $minQty) {
            flash('error', 'Maximum quantity must be greater than minimum quantity');
            $this->redirect(url("admin/bottles/{$id}/pricing"));
        }

        if ($price <= 0) {
            flash('error', 'Price must be greater than 0');
            $this->redirect(url("admin/bottles/{$id}/pricing"));
        }

        // Check for overlapping ranges
        $existingTiers = $this->db->fetchAll(
            "SELECT * FROM bottle_model_pricing WHERE bottle_model_id = ?",
            [$id]
        );

        foreach ($existingTiers as $tier) {
            $tierMin = (int)$tier['min_quantity'];
            $tierMax = $tier['max_quantity'] ? (int)$tier['max_quantity'] : PHP_INT_MAX;
            $newMax = $maxQty ?? PHP_INT_MAX;

            if (($minQty >= $tierMin && $minQty <= $tierMax) || 
                ($newMax >= $tierMin && $newMax <= $tierMax) ||
                ($minQty <= $tierMin && $newMax >= $tierMax)) {
                flash('error', 'Quantity range overlaps with existing tier');
                $this->redirect(url("admin/bottles/{$id}/pricing"));
            }
        }

        try {
            $this->db->query(
                "INSERT INTO bottle_model_pricing 
                (bottle_model_id, min_quantity, max_quantity, price_per_unit, discount_percent, is_active, valid_from, valid_until) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                [$id, $minQty, $maxQty, $price, $discount, $isActive, $validFrom, $validUntil]
            );

            flash('success', 'Pricing tier added successfully');
        } catch (\Exception $e) {
            error_log('Failed to add pricing tier: ' . $e->getMessage());
            flash('error', 'Failed to add pricing tier. Please try again.');
        }

        $this->redirect(url("admin/bottles/{$id}/pricing"));
    }

    public function updatePricingTier($id, $tierId)
    {
        require_role('manager');
        $this->validateCSRF();

        $bottleModel = new BottleModel();
        $bottle = $bottleModel->find($id);

        if (!$bottle) {
            flash('error', 'Bottle model not found');
            $this->redirect(url('admin/bottles'));
        }

        // Verify tier belongs to this bottle
        $tier = $this->db->fetch(
            "SELECT * FROM bottle_model_pricing WHERE id = ? AND bottle_model_id = ?",
            [$tierId, $id]
        );

        if (!$tier) {
            flash('error', 'Pricing tier not found');
            $this->redirect(url("admin/bottles/{$id}/pricing"));
        }

        $minQty = (int)($_POST['min_quantity'] ?? 0);
        $maxQty = !empty($_POST['max_quantity']) ? (int)$_POST['max_quantity'] : null;
        $price = (float)($_POST['price_per_unit'] ?? 0);
        $discount = (float)($_POST['discount_percent'] ?? 0);
        $isActive = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;
        $validFrom = !empty($_POST['valid_from']) ? sanitize($_POST['valid_from']) : null;
        $validUntil = !empty($_POST['valid_until']) ? sanitize($_POST['valid_until']) : null;

        // Validation
        if ($minQty < 1) {
            flash('error', 'Minimum quantity must be at least 1');
            $this->redirect(url("admin/bottles/{$id}/pricing"));
        }

        if ($maxQty && $maxQty <= $minQty) {
            flash('error', 'Maximum quantity must be greater than minimum quantity');
            $this->redirect(url("admin/bottles/{$id}/pricing"));
        }

        if ($price <= 0) {
            flash('error', 'Price must be greater than 0');
            $this->redirect(url("admin/bottles/{$id}/pricing"));
        }

        // Check for overlapping ranges (exclude current tier)
        $existingTiers = $this->db->fetchAll(
            "SELECT * FROM bottle_model_pricing WHERE bottle_model_id = ? AND id != ?",
            [$id, $tierId]
        );

        foreach ($existingTiers as $existingTier) {
            $tierMin = (int)$existingTier['min_quantity'];
            $tierMax = $existingTier['max_quantity'] ? (int)$existingTier['max_quantity'] : PHP_INT_MAX;
            $newMax = $maxQty ?? PHP_INT_MAX;

            if (($minQty >= $tierMin && $minQty <= $tierMax) || 
                ($newMax >= $tierMin && $newMax <= $tierMax) ||
                ($minQty <= $tierMin && $newMax >= $tierMax)) {
                flash('error', 'Quantity range overlaps with existing tier');
                $this->redirect(url("admin/bottles/{$id}/pricing"));
            }
        }

        try {
            $this->db->query(
                "UPDATE bottle_model_pricing 
                SET min_quantity = ?, max_quantity = ?, price_per_unit = ?, discount_percent = ?, 
                    is_active = ?, valid_from = ?, valid_until = ?
                WHERE id = ? AND bottle_model_id = ?",
                [$minQty, $maxQty, $price, $discount, $isActive, $validFrom, $validUntil, $tierId, $id]
            );

            flash('success', 'Pricing tier updated successfully');
        } catch (\Exception $e) {
            error_log('Failed to update pricing tier: ' . $e->getMessage());
            flash('error', 'Failed to update pricing tier. Please try again.');
        }

        $this->redirect(url("admin/bottles/{$id}/pricing"));
    }

    public function deletePricingTier($id, $tierId)
    {
        require_role('manager');
        $this->validateCSRF();

        $bottleModel = new BottleModel();
        $bottle = $bottleModel->find($id);

        if (!$bottle) {
            flash('error', 'Bottle model not found');
            $this->redirect(url('admin/bottles'));
        }

        // Verify tier belongs to this bottle
        $tier = $this->db->fetch(
            "SELECT * FROM bottle_model_pricing WHERE id = ? AND bottle_model_id = ?",
            [$tierId, $id]
        );

        if (!$tier) {
            flash('error', 'Pricing tier not found');
            $this->redirect(url("admin/bottles/{$id}/pricing"));
        }

        try {
            $this->db->query(
                "DELETE FROM bottle_model_pricing WHERE id = ? AND bottle_model_id = ?",
                [$tierId, $id]
            );

            flash('success', 'Pricing tier deleted successfully');
        } catch (\Exception $e) {
            error_log('Failed to delete pricing tier: ' . $e->getMessage());
            flash('error', 'Failed to delete pricing tier. Please try again.');
        }

        $this->redirect(url("admin/bottles/{$id}/pricing"));
    }
}
