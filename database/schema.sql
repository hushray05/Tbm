-- =====================================================================
-- TBM ENTERPRISE SUITE & SUBSCRIPTION MANAGEMENT SYSTEM (UGANDA UGX)
-- DATABASE SCHEMA DEFINITION (MySQL / PostgreSQL / SQLite Compatible)
-- =====================================================================

-- 1. Currencies Table
CREATE TABLE IF NOT EXISTS `currencies` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(3) NOT NULL UNIQUE,
  `name` VARCHAR(255) NOT NULL,
  `symbol` VARCHAR(10) NOT NULL,
  `exchange_rate` DECIMAL(18,6) NOT NULL DEFAULT 1.000000,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `country` VARCHAR(255) NOT NULL,
  `flag` VARCHAR(10) NOT NULL DEFAULT '🌐',
  `format` VARCHAR(50) NOT NULL DEFAULT 'standard',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Businesses / Organizations Table
CREATE TABLE IF NOT EXISTS `businesses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) UNIQUE,
  `owner_id` BIGINT UNSIGNED NULL,
  `email` VARCHAR(255) NULL,
  `phone` VARCHAR(50) NULL,
  `tin_number` VARCHAR(50) NULL,
  `address` TEXT NULL,
  `city` VARCHAR(100) NOT NULL DEFAULT 'Kampala',
  `country` VARCHAR(100) NOT NULL DEFAULT 'Uganda',
  `currency_code` VARCHAR(10) NOT NULL DEFAULT 'UGX',
  `currency_symbol` VARCHAR(10) NOT NULL DEFAULT 'UGX',
  `logo_url` VARCHAR(255) NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Business Locations Table
CREATE TABLE IF NOT EXISTS `business_locations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `address` TEXT NULL,
  `city` VARCHAR(100) NOT NULL DEFAULT 'Kampala',
  `phone` VARCHAR(50) NULL,
  `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`business_id`) REFERENCES `businesses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Subscription Plans Table
CREATE TABLE IF NOT EXISTS `subscription_plans` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `badge` VARCHAR(100) NULL,
  `monthly_price` BIGINT NOT NULL DEFAULT 15000,
  `annual_price` BIGINT NOT NULL DEFAULT 150000,
  `currency` VARCHAR(10) NOT NULL DEFAULT 'UGX',
  `currency_symbol` VARCHAR(10) NOT NULL DEFAULT 'UGX',
  `trial_enabled` TINYINT(1) NOT NULL DEFAULT 1,
  `trial_duration` INT NOT NULL DEFAULT 30,
  `trial_unit` VARCHAR(50) NOT NULL DEFAULT 'Days',
  `max_users` INT NULL DEFAULT 1,
  `max_locations` INT NULL DEFAULT 1,
  `max_products` INT NULL DEFAULT 100,
  `max_invoices` INT NULL DEFAULT 100,
  `max_receipts` INT NULL DEFAULT 100,
  `max_purchases` INT NULL DEFAULT 100,
  `max_vendors` INT NULL DEFAULT 50,
  `max_customers` INT NULL DEFAULT 100,
  `max_storage_mb` INT NULL DEFAULT 500,
  `max_documents` INT NULL DEFAULT 200,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Features Master Table
CREATE TABLE IF NOT EXISTS `features` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `key` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `category` VARCHAR(100) NOT NULL DEFAULT 'General',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Subscription Plan Features Mapping Table
CREATE TABLE IF NOT EXISTS `subscription_plan_features` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `subscription_plan_id` BIGINT UNSIGNED NOT NULL,
  `feature_key` VARCHAR(100) NOT NULL,
  `is_enabled` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Subscriptions Table
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `subscription_plan_id` BIGINT UNSIGNED NOT NULL,
  `billing_cycle` VARCHAR(50) NOT NULL DEFAULT 'monthly',
  `amount` BIGINT NOT NULL DEFAULT 15000,
  `currency` VARCHAR(10) NOT NULL DEFAULT 'UGX',
  `status` VARCHAR(50) NOT NULL DEFAULT 'trialing',
  `trial_starts_at` TIMESTAMP NULL,
  `trial_ends_at` TIMESTAMP NULL,
  `starts_at` TIMESTAMP NULL,
  `ends_at` TIMESTAMP NULL,
  `next_billing_date` TIMESTAMP NULL,
  `cancelled_at` TIMESTAMP NULL,
  `payment_status` VARCHAR(50) NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`business_id`) REFERENCES `businesses`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Subscription Payments Table
CREATE TABLE IF NOT EXISTS `subscription_payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_reference` VARCHAR(100) NOT NULL UNIQUE,
  `business_id` BIGINT UNSIGNED NOT NULL,
  `subscription_id` BIGINT UNSIGNED NULL,
  `plan_id` BIGINT UNSIGNED NULL,
  `amount` BIGINT NOT NULL,
  `currency` VARCHAR(10) NOT NULL DEFAULT 'UGX',
  `gateway` VARCHAR(100) NOT NULL DEFAULT 'MTN Mobile Money',
  `status` VARCHAR(50) NOT NULL DEFAULT 'successful',
  `paid_at` TIMESTAMP NULL,
  `metadata` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`business_id`) REFERENCES `businesses`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Subscription Invoices Table
