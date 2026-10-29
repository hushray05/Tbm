-- ====================================================================
-- TBM Enterprise Suite & Uganda Multi-Business SaaS Database Dump
-- Exported on: 2026-09-09 17:34:12
-- Database: taskbusinessma_tbm
-- Server: MySQL 8.0+ / MariaDB 10.4+
-- ====================================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Structure for table `audit_logs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `entity_type` varchar(255) DEFAULT NULL,
  `entity_id` bigint(20) unsigned DEFAULT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `audit_logs`
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `description`, `ip_address`, `metadata`, `created_at`, `updated_at`) VALUES ('1', '1', 'system_initialization', 'system', '1', 'TBM Enterprise Suite & Uganda UGX Multi-Business SaaS System initialized successfully.', '127.0.0.1', '{\"platform_currency\":\"UGX\",\"trial_days\":30,\"plans_seeded\":3}', '2026-09-03 16:05:54', NULL);
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `description`, `ip_address`, `metadata`, `created_at`, `updated_at`) VALUES ('2', NULL, 'create_trial', 'subscription', '4', 'New 30-day free trial created for business \'hushrayr\' on Professional plan.', '127.0.0.1', NULL, '2026-09-09 15:49:44', '2026-09-09 15:49:44');
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `description`, `ip_address`, `metadata`, `created_at`, `updated_at`) VALUES ('3', '8', 'payment_received', 'subscription_payment', '2', 'Received UGX 40,000 for subscription #4 (MTN Mobile Money). Ref: TXN-UGX-CNABYSLPSX', '127.0.0.1', NULL, '2026-09-09 16:11:16', '2026-09-09 16:11:16');

-- --------------------------------------------------------
-- Structure for table `bill_items`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `bill_items`;
CREATE TABLE `bill_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bill_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_cost_ugx` bigint(20) NOT NULL,
  `total_cost_ugx` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bill_items_bill_id_foreign` (`bill_id`),
  KEY `bill_items_product_id_foreign` (`product_id`),
  CONSTRAINT `bill_items_bill_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bill_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `bills`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `bills`;
CREATE TABLE `bills` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `bill_number` varchar(255) NOT NULL,
  `party_id` bigint(20) unsigned DEFAULT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `bill_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `subtotal_ugx` bigint(20) NOT NULL DEFAULT 0,
  `vat_rate_percent` decimal(5,2) NOT NULL DEFAULT 18.00,
  `vat_amount_ugx` bigint(20) NOT NULL DEFAULT 0,
  `total_amount_ugx` bigint(20) NOT NULL DEFAULT 0,
  `paid_amount_ugx` bigint(20) NOT NULL DEFAULT 0,
  `balance_ugx` bigint(20) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bills_bill_number_unique` (`bill_number`),
  KEY `bills_business_id_foreign` (`business_id`),
  KEY `bills_party_id_foreign` (`party_id`),
  KEY `bills_order_id_foreign` (`order_id`),
  CONSTRAINT `bills_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bills_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bills_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `business_locations`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `business_locations`;
CREATE TABLE `business_locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL DEFAULT 'Kampala',
  `phone` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `business_locations_business_id_foreign` (`business_id`),
  CONSTRAINT `business_locations_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `business_locations`
INSERT INTO `business_locations` (`id`, `business_id`, `name`, `address`, `city`, `phone`, `is_primary`, `created_at`, `updated_at`) VALUES ('4', '4', 'Headquarters / Main Depot', NULL, 'Kampala', '6744556455', '1', '2026-09-09 15:49:43', '2026-09-09 15:49:43');

-- --------------------------------------------------------
-- Structure for table `businesses`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `businesses`;
CREATE TABLE `businesses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `owner_id` bigint(20) unsigned DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `tin_number` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL DEFAULT 'Kampala',
  `country` varchar(255) NOT NULL DEFAULT 'Uganda',
  `currency_code` varchar(10) NOT NULL DEFAULT 'UGX',
  `currency_symbol` varchar(10) NOT NULL DEFAULT 'UGX',
  `logo_url` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `businesses_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `businesses`
INSERT INTO `businesses` (`id`, `name`, `slug`, `owner_id`, `email`, `phone`, `tin_number`, `address`, `city`, `country`, `currency_code`, `currency_symbol`, `logo_url`, `status`, `created_at`, `updated_at`) VALUES ('4', 'hushrayr', 'hushrayr-547', '8', 'hushray05@gmail.com', '6744556455', NULL, NULL, 'Kampala', 'Uganda', 'UGX', 'UGX', NULL, 'active', '2026-09-09 15:49:43', '2026-09-09 15:49:43');

