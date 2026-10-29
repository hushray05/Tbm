-- =====================================================================
-- TBM ENTERPRISE SUITE & SUBSCRIPTION MANAGEMENT SYSTEM (UGANDA UGX)
-- DATABASE SEED DATA (Starter, Professional, Premium Plans & Configuration)
-- =====================================================================

-- 1. Seed Subscription Plans
INSERT INTO `subscription_plans` 
(`id`, `name`, `slug`, `description`, `badge`, `monthly_price`, `annual_price`, `currency`, `currency_symbol`, `trial_enabled`, `trial_duration`, `trial_unit`, `max_users`, `max_locations`, `max_products`, `max_invoices`, `max_receipts`, `max_purchases`, `max_vendors`, `max_customers`, `max_storage_mb`, `max_documents`, `is_active`, `sort_order`, `created_at`, `updated_at`) 
VALUES
(1, 'Starter', 'starter', 'Efficiently organize your transactions, accounts, reports and books.', 'STARTER', 15000, 150000, 'UGX', 'UGX', 1, 30, 'Days', 1, 1, 100, 100, 100, 100, 50, 100, 500, 200, 1, 1, NOW(), NOW()),
(2, 'Professional', 'professional', 'Professional tools for growing businesses.', 'MOST POPULAR', 25000, 250000, 'UGX', 'UGX', 1, 30, 'Days', 2, 2, 500, 500, 500, 500, 200, 500, 2000, 1000, 1, 2, NOW(), NOW()),
(3, 'Premium', 'premium', 'Enhanced customization and automation that streamlines business processes.', 'PREMIUM', 40000, 400000, 'UGX', 'UGX', 1, 30, 'Days', 5, 10, NULL, NULL, NULL, NULL, NULL, NULL, 10000, NULL, 1, 3, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `monthly_price`=VALUES(`monthly_price`), `annual_price`=VALUES(`annual_price`);

-- 2. Seed Features
INSERT INTO `features` (`id`, `name`, `key`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Vendor Management', 'vendor_management', 'Manage suppliers and vendor profiles.', 'Core', 1, NOW(), NOW()),
(2, 'Early Payment Discount', 'early_payment_discount', 'Apply discount rules for early invoice settlements.', 'Billing', 1, NOW(), NOW()),
(3, 'Journal Entries', 'journal_entries', 'Create manual double-entry accounting journals.', 'Accounting', 1, NOW(), NOW()),
(4, 'Sales Orders', 'sales_orders', 'Track sales orders and dispatch workflows.', 'Sales', 1, NOW(), NOW()),
(5, 'Purchase Orders', 'purchase_orders', 'Track supplier purchase orders and receipts.', 'Purchases', 1, NOW(), NOW()),
(6, 'Inventory Tracking', 'inventory_tracking', 'Track stock quantities and inventory movements.', 'Inventory', 1, NOW(), NOW()),
(7, 'Online Payments', 'online_payments', 'Accept MTN Mobile Money, Airtel Money and Cards.', 'Payments', 1, NOW(), NOW()),
(8, 'Custom User Roles', 'custom_user_roles', 'Create custom roles and fine-grained permissions.', 'Security', 1, NOW(), NOW()),
(9, 'Profit & Loss', 'profit_loss', 'Generate real-time Profit and Loss statements.', 'Reports', 1, NOW(), NOW()),
(10, 'Balance Sheet', 'balance_sheet', 'Comprehensive assets and liabilities reporting.', 'Reports', 1, NOW(), NOW()),
(11, 'Financial Reports', 'financial_reports', 'Advanced ledger, cash flow, and tax reports.', 'Reports', 1, NOW(), NOW()),
(12, 'Payroll', 'payroll', 'Employee records, PAYE, NSSF, and payslips.', 'HR', 1, NOW(), NOW()),
(13, 'Taxes', 'taxes', 'Uganda URA compliance: PAYE, NSSF, VAT, LED.', 'Compliance', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 3. Seed Plan Feature Matrix
-- Starter Plan Features (Plan ID 1)
INSERT INTO `subscription_plan_features` (`subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES
(1, 'vendor_management', 1, NOW(), NOW()),
(1, 'early_payment_discount', 0, NOW(), NOW()),
(1, 'journal_entries', 0, NOW(), NOW()),
(1, 'sales_orders', 0, NOW(), NOW()),
(1, 'purchase_orders', 0, NOW(), NOW()),
(1, 'inventory_tracking', 0, NOW(), NOW()),
(1, 'online_payments', 0, NOW(), NOW()),
(1, 'custom_user_roles', 0, NOW(), NOW()),
(1, 'profit_loss', 0, NOW(), NOW()),
(1, 'balance_sheet', 0, NOW(), NOW()),
(1, 'financial_reports', 0, NOW(), NOW()),
(1, 'payroll', 0, NOW(), NOW()),
(1, 'taxes', 0, NOW(), NOW());

-- Professional Plan Features (Plan ID 2)
INSERT INTO `subscription_plan_features` (`subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES
(2, 'vendor_management', 1, NOW(), NOW()),
(2, 'early_payment_discount', 1, NOW(), NOW()),
(2, 'journal_entries', 1, NOW(), NOW()),
(2, 'sales_orders', 0, NOW(), NOW()),
(2, 'purchase_orders', 0, NOW(), NOW()),
(2, 'inventory_tracking', 0, NOW(), NOW()),
(2, 'online_payments', 0, NOW(), NOW()),
(2, 'custom_user_roles', 0, NOW(), NOW()),
(2, 'profit_loss', 1, NOW(), NOW()),
(2, 'balance_sheet', 1, NOW(), NOW()),
(2, 'financial_reports', 1, NOW(), NOW()),
(2, 'payroll', 0, NOW(), NOW()),
(2, 'taxes', 0, NOW(), NOW());

-- Premium Plan Features (Plan ID 3)
INSERT INTO `subscription_plan_features` (`subscription_plan_id`, `feature_key`, `is_enabled`, `created_at`, `updated_at`) VALUES
(3, 'vendor_management', 1, NOW(), NOW()),
(3, 'early_payment_discount', 1, NOW(), NOW()),
(3, 'journal_entries', 1, NOW(), NOW()),
(3, 'sales_orders', 1, NOW(), NOW()),
(3, 'purchase_orders', 1, NOW(), NOW()),
(3, 'inventory_tracking', 1, NOW(), NOW()),
(3, 'online_payments', 1, NOW(), NOW()),
(3, 'custom_user_roles', 1, NOW(), NOW()),
(3, 'profit_loss', 1, NOW(), NOW()),
(3, 'balance_sheet', 1, NOW(), NOW()),
(3, 'financial_reports', 1, NOW(), NOW()),
(3, 'payroll', 1, NOW(), NOW()),
(3, 'taxes', 1, NOW(), NOW());

-- 4. Seed Subscription Settings
INSERT INTO `subscription_settings` (`key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES
('default_trial_days', '30', 'integer', 'Default trial period duration in days for newly registered businesses', NOW(), NOW()),
('currency', 'UGX', 'string', 'Primary system billing currency display name', NOW(), NOW()),
('currency_code', 'UGX', 'string', 'ISO 4217 Currency Code for billing and checkout', NOW(), NOW()),
('currency_symbol', 'UGX', 'string', 'Currency symbol representation', NOW(), NOW()),
('monthly_billing_enabled', '1', 'boolean', 'Enable monthly recurring subscription options', NOW(), NOW()),
('annual_billing_enabled', '1', 'boolean', 'Enable annual discount billing options', NOW(), NOW()),
('payment_gateway', 'MTN Mobile Money & Airtel Money', 'string', 'Default active payment gateway provider', NOW(), NOW()),
('trial_payment_required', '0', 'boolean', 'Require payment method entry before beginning trial', NOW(), NOW()),
('grace_period_days', '3', 'integer', 'Number of grace days after expiration before suspension', NOW(), NOW()),
('renewal_reminder_days', '7,3,1', 'string', 'Days prior to expiration to trigger automated warnings', NOW(), NOW()),
('expiration_behavior', 'read_only', 'string', 'System behavior upon unpaid expiration (read_only / lock_out)', NOW(), NOW())
ON DUPLICATE KEY UPDATE `value`=VALUES(`value`);

-- 5. Seed Businesses & Subscriptions
INSERT INTO `businesses` (`id`, `name`, `slug`, `owner_id`, `email`, `phone`, `tin_number`, `address`, `city`, `country`, `currency_code`, `currency_symbol`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Victoria Logistics & Trading Ltd', 'victoria-logistics', 1, 'contact@victoriatrading.co.ug', '+256 772 123456', '1002345678', 'Plot 42 Jinja Road, Industrial Area', 'Kampala', 'Uganda', 'UGX', 'UGX', 'active', NOW(), NOW()),
(2, 'Pearl Agri-Supplies Uganda', 'pearl-agri', 1, 'info@pearlagri.co.ug', '+256 701 987654', '1009876543', 'Nakasero Hill Plaza, Suite 4B', 'Kampala', 'Uganda', 'UGX', 'UGX', 'active', NOW(), NOW()),
(3, 'Rwenzori Tech Innovators', 'rwenzori-tech', 1, 'admin@rwenzoritech.ug', '+256 788 456789', '1014567890', 'Mbarara High Street, Block 12', 'Mbarara', 'Uganda', 'UGX', 'UGX', 'active', NOW(), NOW());

INSERT INTO `business_locations` (`id`, `business_id`, `name`, `address`, `city`, `phone`, `is_primary`, `created_at`, `updated_at`) VALUES
(1, 1, 'Kampala Central Warehouse', 'Plot 42 Jinja Road', 'Kampala', '+256 772 123456', 1, NOW(), NOW()),
(2, 1, 'Entebbe Airport Logistics Hub', 'Airport Cargo Terminal B', 'Entebbe', '+256 772 123457', 0, NOW(), NOW()),
(3, 2, 'Nakasero Distribution Outlet', 'Nakasero Market Lane', 'Kampala', '+256 701 987654', 1, NOW(), NOW());

INSERT INTO `subscriptions` (`id`, `business_id`, `user_id`, `subscription_plan_id`, `billing_cycle`, `amount`, `currency`, `status`, `trial_starts_at`, `trial_ends_at`, `starts_at`, `ends_at`, `next_billing_date`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 'yearly', 400000, 'UGX', 'active', NULL, NULL, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), DATE_ADD(NOW(), INTERVAL 1 YEAR), 'paid', NOW(), NOW()),
(2, 2, 1, 2, 'monthly', 25000, 'UGX', 'trialing', NOW(), DATE_ADD(NOW(), INTERVAL 24 DAY), NOW(), DATE_ADD(NOW(), INTERVAL 24 DAY), DATE_ADD(NOW(), INTERVAL 24 DAY), 'pending', NOW(), NOW()),
(3, 3, 1, 1, 'monthly', 15000, 'UGX', 'trialing', NOW(), DATE_ADD(NOW(), INTERVAL 3 DAY), NOW(), DATE_ADD(NOW(), INTERVAL 3 DAY), DATE_ADD(NOW(), INTERVAL 3 DAY), 'pending', NOW(), NOW());

INSERT INTO `subscription_payments` (`id`, `transaction_reference`, `business_id`, `subscription_id`, `plan_id`, `amount`, `currency`, `gateway`, `status`, `paid_at`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 'TXN-UGX-2026-00891', 1, 1, 3, 400000, 'UGX', 'MTN Mobile Money', 'successful', NOW(), '{"msisdn":"+256772123456","network":"MTN Uganda","network_ref":"MM-8923412"}', NOW(), NOW());

INSERT INTO `subscription_invoices` (`id`, `invoice_number`, `business_id`, `subscription_id`, `plan_id`, `amount`, `currency`, `billing_period_start`, `billing_period_end`, `status`, `payment_method`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 'SUB-INV-2026-001', 1, 1, 3, 400000, 'UGX', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 'paid', 'MTN Mobile Money', NOW(), NOW(), NOW());