CREATE TABLE IF NOT EXISTS `subscription_invoices` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_number` VARCHAR(100) NOT NULL UNIQUE,
  `business_id` BIGINT UNSIGNED NOT NULL,
  `subscription_id` BIGINT UNSIGNED NULL,
  `plan_id` BIGINT UNSIGNED NULL,
  `amount` BIGINT NOT NULL,
  `currency` VARCHAR(10) NOT NULL DEFAULT 'UGX',
  `billing_period_start` DATE NULL,
  `billing_period_end` DATE NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'paid',
  `payment_method` VARCHAR(100) NULL,
  `paid_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`business_id`) REFERENCES `businesses`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Subscription Events Table
CREATE TABLE IF NOT EXISTS `subscription_events` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `subscription_id` BIGINT UNSIGNED NULL,
  `business_id` BIGINT UNSIGNED NULL,
  `event_type` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `metadata` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Feature Usage Table
CREATE TABLE IF NOT EXISTS `feature_usage` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` BIGINT UNSIGNED NOT NULL,
  `feature_key` VARCHAR(100) NOT NULL,
  `usage_count` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`business_id`) REFERENCES `businesses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Subscription Settings Table
CREATE TABLE IF NOT EXISTS `subscription_settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(100) NOT NULL UNIQUE,
  `value` TEXT NULL,
  `type` VARCHAR(50) NOT NULL DEFAULT 'string',
  `description` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Audit Logs Table
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NULL,
  `action` VARCHAR(100) NOT NULL,
  `entity_type` VARCHAR(100) NULL,
  `entity_id` BIGINT UNSIGNED NULL,
  `description` TEXT NOT NULL,
  `ip_address` VARCHAR(50) NULL,
  `metadata` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Expenses Table
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` BIGINT UNSIGNED NULL,
  `category` VARCHAR(100) NOT NULL,
  `amount_ugx` BIGINT NOT NULL,
  `expense_date` DATE NOT NULL,
  `vendor_name` VARCHAR(255) NULL,
  `payment_method` VARCHAR(100) NOT NULL DEFAULT 'Cash',
  `reference_no` VARCHAR(100) NULL,
  `description` TEXT NULL,
  `receipt_url` VARCHAR(255) NULL,
  `created_by` BIGINT UNSIGNED NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Staff / Employees Table
CREATE TABLE IF NOT EXISTS `staff` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` BIGINT UNSIGNED NULL,
  `employee_id` VARCHAR(50) NOT NULL UNIQUE,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NULL,
  `phone` VARCHAR(50) NULL,
  `department` VARCHAR(100) NOT NULL DEFAULT 'Operations',
  `designation` VARCHAR(100) NOT NULL DEFAULT 'Officer',
  `basic_salary_ugx` BIGINT NOT NULL DEFAULT 1500000,
  `allowances_ugx` BIGINT NOT NULL DEFAULT 200000,
  `tin_number` VARCHAR(50) NULL,
  `nssf_number` VARCHAR(50) NULL,
  `payment_method` VARCHAR(100) NOT NULL DEFAULT 'Bank',
  `bank_name` VARCHAR(255) NULL,
  `account_number` VARCHAR(100) NULL,
  `mobile_money_number` VARCHAR(50) NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. Payroll & Payslips Table
CREATE TABLE IF NOT EXISTS `payrolls` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` BIGINT UNSIGNED NULL,
  `staff_id` BIGINT UNSIGNED NOT NULL,
  `payroll_period` VARCHAR(50) NOT NULL,
  `basic_salary_ugx` BIGINT NOT NULL,
  `allowances_ugx` BIGINT NOT NULL DEFAULT 0,
  `gross_pay_ugx` BIGINT NOT NULL,
  `nssf_employee_ugx` BIGINT NOT NULL,
  `nssf_employer_ugx` BIGINT NOT NULL,
  `paye_tax_ugx` BIGINT NOT NULL,
  `other_deductions_ugx` BIGINT NOT NULL DEFAULT 0,
  `net_salary_ugx` BIGINT NOT NULL,
  `payment_status` VARCHAR(50) NOT NULL DEFAULT 'paid',
  `payment_date` DATE NULL,
  `payment_method` VARCHAR(100) NOT NULL DEFAULT 'Bank Transfer',
  `reference_no` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`staff_id`) REFERENCES `staff`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. Tax Compliance Records Table (Uganda URA)
CREATE TABLE IF NOT EXISTS `tax_records` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` BIGINT UNSIGNED NULL,
  `tax_type` VARCHAR(50) NOT NULL,
  `period` VARCHAR(50) NOT NULL,
  `taxable_amount_ugx` BIGINT NOT NULL,
  `tax_rate_percent` DECIMAL(5,2) NOT NULL DEFAULT 18.00,
  `tax_amount_ugx` BIGINT NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending',
  `due_date` DATE NULL,
  `filing_date` DATE NULL,
  `ura_prn` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. Customers & Vendors Directory
CREATE TABLE IF NOT EXISTS `parties` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` BIGINT UNSIGNED NULL,
  `name` VARCHAR(255) NOT NULL,
  `company` VARCHAR(255) NULL,
  `email` VARCHAR(255) NULL,
  `phone` VARCHAR(50) NULL,
  `tin_number` VARCHAR(50) NULL,
  `address` TEXT NULL,
  `city` VARCHAR(100) NOT NULL DEFAULT 'Kampala',
  `type` VARCHAR(50) NOT NULL DEFAULT 'customer',
  `balance_ugx` BIGINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. Sales & Purchase Orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` BIGINT UNSIGNED NULL,
  `order_number` VARCHAR(100) NOT NULL UNIQUE,
  `type` VARCHAR(50) NOT NULL DEFAULT 'sales_order',
  `party_name` VARCHAR(255) NOT NULL,
  `total_amount_ugx` BIGINT NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'confirmed',
  `order_date` DATE NOT NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