-- --------------------------------------------------------
-- Structure for table `cache`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `cache_locks`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `companies`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `companies_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `companies`
INSERT INTO `companies` (`id`, `name`, `slug`, `description`, `industry`, `website`, `logo_url`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('1', 'Victoria Logistics & Trading Ltd', 'victoria-logistics-trading-ltd', 'Cross-border trade, grain supply and distribution across East Africa with a fleet of distribution trucks.', 'Logistics & Trade', 'https://victoriatrading.co.ug', NULL, '1', '1', '2026-09-03 16:05:54', '2026-09-03 16:05:54');
INSERT INTO `companies` (`id`, `name`, `slug`, `description`, `industry`, `website`, `logo_url`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('2', 'Pearl Agri-Supplies Uganda', 'pearl-agri-supplies-uganda', 'Agro-input distribution serving farmers across the Central and Western regions.', 'Agriculture', 'https://pearlagri.co.ug', NULL, '1', '2', '2026-09-03 16:05:54', '2026-09-03 16:05:54');
INSERT INTO `companies` (`id`, `name`, `slug`, `description`, `industry`, `website`, `logo_url`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('3', 'Rwenzori Tech Innovators', 'rwenzori-tech-innovators', 'Mbarara-based technology consultancy building point-of-sale and inventory solutions.', 'Technology', 'https://rwenzoritech.ug', NULL, '1', '3', '2026-09-03 16:05:54', '2026-09-03 16:05:54');
INSERT INTO `companies` (`id`, `name`, `slug`, `description`, `industry`, `website`, `logo_url`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('4', 'Kampala Coffee Roasters', 'kampala-coffee-roasters', 'Specialty coffee roaster exporting Mt. Elgon single-origin Arabica to European buyers.', 'Agri-Export', 'https://kampalacoffee.ug', NULL, '1', '4', '2026-09-03 16:05:54', '2026-09-03 16:05:54');
INSERT INTO `companies` (`id`, `name`, `slug`, `description`, `industry`, `website`, `logo_url`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('5', 'Entebbe Fresh Markets', 'entebbe-fresh-markets', 'Fresh produce wholesale operation supplying supermarkets and hospitality chains.', 'Retail & Distribution', 'https://entebbefresh.ug', NULL, '1', '5', '2026-09-03 16:05:54', '2026-09-03 16:05:54');
INSERT INTO `companies` (`id`, `name`, `slug`, `description`, `industry`, `website`, `logo_url`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('6', 'Nile Construction & Hardware', 'nile-construction-hardware', 'Civil construction firm and building materials supplier operating in Kampala and Jinja.', 'Construction', 'https://nileconstruction.ug', NULL, '1', '6', '2026-09-03 16:05:54', '2026-09-03 16:05:54');

-- --------------------------------------------------------
-- Structure for table `contact_messages`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE `contact_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `credit_notes`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `credit_notes`;
CREATE TABLE `credit_notes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `credit_note_number` varchar(255) NOT NULL,
  `party_id` bigint(20) unsigned DEFAULT NULL,
  `invoice_id` bigint(20) unsigned DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `credit_date` date NOT NULL,
  `amount_ugx` bigint(20) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'issued',
  `restock_inventory` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `credit_notes_credit_note_number_unique` (`credit_note_number`),
  KEY `credit_notes_business_id_foreign` (`business_id`),
  KEY `credit_notes_party_id_foreign` (`party_id`),
  KEY `credit_notes_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `credit_notes_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `credit_notes_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `credit_notes_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `currencies`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(3) NOT NULL,
  `name` varchar(255) NOT NULL,
  `symbol` varchar(10) NOT NULL,
  `exchange_rate` decimal(18,6) NOT NULL DEFAULT 1.000000,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `country` varchar(255) NOT NULL,
  `flag` varchar(10) NOT NULL DEFAULT '?',
  `format` varchar(255) NOT NULL DEFAULT 'standard',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `currencies_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `currencies`
INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `exchange_rate`, `is_active`, `country`, `flag`, `format`, `created_at`, `updated_at`) VALUES ('1', 'UGX', 'Ugandan Shilling', 'UGX', '3710.000000', '1', 'Uganda', '🇺🇬', 'standard', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `exchange_rate`, `is_active`, `country`, `flag`, `format`, `created_at`, `updated_at`) VALUES ('2', 'KES', 'Kenyan Shilling', 'KSh', '129.500000', '1', 'Kenya', '🇰🇪', 'standard', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `exchange_rate`, `is_active`, `country`, `flag`, `format`, `created_at`, `updated_at`) VALUES ('3', 'TZS', 'Tanzanian Shilling', 'TSh', '2680.000000', '1', 'Tanzania', '🇹🇿', 'standard', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `exchange_rate`, `is_active`, `country`, `flag`, `format`, `created_at`, `updated_at`) VALUES ('4', 'RWF', 'Rwandan Franc', 'FRw', '1360.000000', '1', 'Rwanda', '🇷🇼', 'standard', '2026-09-03 16:05:54', '2026-09-03 16:05:54');
INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `exchange_rate`, `is_active`, `country`, `flag`, `format`, `created_at`, `updated_at`) VALUES ('5', 'USD', 'US Dollar', '$', '1.000000', '1', 'United States', '🇺🇸', 'standard', '2026-09-03 16:05:54', '2026-09-03 16:05:54');
INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `exchange_rate`, `is_active`, `country`, `flag`, `format`, `created_at`, `updated_at`) VALUES ('6', 'EUR', 'Euro', '€', '0.925000', '1', 'European Union', '🇪🇺', 'standard', '2026-09-03 16:05:54', '2026-09-03 16:05:54');
INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `exchange_rate`, `is_active`, `country`, `flag`, `format`, `created_at`, `updated_at`) VALUES ('7', 'GBP', 'British Pound', '£', '0.792000', '1', 'United Kingdom', '🇬🇧', 'standard', '2026-09-03 16:05:54', '2026-09-03 16:05:54');
INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `exchange_rate`, `is_active`, `country`, `flag`, `format`, `created_at`, `updated_at`) VALUES ('8', 'ZAR', 'South African Rand', 'R', '18.350000', '1', 'South Africa', '🇿🇦', 'standard', '2026-09-03 16:05:54', '2026-09-03 16:05:54');
INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `exchange_rate`, `is_active`, `country`, `flag`, `format`, `created_at`, `updated_at`) VALUES ('9', 'EGP', 'Egyptian Pound', 'E£', '48.700000', '1', 'Egypt', '🇪🇬', 'standard', '2026-09-03 16:05:54', '2026-09-03 16:05:54');
INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `exchange_rate`, `is_active`, `country`, `flag`, `format`, `created_at`, `updated_at`) VALUES ('10', 'NGN', 'Nigerian Naira', '₦', '1520.500000', '1', 'Nigeria', '🇳🇬', 'standard', '2026-09-03 16:05:54', '2026-09-03 16:05:54');
INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `exchange_rate`, `is_active`, `country`, `flag`, `format`, `created_at`, `updated_at`) VALUES ('11', 'GHS', 'Ghanaian Cedi', 'GH₵', '15.650000', '1', 'Ghana', '🇬🇭', 'standard', '2026-09-03 16:05:54', '2026-09-03 16:05:54');
INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `exchange_rate`, `is_active`, `country`, `flag`, `format`, `created_at`, `updated_at`) VALUES ('12', 'MAD', 'Moroccan Dirham', 'MAD', '9.950000', '1', 'Morocco', '🇲🇦', 'standard', '2026-09-03 16:05:54', '2026-09-03 16:05:54');

-- --------------------------------------------------------
-- Structure for table `expenses`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `amount_ugx` bigint(20) NOT NULL,
  `expense_date` date NOT NULL,
  `vendor_name` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'Cash',
  `reference_no` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `receipt_url` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_business_id_foreign` (`business_id`),
  CONSTRAINT `expenses_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `failed_jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `feature_usage`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `feature_usage`;
CREATE TABLE `feature_usage` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned NOT NULL,
  `feature_key` varchar(255) NOT NULL,
  `usage_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feature_usage_business_id_foreign` (`business_id`),
  CONSTRAINT `feature_usage_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `features`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `features`;
CREATE TABLE `features` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'General',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `features_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `features`
INSERT INTO `features` (`id`, `name`, `key`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'Vendor Management', 'vendor_management', 'Manage vendors, suppliers, and vendor ledger balances.', 'Purchases', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `features` (`id`, `name`, `key`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'Early Payment Discount', 'early_payment_discount', 'Configure early settlement discounts on customer invoices.', 'Billing', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `features` (`id`, `name`, `key`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES ('3', 'Journal Entries', 'journal_entries', 'Create double-entry manual bookkeeping journal vouchers.', 'Accounting', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `features` (`id`, `name`, `key`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES ('4', 'Sales Orders', 'sales_orders', 'Track commercial sales orders from draft to fulfilment.', 'Sales', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `features` (`id`, `name`, `key`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES ('5', 'Purchase Orders', 'purchase_orders', 'Manage purchase requests and supplier purchase orders.', 'Purchases', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `features` (`id`, `name`, `key`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES ('6', 'Inventory Tracking', 'inventory_tracking', 'Track stock quantities, stock in/out, and warehouse movements.', 'Inventory', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `features` (`id`, `name`, `key`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES ('7', 'Online Payments', 'online_payments', 'Accept MTN Mobile Money, Airtel Money and Card payments.', 'Payments', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `features` (`id`, `name`, `key`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES ('8', 'Custom User Roles', 'custom_user_roles', 'Create custom roles and fine-grained permissions for staff.', 'Security', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `features` (`id`, `name`, `key`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES ('9', 'Profit & Loss Statement', 'profit_loss', 'Real-time Profit & Loss statement with revenue vs expense breakdown.', 'Reports', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `features` (`id`, `name`, `key`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES ('10', 'Balance Sheet', 'balance_sheet', 'Complete statement of financial position (Assets vs Liabilities).', 'Reports', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `features` (`id`, `name`, `key`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES ('11', 'Financial Reports', 'financial_reports', 'Trial balance, cash flow summary, and general ledger reports.', 'Reports', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `features` (`id`, `name`, `key`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES ('12', 'Payroll Management', 'payroll', 'Staff records, monthly payroll processing, and statutory deductions.', 'HR', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `features` (`id`, `name`, `key`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES ('13', 'Tax Compliance (URA)', 'taxes', 'Uganda Revenue Authority compliance: PAYE, NSSF, VAT 18%, LED.', 'Compliance', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');

-- --------------------------------------------------------
-- Structure for table `feedback`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'General Feedback',
  `message` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feedback_user_id_foreign` (`user_id`),
  KEY `feedback_business_id_foreign` (`business_id`),
  CONSTRAINT `feedback_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `feedback_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `invoice_items`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `invoice_items`;
