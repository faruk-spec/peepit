<?php

/**
 * Pricing Helper Functions
 * Calculate prices for bottles with support for bottle-specific pricing
 */

use App\Core\Database;

/**
 * Calculate price for a specific bottle model and quantity
 * 
 * @param int $bottleModelId The bottle model ID
 * @param int $quantity The order quantity
 * @return array Price breakdown with base_price, discount, subtotal, tax, total
 */
function calculateBottlePrice($bottleModelId, $quantity) {
    $db = Database::getInstance()->getConnection();
    
    // Priority 1: Check for custom bottle-specific pricing
    $customPricing = getBottleModelPricing($bottleModelId, $quantity, $db);
    if ($customPricing) {
        return calculatePriceFromTier($customPricing, $quantity);
    }
    
    // Priority 2: Check if bottle has a linked pricing tier
    $stmt = $db->prepare("
        SELECT pt.* 
        FROM bottle_models bm
        JOIN pricing_tiers pt ON bm.pricing_tier_id = pt.id
        WHERE bm.id = ? AND bm.status = 'active' AND pt.is_active = 1
    ");
    $stmt->execute([$bottleModelId]);
    $linkedTier = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($linkedTier) {
        return calculatePriceFromTier($linkedTier, $quantity);
    }
    
    // Priority 3: Fall back to general bottle pricing
    $stmt = $db->prepare("
        SELECT * FROM pricing_tiers 
        WHERE product_type = 'bottle' 
        AND is_active = 1
        AND min_quantity <= ?
        AND (max_quantity >= ? OR max_quantity IS NULL)
        ORDER BY min_quantity DESC
        LIMIT 1
    ");
    $stmt->execute([$quantity, $quantity]);
    $generalTier = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($generalTier) {
        return calculatePriceFromTier($generalTier, $quantity);
    }
    
    // Fallback: No pricing found, use a default
    return [
        'base_price' => 25.00 * $quantity,
        'price_per_unit' => 25.00,
        'tier_discount' => 0.00,
        'subtotal' => 25.00 * $quantity,
        'tax_rate' => 18.00,
        'tax_amount' => (25.00 * $quantity) * 0.18,
        'total' => (25.00 * $quantity) * 1.18,
        'pricing_type' => 'default',
        'tier_name' => 'Default Pricing'
    ];
}

/**
 * Get bottle-specific pricing for quantity
 */
function getBottleModelPricing($bottleModelId, $quantity, $db) {
    $stmt = $db->prepare("
        SELECT * FROM bottle_model_pricing
        WHERE bottle_model_id = ?
        AND is_active = 1
        AND min_quantity <= ?
        AND (max_quantity >= ? OR max_quantity IS NULL)
        AND (valid_from IS NULL OR valid_from <= CURDATE())
        AND (valid_until IS NULL OR valid_until >= CURDATE())
        ORDER BY min_quantity DESC
        LIMIT 1
    ");
    $stmt->execute([$bottleModelId, $quantity, $quantity]);
    $pricing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($pricing) {
        $pricing['product_type'] = 'bottle_custom';
        $pricing['pricing_type'] = 'custom';
    }
    
    return $pricing;
}

/**
 * Calculate price breakdown from a pricing tier
 */
function calculatePriceFromTier($tier, $quantity) {
    $pricePerUnit = (float)$tier['price_per_unit'];
    $discountPercent = (float)($tier['discount_percent'] ?? 0);
    
    $basePrice = $pricePerUnit * $quantity;
    $tierDiscount = $basePrice * ($discountPercent / 100);
    $subtotal = $basePrice - $tierDiscount;
    
    $taxRate = 18.00; // 18% GST
    $taxAmount = $subtotal * ($taxRate / 100);
    $total = $subtotal + $taxAmount;
    
    return [
        'base_price' => round($basePrice, 2),
        'price_per_unit' => round($pricePerUnit, 2),
        'tier_discount' => round($tierDiscount, 2),
        'discount_percent' => $discountPercent,
        'subtotal' => round($subtotal, 2),
        'tax_rate' => $taxRate,
        'tax_amount' => round($taxAmount, 2),
        'total' => round($total, 2),
        'pricing_type' => $tier['pricing_type'] ?? ($tier['product_type'] ?? 'general'),
        'tier_name' => $tier['description'] ?? 'Standard Pricing',
        'tier_id' => $tier['id'] ?? null
    ];
}

/**
 * Get all active pricing tiers
 */
function getAllPricingTiers() {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("
        SELECT * FROM pricing_tiers 
        WHERE is_active = 1 
        ORDER BY product_type, min_quantity
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get pricing tiers for a specific product type
 */
function getPricingTiersByType($productType = 'bottle') {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("
        SELECT * FROM pricing_tiers 
        WHERE product_type = ? AND is_active = 1 
        ORDER BY min_quantity
    ");
    $stmt->execute([$productType]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get bottle model's assigned pricing tier
 */
function getBottlePricingTier($bottleModelId) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("
        SELECT pt.* FROM pricing_tiers pt
        JOIN bottle_models bm ON bm.pricing_tier_id = pt.id
        WHERE bm.id = ? AND pt.is_active = 1
    ");
    $stmt->execute([$bottleModelId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get all custom pricing for a bottle model
 */
function getBottleCustomPricing($bottleModelId) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("
        SELECT * FROM bottle_model_pricing
        WHERE bottle_model_id = ? AND is_active = 1
        ORDER BY min_quantity
    ");
    $stmt->execute([$bottleModelId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
