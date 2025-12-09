-- Traffic Tracking System Database Schema
-- Run this migration to create all necessary tables for the traffic tracking feature

-- Table: traffic_logs
-- Stores all page views and visitor tracking data
CREATE TABLE IF NOT EXISTS `traffic_logs` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `visitor_id` VARCHAR(64) NOT NULL,
  `session_id` VARCHAR(64) NOT NULL,
  `page_url` VARCHAR(500) NOT NULL,
  `referrer` VARCHAR(500) DEFAULT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` TEXT,
  `device_type` VARCHAR(50) DEFAULT NULL COMMENT 'desktop, mobile, tablet',
  `browser` VARCHAR(100) DEFAULT NULL,
  `os` VARCHAR(100) DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `utm_source` VARCHAR(255) DEFAULT NULL,
  `utm_medium` VARCHAR(255) DEFAULT NULL,
  `utm_campaign` VARCHAR(255) DEFAULT NULL,
  `utm_term` VARCHAR(255) DEFAULT NULL,
  `utm_content` VARCHAR(255) DEFAULT NULL,
  `is_bot` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_visitor` (`visitor_id`),
  INDEX `idx_session` (`session_id`),
  INDEX `idx_created` (`created_at`),
  INDEX `idx_page` (`page_url`(255)),
  INDEX `idx_is_bot` (`is_bot`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: visitor_sessions
-- Manages visitor sessions with metrics
CREATE TABLE IF NOT EXISTS `visitor_sessions` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` VARCHAR(64) NOT NULL UNIQUE,
  `visitor_id` VARCHAR(64) NOT NULL,
  `started_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `ended_at` TIMESTAMP NULL DEFAULT NULL,
  `page_count` INT(11) DEFAULT 1,
  `duration` INT(11) DEFAULT 0 COMMENT 'Session duration in seconds',
  `bounce` TINYINT(1) DEFAULT 1 COMMENT '1 if single page visit',
  `entry_page` VARCHAR(500) DEFAULT NULL,
  `exit_page` VARCHAR(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`),
  INDEX `idx_visitor` (`visitor_id`),
  INDEX `idx_started` (`started_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: conversion_goals
-- Defines conversion goals for tracking
CREATE TABLE IF NOT EXISTS `conversion_goals` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `type` ENUM('url', 'event') NOT NULL DEFAULT 'url',
  `value` VARCHAR(500) NOT NULL COMMENT 'URL pattern or event name',
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: conversion_events
-- Records when goals are achieved
CREATE TABLE IF NOT EXISTS `conversion_events` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `goal_id` INT(11) UNSIGNED NOT NULL,
  `visitor_id` VARCHAR(64) NOT NULL,
  `session_id` VARCHAR(64) NOT NULL,
  `page_url` VARCHAR(500) DEFAULT NULL,
  `value` DECIMAL(10,2) DEFAULT NULL COMMENT 'Optional monetary value',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`goal_id`) REFERENCES `conversion_goals`(`id`) ON DELETE CASCADE,
  INDEX `idx_goal` (`goal_id`),
  INDEX `idx_visitor` (`visitor_id`),
  INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample conversion goals
INSERT INTO `conversion_goals` (`name`, `description`, `type`, `value`, `is_active`) VALUES
('Order Completed', 'Track when users complete an order', 'url', '/order/success', 1),
('Contact Form Submitted', 'Track contact form submissions', 'url', '/contact/thank-you', 1),
('User Registration', 'Track new user signups', 'url', '/register/success', 1);

