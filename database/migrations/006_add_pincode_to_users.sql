-- Migration: Add pincode field to users table
-- Date: 2025-12-09

ALTER TABLE `users` 
ADD COLUMN `pincode` varchar(6) DEFAULT NULL AFTER `phone`;