CREATE TABLE `invoice_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price_cents` bigint(20) NOT NULL,
  `total_price_cents` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `invoices`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `billing_address` text DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `currency_code` varchar(3) NOT NULL DEFAULT 'USD',
  `exchange_rate_applied` decimal(18,6) NOT NULL DEFAULT 1.000000,
  `subtotal_cents` bigint(20) NOT NULL,
  `tax_cents` bigint(20) NOT NULL DEFAULT 0,
  `total_cents` bigint(20) NOT NULL,
  `status` enum('draft','pending','paid','overdue','cancelled') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `job_batches`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `leads`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `leads`;
CREATE TABLE `leads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `status` enum('new','contacted','qualified','proposal','won','lost') NOT NULL DEFAULT 'new',
  `estimated_value_cents` bigint(20) NOT NULL DEFAULT 0,
  `assigned_to` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `migrations`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `migrations`
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2026_08_29_000001_create_tbm_tables', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2026_08_31_000001_add_images_and_avatars_to_tables', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2026_08_31_000002_create_saas_and_business_suite_tables', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2026_09_02_000001_enhance_system_tables_for_agent_spec', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2026_09_03_000001_create_companies_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('9', '2026_09_03_112247_add_image_url_to_orders_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('10', '2026_09_03_130616_create_pages_table', '1');

-- --------------------------------------------------------
-- Structure for table `orders`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `order_number` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'sales_order',
  `party_name` varchar(255) NOT NULL,
  `total_amount_ugx` bigint(20) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'confirmed',
  `order_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_business_id_foreign` (`business_id`),
  CONSTRAINT `orders_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `orders`
INSERT INTO `orders` (`id`, `business_id`, `order_number`, `type`, `party_name`, `total_amount_ugx`, `status`, `order_date`, `notes`, `image_url`, `created_at`, `updated_at`) VALUES ('4', '4', 'SO-2026-7084', 'sales_order', 'themp', '128999', 'confirmed', '2026-09-09', '5vbhkbvg', 'images/orders/order-so-2026-7084-1788973174.png', '2026-09-09 16:59:34', '2026-09-09 16:59:34');

-- --------------------------------------------------------
-- Structure for table `pages`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `meta_description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `parties`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `parties`;
CREATE TABLE `parties` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `tin_number` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL DEFAULT 'Kampala',
  `type` varchar(255) NOT NULL DEFAULT 'customer',
  `balance_ugx` bigint(20) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parties_business_id_foreign` (`business_id`),
  CONSTRAINT `parties_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `password_reset_tokens`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `payments_made`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `payments_made`;
CREATE TABLE `payments_made` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `payment_number` varchar(255) NOT NULL,
  `bill_id` bigint(20) unsigned DEFAULT NULL,
  `party_id` bigint(20) unsigned DEFAULT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `amount_ugx` bigint(20) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'Bank Transfer',
  `reference_no` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_made_payment_number_unique` (`payment_number`),
  KEY `payments_made_business_id_foreign` (`business_id`),
  KEY `payments_made_bill_id_foreign` (`bill_id`),
  KEY `payments_made_party_id_foreign` (`party_id`),
  CONSTRAINT `payments_made_bill_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_made_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_made_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `payments_received`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `payments_received`;
CREATE TABLE `payments_received` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `payment_number` varchar(255) NOT NULL,
  `invoice_id` bigint(20) unsigned DEFAULT NULL,
  `party_id` bigint(20) unsigned DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `amount_ugx` bigint(20) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'Cash',
  `reference_no` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_received_payment_number_unique` (`payment_number`),
  KEY `payments_received_business_id_foreign` (`business_id`),
  KEY `payments_received_invoice_id_foreign` (`invoice_id`),
  KEY `payments_received_party_id_foreign` (`party_id`),
  CONSTRAINT `payments_received_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_received_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_received_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `payrolls`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `payrolls`;
CREATE TABLE `payrolls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `staff_id` bigint(20) unsigned NOT NULL,
  `payroll_period` varchar(255) NOT NULL,
  `basic_salary_ugx` bigint(20) NOT NULL,
  `allowances_ugx` bigint(20) NOT NULL DEFAULT 0,
  `gross_pay_ugx` bigint(20) NOT NULL,
  `nssf_employee_ugx` bigint(20) NOT NULL,
  `nssf_employer_ugx` bigint(20) NOT NULL,
  `paye_tax_ugx` bigint(20) NOT NULL,
  `other_deductions_ugx` bigint(20) NOT NULL DEFAULT 0,
  `net_salary_ugx` bigint(20) NOT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'paid',
  `payment_date` date DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'Bank Transfer',
  `reference_no` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payrolls_business_id_foreign` (`business_id`),
  KEY `payrolls_staff_id_foreign` (`staff_id`),
  CONSTRAINT `payrolls_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payrolls_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `products`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price_cents` bigint(20) NOT NULL,
  `cost_price_cents` bigint(20) NOT NULL DEFAULT 0,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `category` varchar(255) NOT NULL DEFAULT 'Enterprise Core',
  `vat_rate` decimal(5,2) NOT NULL DEFAULT 18.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `quote_items`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `quote_items`;
CREATE TABLE `quote_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quote_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price_ugx` bigint(20) NOT NULL,
  `total_price_ugx` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quote_items_quote_id_foreign` (`quote_id`),
  KEY `quote_items_product_id_foreign` (`product_id`),
  CONSTRAINT `quote_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `quote_items_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `quotes`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `quotes`;
