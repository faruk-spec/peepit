-- Migration: Pricing System
-- Description: Complete pricing management system with tiers, rules, and order breakdown
-- Created: 2025-12-09

-- Table: pricing_tiers
-- Stores pricing tiers based on quantity ranges
CREATE TABLE IF NOT EXISTS `pricing_tiers` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_type` ENUM('bottle', 'label', 'printing', 'setup') NOT NULL DEFAULT 'bottle',
  `min_quantity` INT(11) NOT NULL,
  `max_quantity` INT(11) DEFAULT NULL COMMENT 'NULL means unlimited',
  `price_per_unit` DECIMAL(10,2) NOT NULL,
  `discount_percent` DECIMAL(5,2) DEFAULT 0.00,
  `is_active` TINYINT(1) DEFAULT 1,
  `description` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_product_type` (`product_type`),
  INDEX `idx_quantity_range` (`min_quantity`, `max_quantity`),
  INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pricing_rules
-- Custom pricing rules for specific scenarios
CREATE TABLE IF NOT EXISTS `pricing_rules` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rule_name` VARCHAR(255) NOT NULL,
  `rule_type` ENUM('bottle_model', 'bottle_size', 'color_preset', 'customer', 'promotional') NOT NULL,
  `entity_id` INT(11) DEFAULT NULL COMMENT 'ID of bottle, size, color, or customer',
  `min_quantity` INT(11) DEFAULT 1,
  `price_override` DECIMAL(10,2) DEFAULT NULL,
  `discount_percent` DECIMAL(5,2) DEFAULT NULL,
  `valid_from` DATE DEFAULT NULL,
  `valid_until` DATE DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_rule_type` (`rule_type`),
  INDEX `idx_entity` (`entity_id`),
  INDEX `idx_dates` (`valid_from`, `valid_until`),
  INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: order_pricing_breakdown
-- Detailed pricing breakdown for each order
CREATE TABLE IF NOT EXISTS `order_pricing_breakdown` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) UNSIGNED NOT NULL,
  `base_price` DECIMAL(10,2) NOT NULL COMMENT 'Price per unit × quantity',
  `quantity_discount` DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Tier-based discount',
  `custom_fees` DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Setup fees, rush fees, etc.',
  `subtotal` DECIMAL(10,2) NOT NULL COMMENT 'After discounts, before tax',
  `tax_rate` DECIMAL(5,2) DEFAULT 18.00 COMMENT 'Tax percentage (default 18% GST)',
  `tax_amount` DECIMAL(10,2) NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `tier_applied` VARCHAR(100) DEFAULT NULL COMMENT 'Which pricing tier was used',
  `pricing_notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_order` (`order_id`),
  INDEX `idx_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default pricing tiers
INSERT INTO `pricing_tiers` (`product_type`, `min_quantity`, `max_quantity`, `price_per_unit`, `discount_percent`, `description`) VALUES
('bottle', 1, 20, 25.00, 0.00, 'Standard pricing for 1-20 bottles'),
('bottle', 21, 50, 22.00, 12.00, 'Volume discount for 21-50 bottles'),
('bottle', 51, NULL, 20.00, 20.00, 'Best value for 51+ bottles'),
('label', 1, 50, 5.00, 0.00, 'Custom label printing (1-50)'),
('label', 51, 200, 4.50, 10.00, 'Bulk label printing (51-200)'),
('label', 201, NULL, 4.00, 20.00, 'Large volume label printing (201+)'),
('printing', 1, NULL, 3.00, 0.00, 'Direct bottle printing per unit'),
('setup', 1, 1, 50.00, 0.00, 'One-time setup fee for custom designs');

-- Insert sample custom pricing rules
INSERT INTO `pricing_rules` (`rule_name`, `rule_type`, `min_quantity`, `discount_percent`, `valid_from`, `valid_until`, `is_active`) VALUES
('Holiday Season Discount', 'promotional', 10, 15.00, '2024-12-01', '2024-12-31', 1),
('Bulk Order Incentive', 'promotional', 100, 25.00, NULL, NULL, 1),
('First Time Customer', 'customer', 1, 10.00, NULL, NULL, 1);

-- Create view for easy pricing lookup
CREATE OR REPLACE VIEW `v_active_pricing` AS
SELECT 
    pt.id,
    pt.product_type,
    pt.min_quantity,
    pt.max_quantity,
    pt.price_per_unit,
    pt.discount_percent,
    pt.description,
    CASE 
        WHEN pt.max_quantity IS NULL THEN CONCAT(pt.min_quantity, '+')
        ELSE CONCAT(pt.min_quantity, '-', pt.max_quantity)
    END as quantity_range
FROM pricing_tiers pt
WHERE pt.is_active = 1
ORDER BY pt.product_type, pt.min_quantity;

-- Success message
SELECT 'Pricing system tables created successfully!' as Status;
SELECT COUNT(*) as 'Default Pricing Tiers' FROM pricing_tiers WHERE is_active = 1;
SELECT COUNT(*) as 'Sample Pricing Rules' FROM pricing_rules WHERE is_active = 1;
