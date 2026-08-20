-- CMS Tables for Peepit
-- Create tables for custom pages, navigation, and hero slider

-- Pages Table - For custom CMS pages
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Navigation Items Table - For dynamic navigation menu
CREATE TABLE IF NOT EXISTS `navigation_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL COMMENT 'For dropdown/subdropdown support',
  `label` varchar(255) NOT NULL,
  `url` varchar(500) NOT NULL,
  `target` enum('_self','_blank') NOT NULL DEFAULT '_self',
  `icon` varchar(100) DEFAULT NULL COMMENT 'Icon class for menu item',
  `visible_to` enum('all','guests','users','admin') NOT NULL DEFAULT 'all',
  `order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `order` (`order`),
  KEY `status` (`status`),
  FOREIGN KEY (`parent_id`) REFERENCES `navigation_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Hero Slider Table - For homepage hero section images
CREATE TABLE IF NOT EXISTS `hero_slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT 'Optional title override for slide',
  `description` text COMMENT 'Optional description override for slide',
  `image` varchar(255) NOT NULL COMMENT 'Image filename in uploads/hero/',
  `image_alt` varchar(255) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL COMMENT 'Optional button text override',
  `button_url` varchar(500) DEFAULT NULL COMMENT 'Optional button URL override',
  `order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order` (`order`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Home Page Content Table - For managing editable homepage sections
CREATE TABLE IF NOT EXISTS `home_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(100) NOT NULL COMMENT 'Section identifier: hero_title, hero_description, how_it_works, etc.',
  `content` longtext NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `section` (`section`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default home content
INSERT INTO `home_content` (`section`, `content`) VALUES
('hero_title', 'Welcome to Peepit'),
('hero_description', 'Create Your Perfect Custom Water Bottle'),
('hero_button_text', 'Get Started'),
('how_it_works_title', 'How It Works'),
('how_it_works_description', 'Simple steps to get your custom water bottle'),
('why_choose_title', 'Why Choose Peepit?'),
('cta_title', 'Ready to Create Your Custom Bottle?'),
('cta_description', 'Join thousands of satisfied customers who trust Peepit for their custom water bottle needs'),
('stats_title', 'Trusted by Thousands')
ON DUPLICATE KEY UPDATE content=VALUES(content);

-- Insert default navigation items
INSERT INTO `navigation_items` (`label`, `url`, `visible_to`, `order`, `status`) VALUES
('Home', '/', 'all', 1, 'active'),
('About Us', '/page/about-us', 'all', 2, 'active'),
('Catalog', '/page/catalog', 'all', 3, 'active'),
('Contact Us', '/page/contact-us', 'all', 4, 'active'),
('Login', '/login', 'guests', 5, 'active'),
('Register', '/register', 'guests', 6, 'active'),
('My Orders', '/my-orders', 'users', 7, 'active'),
('Profile', '/profile', 'users', 8, 'active')
ON DUPLICATE KEY UPDATE label=VALUES(label);

-- Create default pages
INSERT INTO `pages` (`title`, `slug`, `content`, `status`) VALUES
('About Us', 'about-us', '<h1>About Peepit</h1><p>Welcome to Peepit - your trusted partner for custom water bottles.</p><p>This content can be edited from the admin panel.</p>', 'published'),
('Catalog', 'catalog', '<h1>Our Catalog</h1><p>Browse our collection of custom water bottles.</p><p>This content can be edited from the admin panel.</p>', 'published'),
('Contact Us', 'contact-us', '<h1>Contact Us</h1><p>Get in touch with us for any inquiries.</p><p>This content can be edited from the admin panel.</p>', 'published'),
('Privacy Policy', 'privacy-policy', '<h1>Privacy Policy</h1><p>Your privacy is important to us.</p><p>This content can be edited from the admin panel.</p>', 'published'),
('Terms and Conditions', 'terms-and-conditions', '<h1>Terms and Conditions</h1><p>Please read these terms carefully.</p><p>This content can be edited from the admin panel.</p>', 'published')
ON DUPLICATE KEY UPDATE title=VALUES(title);