CREATE TABLE `quotes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `quote_number` varchar(255) NOT NULL,
  `party_id` bigint(20) unsigned DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `quote_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `subtotal_ugx` bigint(20) NOT NULL DEFAULT 0,
  `vat_rate_percent` decimal(5,2) NOT NULL DEFAULT 18.00,
  `vat_amount_ugx` bigint(20) NOT NULL DEFAULT 0,
  `discount_amount_ugx` bigint(20) NOT NULL DEFAULT 0,
  `total_amount_ugx` bigint(20) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'sent',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `quotes_quote_number_unique` (`quote_number`),
  KEY `quotes_business_id_foreign` (`business_id`),
  KEY `quotes_party_id_foreign` (`party_id`),
  CONSTRAINT `quotes_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `quotes_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `sales_receipts`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sales_receipts`;
CREATE TABLE `sales_receipts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `receipt_number` varchar(255) NOT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL DEFAULT 'Walk-in Customer',
  `invoice_id` bigint(20) unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `payment_mode` varchar(255) NOT NULL DEFAULT 'Cash',
  `subtotal_ugx` bigint(20) NOT NULL DEFAULT 0,
  `vat_amount_ugx` bigint(20) NOT NULL DEFAULT 0,
  `total` bigint(20) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_receipts_receipt_number_unique` (`receipt_number`),
  KEY `sales_receipts_business_id_foreign` (`business_id`),
  KEY `sales_receipts_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `sales_receipts_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sales_receipts_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `sessions`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `staff`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `employee_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `department` varchar(255) NOT NULL DEFAULT 'Operations',
  `designation` varchar(255) NOT NULL DEFAULT 'Officer',
  `basic_salary_ugx` bigint(20) NOT NULL DEFAULT 1500000,
  `allowances_ugx` bigint(20) NOT NULL DEFAULT 200000,
  `tin_number` varchar(255) DEFAULT NULL,
  `nssf_number` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'Bank',
  `bank_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `mobile_money_number` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_employee_id_unique` (`employee_id`),
  KEY `staff_business_id_foreign` (`business_id`),
  CONSTRAINT `staff_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `stock_movements`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `stock_movements`;
CREATE TABLE `stock_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `quantity_change` int(11) NOT NULL,
  `balance_after` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_product_id_foreign` (`product_id`),
  KEY `stock_movements_business_id_foreign` (`business_id`),
  CONSTRAINT `stock_movements_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `subscription_events`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `subscription_events`;
CREATE TABLE `subscription_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint(20) unsigned DEFAULT NULL,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `event_type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `metadata` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscription_events_subscription_id_foreign` (`subscription_id`),
  KEY `subscription_events_business_id_foreign` (`business_id`),
  CONSTRAINT `subscription_events_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscription_events_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `subscription_events`
INSERT INTO `subscription_events` (`id`, `subscription_id`, `business_id`, `event_type`, `description`, `metadata`, `created_at`, `updated_at`) VALUES ('1', '4', '4', 'trial_started', 'Provisioned 30-Day Free Trial on Professional Plan.', NULL, '2026-09-09 15:49:44', NULL);
INSERT INTO `subscription_events` (`id`, `subscription_id`, `business_id`, `event_type`, `description`, `metadata`, `created_at`, `updated_at`) VALUES ('2', '4', '4', 'renewed', 'Payment of UGX 40,000 confirmed via MTN Mobile Money. Subscription active until 09 Oct 2026.', NULL, '2026-09-09 16:11:16', NULL);

