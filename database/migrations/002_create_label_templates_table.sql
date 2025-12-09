-- Migration: Create label_templates table
-- Description: Table for storing label template designs that customers can use

CREATE TABLE IF NOT EXISTS `label_templates` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `category` VARCHAR(100) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL COMMENT 'Preview image filename',
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert some sample templates (image will use DEFAULT NULL from table definition)
INSERT INTO `label_templates` (`name`, `category`, `status`) VALUES
('Classic Label', 'Business', 'active'),
('Modern Minimalist', 'Business', 'active'),
('Vintage Style', 'Retro', 'active');
