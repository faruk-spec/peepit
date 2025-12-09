-- Migration: Add Bottle Model Pricing Assignment
-- Description: Enable custom pricing per bottle model
-- Created: 2025-12-09

-- Add pricing_tier_id to bottle_models table
ALTER TABLE `bottle_models` 
ADD COLUMN `pricing_tier_id` INT(11) UNSIGNED NULL DEFAULT NULL 
COMMENT 'Linked pricing tier for this bottle model (NULL = use general pricing)',
ADD INDEX `idx_pricing_tier` (`pricing_tier_id`);

-- Create bottle_model_pricing table for custom pricing per bottle
CREATE TABLE IF NOT EXISTS `bottle_model_pricing` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bottle_model_id` INT(11) UNSIGNED NOT NULL,
  `min_quantity` INT(11) NOT NULL DEFAULT 1,
  `max_quantity` INT(11) DEFAULT NULL COMMENT 'NULL means unlimited',
  `price_per_unit` DECIMAL(10,2) NOT NULL,
  `discount_percent` DECIMAL(5,2) DEFAULT 0.00 COMMENT 'Additional discount on this price',
  `is_active` TINYINT(1) DEFAULT 1,
  `valid_from` DATE DEFAULT NULL COMMENT 'Start date for seasonal pricing',
  `valid_until` DATE DEFAULT NULL COMMENT 'End date for seasonal pricing',
  `description` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_bottle_model` (`bottle_model_id`),
  INDEX `idx_quantity_range` (`min_quantity`, `max_quantity`),
  INDEX `idx_active` (`is_active`),
  INDEX `idx_dates` (`valid_from`, `valid_until`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample bottle pricing assignments (optional - customize as needed)
-- Assumes bottle_models with IDs 1, 2, 3 exist
-- You can remove these if you prefer to assign manually via admin panel

-- Example: Assign bottle ID 1 to pricing tier ID 2 (volume discount tier)
-- UPDATE `bottle_models` SET `pricing_tier_id` = 2 WHERE `id` = 1;

-- Example: Custom pricing for premium bottle (ID 2)
-- INSERT INTO `bottle_model_pricing` (`bottle_model_id`, `min_quantity`, `max_quantity`, `price_per_unit`, `discount_percent`, `description`) VALUES
-- (2, 1, 10, 40.00, 0.00, 'Premium bottle - small quantities'),
-- (2, 11, 25, 38.00, 5.00, 'Premium bottle - medium volume'),
-- (2, 26, NULL, 35.00, 12.50, 'Premium bottle - bulk pricing');

-- Create view for easy bottle pricing lookup
CREATE OR REPLACE VIEW `v_bottle_pricing` AS
SELECT 
    bm.id as bottle_model_id,
    bm.name as bottle_name,
    bm.pricing_tier_id,
    pt.description as pricing_tier_name,
    pt.price_per_unit as tier_price,
    bmp.id as custom_pricing_id,
    bmp.min_quantity,
    bmp.max_quantity,
    bmp.price_per_unit as custom_price,
    bmp.discount_percent,
    bmp.is_active,
    bmp.valid_from,
    bmp.valid_until,
    CASE 
        WHEN bmp.id IS NOT NULL AND bmp.is_active = 1 
            AND (bmp.valid_from IS NULL OR bmp.valid_from <= CURDATE())
            AND (bmp.valid_until IS NULL OR bmp.valid_until >= CURDATE())
        THEN 'custom'
        WHEN bm.pricing_tier_id IS NOT NULL THEN 'tier'
        ELSE 'general'
    END as pricing_type
FROM bottle_models bm
LEFT JOIN pricing_tiers pt ON bm.pricing_tier_id = pt.id AND pt.is_active = 1
LEFT JOIN bottle_model_pricing bmp ON bm.id = bmp.bottle_model_id
WHERE bm.status = 'active'
ORDER BY bm.name, bmp.min_quantity;

-- Success messages
SELECT 'Bottle model pricing system added successfully!' as Status;
SELECT 'Run UPDATE queries above to assign pricing to specific bottles' as Note;