-- --------------------------------------------------------
-- Structure for table `subscription_invoices`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `subscription_invoices`;
CREATE TABLE `subscription_invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) NOT NULL,
  `business_id` bigint(20) unsigned NOT NULL,
  `subscription_id` bigint(20) unsigned DEFAULT NULL,
  `plan_id` bigint(20) unsigned DEFAULT NULL,
  `amount` bigint(20) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'UGX',
  `billing_period_start` date DEFAULT NULL,
  `billing_period_end` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'paid',
  `payment_method` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_invoices_invoice_number_unique` (`invoice_number`),
  KEY `subscription_invoices_business_id_foreign` (`business_id`),
  KEY `subscription_invoices_subscription_id_foreign` (`subscription_id`),
  KEY `subscription_invoices_plan_id_foreign` (`plan_id`),
  CONSTRAINT `subscription_invoices_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscription_invoices_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `subscription_invoices_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `subscription_invoices`
INSERT INTO `subscription_invoices` (`id`, `invoice_number`, `business_id`, `subscription_id`, `plan_id`, `amount`, `currency`, `billing_period_start`, `billing_period_end`, `status`, `payment_method`, `paid_at`, `created_at`, `updated_at`) VALUES ('2', 'SUB-INV-2026-7746', '4', '4', '3', '40000', 'UGX', '2026-09-09', '2026-10-09', 'paid', 'MTN Mobile Money', '2026-09-09 16:11:16', '2026-09-09 16:11:16', '2026-09-09 16:11:16');

-- --------------------------------------------------------
-- Structure for table `subscription_payments`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `subscription_payments`;
CREATE TABLE `subscription_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_reference` varchar(255) NOT NULL,
  `business_id` bigint(20) unsigned NOT NULL,
  `subscription_id` bigint(20) unsigned DEFAULT NULL,
  `plan_id` bigint(20) unsigned DEFAULT NULL,
  `amount` bigint(20) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'UGX',
  `gateway` varchar(255) NOT NULL DEFAULT 'MTN Mobile Money',
  `status` varchar(255) NOT NULL DEFAULT 'successful',
  `paid_at` timestamp NULL DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_payments_transaction_reference_unique` (`transaction_reference`),
  KEY `subscription_payments_business_id_foreign` (`business_id`),
  KEY `subscription_payments_subscription_id_foreign` (`subscription_id`),
  KEY `subscription_payments_plan_id_foreign` (`plan_id`),
  CONSTRAINT `subscription_payments_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscription_payments_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `subscription_payments_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `subscription_payments`
INSERT INTO `subscription_payments` (`id`, `transaction_reference`, `business_id`, `subscription_id`, `plan_id`, `amount`, `currency`, `gateway`, `status`, `paid_at`, `metadata`, `created_at`, `updated_at`) VALUES ('2', 'TXN-UGX-CNABYSLPSX', '4', '4', '3', '40000', 'UGX', 'MTN Mobile Money', 'successful', '2026-09-09 16:11:16', '{\"phone\":\"772123456\",\"gateway\":\"MTN Mobile Money\",\"cycle\":\"monthly\"}', '2026-09-09 16:11:16', '2026-09-09 16:11:16');

-- --------------------------------------------------------
-- Structure for table `subscription_plan_features`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `subscription_plan_features`;
CREATE TABLE `subscription_plan_features` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subscription_plan_id` bigint(20) unsigned NOT NULL,
  `feature_key` varchar(255) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscription_plan_features_subscription_plan_id_foreign` (`subscription_plan_id`),
  CONSTRAINT `subscription_plan_features_subscription_plan_id_foreign` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `subscription_plan_features`
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('1', '1', 'vendor_management', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('2', '1', 'early_payment_discount', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('3', '1', 'journal_entries', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('4', '1', 'sales_orders', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('5', '1', 'purchase_orders', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('6', '1', 'inventory_tracking', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('7', '1', 'online_payments', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('8', '1', 'custom_user_roles', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('9', '1', 'profit_loss', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('10', '1', 'balance_sheet', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('11', '1', 'financial_reports', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('12', '1', 'payroll', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('13', '1', 'taxes', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('14', '2', 'vendor_management', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('15', '2', 'early_payment_discount', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('16', '2', 'journal_entries', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('17', '2', 'sales_orders', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('18', '2', 'purchase_orders', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('19', '2', 'inventory_tracking', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('20', '2', 'online_payments', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('21', '2', 'custom_user_roles', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('22', '2', 'profit_loss', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('23', '2', 'balance_sheet', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('24', '2', 'financial_reports', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('25', '2', 'payroll', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('26', '2', 'taxes', '0', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('27', '3', 'vendor_management', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('28', '3', 'early_payment_discount', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('29', '3', 'journal_entries', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('30', '3', 'sales_orders', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('31', '3', 'purchase_orders', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('32', '3', 'inventory_tracking', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('33', '3', 'online_payments', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('34', '3', 'custom_user_roles', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('35', '3', 'profit_loss', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('36', '3', 'balance_sheet', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('37', '3', 'financial_reports', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('38', '3', 'payroll', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plan_features` (`id`, `subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES ('39', '3', 'taxes', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');

-- --------------------------------------------------------
-- Structure for table `subscription_plans`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `subscription_plans`;
CREATE TABLE `subscription_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `badge` varchar(255) DEFAULT NULL,
  `monthly_price` bigint(20) NOT NULL DEFAULT 15000,
  `annual_price` bigint(20) NOT NULL DEFAULT 150000,
  `currency` varchar(10) NOT NULL DEFAULT 'UGX',
  `currency_symbol` varchar(10) NOT NULL DEFAULT 'UGX',
  `trial_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `trial_duration` int(11) NOT NULL DEFAULT 30,
  `trial_unit` varchar(255) NOT NULL DEFAULT 'Days',
  `max_users` int(11) DEFAULT 1,
  `max_locations` int(11) DEFAULT 1,
  `max_products` int(11) DEFAULT 100,
  `max_invoices` int(11) DEFAULT 100,
  `max_receipts` int(11) DEFAULT 100,
  `max_purchases` int(11) DEFAULT 100,
  `max_vendors` int(11) DEFAULT 50,
  `max_customers` int(11) DEFAULT 100,
  `max_storage_mb` int(11) DEFAULT 500,
  `max_documents` int(11) DEFAULT 200,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_plans_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `subscription_plans`
INSERT INTO `subscription_plans` (`id`, `name`, `slug`, `description`, `badge`, `monthly_price`, `annual_price`, `currency`, `currency_symbol`, `trial_enabled`, `trial_duration`, `trial_unit`, `max_users`, `max_locations`, `max_products`, `max_invoices`, `max_receipts`, `max_purchases`, `max_vendors`, `max_customers`, `max_storage_mb`, `max_documents`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('1', 'Starter', 'starter', 'Efficiently organize your transactions, accounts, reports and books.', 'STARTER', '15000', '150000', 'UGX', 'UGX', '1', '30', 'Days', '1', '1', '100', '100', '100', '100', '50', '100', '500', '200', '1', '1', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plans` (`id`, `name`, `slug`, `description`, `badge`, `monthly_price`, `annual_price`, `currency`, `currency_symbol`, `trial_enabled`, `trial_duration`, `trial_unit`, `max_users`, `max_locations`, `max_products`, `max_invoices`, `max_receipts`, `max_purchases`, `max_vendors`, `max_customers`, `max_storage_mb`, `max_documents`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('2', 'Professional', 'professional', 'Professional tools for growing businesses.', 'MOST POPULAR', '25000', '250000', 'UGX', 'UGX', '1', '30', 'Days', '2', '2', '500', '500', '500', '500', '200', '500', '2000', '1000', '1', '2', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_plans` (`id`, `name`, `slug`, `description`, `badge`, `monthly_price`, `annual_price`, `currency`, `currency_symbol`, `trial_enabled`, `trial_duration`, `trial_unit`, `max_users`, `max_locations`, `max_products`, `max_invoices`, `max_receipts`, `max_purchases`, `max_vendors`, `max_customers`, `max_storage_mb`, `max_documents`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ('3', 'Premium', 'premium', 'Enhanced customization and automation that streamlines business processes.', 'PREMIUM', '40000', '400000', 'UGX', 'UGX', '1', '30', 'Days', '5', '10', NULL, NULL, NULL, NULL, NULL, NULL, '10000', NULL, '1', '3', '2026-09-03 16:05:53', '2026-09-03 16:05:53');

-- --------------------------------------------------------
-- Structure for table `subscription_settings`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `subscription_settings`;
CREATE TABLE `subscription_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `subscription_settings`
INSERT INTO `subscription_settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES ('1', 'default_trial_days', '30', 'integer', 'Default trial duration in days for newly registered businesses', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES ('2', 'currency', 'UGX', 'string', 'Primary system billing currency display name', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES ('3', 'currency_code', 'UGX', 'string', 'ISO 4217 Currency Code for billing and checkout', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES ('4', 'currency_symbol', 'UGX', 'string', 'Currency symbol representation', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES ('5', 'monthly_billing_enabled', '1', 'boolean', 'Enable monthly recurring subscription options', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES ('6', 'annual_billing_enabled', '1', 'boolean', 'Enable annual discount billing options', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES ('7', 'payment_gateway', 'MTN Mobile Money & Airtel Money', 'string', 'Default active payment gateway provider', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES ('8', 'trial_payment_required', '0', 'boolean', 'Require payment method entry before beginning trial', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES ('9', 'grace_period_days', '3', 'integer', 'Number of grace days after expiration before suspension', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES ('10', 'renewal_reminder_days', '7,3,1', 'string', 'Days prior to expiration to trigger automated warnings', '2026-09-03 16:05:53', '2026-09-03 16:05:53');
INSERT INTO `subscription_settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES ('11', 'expiration_behavior', 'read_only', 'string', 'System behavior upon unpaid expiration (read_only / lock_out)', '2026-09-03 16:05:53', '2026-09-03 16:05:53');

-- --------------------------------------------------------
-- Structure for table `subscriptions`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE `subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `subscription_plan_id` bigint(20) unsigned NOT NULL,
  `billing_cycle` varchar(255) NOT NULL DEFAULT 'monthly',
  `amount` bigint(20) NOT NULL DEFAULT 15000,
  `currency` varchar(10) NOT NULL DEFAULT 'UGX',
  `status` varchar(255) NOT NULL DEFAULT 'trialing',
  `trial_starts_at` timestamp NULL DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `next_billing_date` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_business_id_foreign` (`business_id`),
  KEY `subscriptions_subscription_plan_id_foreign` (`subscription_plan_id`),
  CONSTRAINT `subscriptions_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscriptions_subscription_plan_id_foreign` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `subscriptions`
INSERT INTO `subscriptions` (`id`, `business_id`, `user_id`, `subscription_plan_id`, `billing_cycle`, `amount`, `currency`, `status`, `trial_starts_at`, `trial_ends_at`, `starts_at`, `ends_at`, `next_billing_date`, `cancelled_at`, `payment_status`, `created_at`, `updated_at`) VALUES ('4', '4', '8', '3', 'monthly', '40000', 'UGX', 'active', '2026-09-09 15:49:44', '2026-10-09 15:49:44', '2026-09-09 16:11:16', '2026-10-09 16:11:16', '2026-10-09 16:11:16', NULL, 'paid', '2026-09-09 15:49:44', '2026-09-09 16:11:16');

-- --------------------------------------------------------
-- Structure for table `tasks`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `project_name` varchar(255) NOT NULL DEFAULT 'General Operations',
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `status` enum('backlog','in_progress','review','completed') NOT NULL DEFAULT 'in_progress',
  `price_cents` bigint(20) NOT NULL DEFAULT 0,
  `due_date` date DEFAULT NULL,
  `assigned_user` varchar(255) DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `tax_records`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tax_records`;
CREATE TABLE `tax_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `tax_type` varchar(255) NOT NULL,
  `period` varchar(255) NOT NULL,
  `taxable_amount_ugx` bigint(20) NOT NULL,
  `tax_rate_percent` decimal(5,2) NOT NULL DEFAULT 18.00,
  `tax_amount_ugx` bigint(20) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `due_date` date DEFAULT NULL,
  `filing_date` date DEFAULT NULL,
  `ura_prn` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tax_records_business_id_foreign` (`business_id`),
  CONSTRAINT `tax_records_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `tickets`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('open','in_progress','resolved','closed') NOT NULL DEFAULT 'open',
  `requester_name` varchar(255) NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `requester_email` varchar(255) DEFAULT NULL,
  `assigned_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tickets_ticket_number_unique` (`ticket_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Structure for table `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'Executive Admin',
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_super_admin` tinyint(1) NOT NULL DEFAULT 0,
  `business_id` bigint(20) unsigned DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `name`, `email`, `avatar_url`, `job_title`, `department`, `phone`, `bio`, `role`, `status`, `email_verified_at`, `password`, `is_super_admin`, `business_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'System Administrator', 'admin@tbm-enterprise.com', '/images/logo.png', 'Chief Technology Officer', 'Executive Office', '+256 700 000000', 'Lead System Administrator for TBM Enterprise Suite.', 'Super Administrator', 'active', '2026-09-03 16:05:49', '$2y$12$QtbBpbCqvnxx7j9C/gRzB.RLCdc2suwOn9mka3w8alA3nla5Z/3XW', '1', NULL, NULL, '2026-09-03 16:05:49', '2026-09-09 17:34:12');
INSERT INTO `users` (`id`, `name`, `email`, `avatar_url`, `job_title`, `department`, `phone`, `bio`, `role`, `status`, `email_verified_at`, `password`, `is_super_admin`, `business_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('8', 'hush ray', 'hushray05@gmail.com', '/storage/avatars/yaUEmy69mPc7FtSRAN3tQ6WanMxHUS51Ljw9J3fN.png', 'Managing Director', 'Executive Management', '6744556455', NULL, 'Business Owner', 'active', NULL, '$2y$12$PMrCIwxovJoP2LZTQrNY5OVeOPwiKV.lItNyRg0HHj2QMOpU0eHOa', '0', '4', NULL, '2026-09-09 15:49:43', '2026-09-09 16:29:47');

SET FOREIGN_KEY_CHECKS=1;
