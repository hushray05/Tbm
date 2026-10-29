<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Feature;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanFeature;
use App\Models\SubscriptionSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Only seeds essential platform data:
     * - Super Admin account
     * - Subscription Plans (Starter, Professional, Premium)
     * - Feature definitions
     * - Subscription settings
     * - Currencies
     * 
     * NO demo businesses, users, or fake data are seeded.
     * Real users register through the web app and get their own 30-day free trial.
     */
    public function run(): void
    {
        // =================================================================
        // 1. SUPER ADMIN ACCOUNT
        // =================================================================
        User::updateOrCreate(
            ['email' => 'admin@tbm-enterprise.com'],
            [
                'name' => 'TBM Administrator',
                'password' => Hash::make('Admin@TBM2024!'),
                'is_super_admin' => true,
                'job_title' => 'Super Administrator',
                'department' => 'Platform Control',
                'role' => 'Super Administrator',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // =================================================================
        // 2. SUBSCRIPTION PLANS
        // =================================================================
        $planStarter = SubscriptionPlan::updateOrCreate(
            ['slug' => 'starter'],
            [
                'name' => 'Starter',
                'description' => 'Efficiently organize your transactions, accounts, reports and books.',
                'badge' => 'STARTER',
                'monthly_price' => 15000,   // UGX 15,000/month
                'annual_price' => 150000,   // UGX 150,000/year
                'currency' => 'UGX',
                'currency_symbol' => 'UGX',
                'trial_enabled' => true,
                'trial_duration' => 30,
                'trial_unit' => 'Days',
                'max_users' => 1,
                'max_locations' => 1,
                'max_products' => 100,
                'max_invoices' => 100,
                'max_receipts' => 100,
                'max_purchases' => 100,
                'max_vendors' => 50,
                'max_customers' => 100,
                'max_storage_mb' => 500,
                'max_documents' => 200,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $planProfessional = SubscriptionPlan::updateOrCreate(
            ['slug' => 'professional'],
            [
                'name' => 'Professional',
                'description' => 'Professional tools for growing businesses.',
                'badge' => 'MOST POPULAR',
                'monthly_price' => 25000,   // UGX 25,000/month
                'annual_price' => 250000,   // UGX 250,000/year
                'currency' => 'UGX',
                'currency_symbol' => 'UGX',
                'trial_enabled' => true,
                'trial_duration' => 30,
                'trial_unit' => 'Days',
                'max_users' => 2,
                'max_locations' => 2,
                'max_products' => 500,
                'max_invoices' => 500,
                'max_receipts' => 500,
                'max_purchases' => 500,
                'max_vendors' => 200,
                'max_customers' => 500,
                'max_storage_mb' => 2000,
                'max_documents' => 1000,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        $planPremium = SubscriptionPlan::updateOrCreate(
            ['slug' => 'premium'],
            [
                'name' => 'Premium',
                'description' => 'Enhanced customization and automation that streamlines business processes.',
                'badge' => 'PREMIUM',
                'monthly_price' => 40000,   // UGX 40,000/month
                'annual_price' => 400000,   // UGX 400,000/year
                'currency' => 'UGX',
                'currency_symbol' => 'UGX',
                'trial_enabled' => true,
                'trial_duration' => 30,
                'trial_unit' => 'Days',
                'max_users' => 5,
                'max_locations' => 10,
                'max_products' => null,     // Unlimited
                'max_invoices' => null,     // Unlimited
                'max_receipts' => null,     // Unlimited
                'max_purchases' => null,    // Unlimited
                'max_vendors' => null,      // Unlimited
                'max_customers' => null,    // Unlimited
                'max_storage_mb' => 10000,
                'max_documents' => null,    // Unlimited
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        // =================================================================
        // 3. MASTER FEATURE DEFINITIONS
        // =================================================================
        $featuresList = [
            ['key' => 'vendor_management', 'name' => 'Vendor Management', 'description' => 'Manage vendors, suppliers, and vendor ledger balances.', 'category' => 'Purchases'],
            ['key' => 'early_payment_discount', 'name' => 'Early Payment Discount', 'description' => 'Configure early settlement discounts on customer invoices.', 'category' => 'Billing'],
            ['key' => 'journal_entries', 'name' => 'Journal Entries', 'description' => 'Create double-entry manual bookkeeping journal vouchers.', 'category' => 'Accounting'],
            ['key' => 'sales_orders', 'name' => 'Sales Orders', 'description' => 'Track commercial sales orders from draft to fulfilment.', 'category' => 'Sales'],
            ['key' => 'purchase_orders', 'name' => 'Purchase Orders', 'description' => 'Manage purchase requests and supplier purchase orders.', 'category' => 'Purchases'],
            ['key' => 'inventory_tracking', 'name' => 'Inventory Tracking', 'description' => 'Track stock quantities, stock in/out, and warehouse movements.', 'category' => 'Inventory'],
            ['key' => 'online_payments', 'name' => 'Online Payments', 'description' => 'Accept MTN Mobile Money, Airtel Money and Card payments.', 'category' => 'Payments'],
            ['key' => 'custom_user_roles', 'name' => 'Custom User Roles', 'description' => 'Create custom roles and fine-grained permissions for staff.', 'category' => 'Security'],
            ['key' => 'profit_loss', 'name' => 'Profit & Loss Statement', 'description' => 'Real-time Profit & Loss statement with revenue vs expense breakdown.', 'category' => 'Reports'],
            ['key' => 'balance_sheet', 'name' => 'Balance Sheet', 'description' => 'Complete statement of financial position (Assets vs Liabilities).', 'category' => 'Reports'],
            ['key' => 'financial_reports', 'name' => 'Financial Reports', 'description' => 'Trial balance, cash flow summary, and general ledger reports.', 'category' => 'Reports'],
            ['key' => 'payroll', 'name' => 'Payroll Management', 'description' => 'Staff records, monthly payroll processing, and statutory deductions.', 'category' => 'HR'],
            ['key' => 'taxes', 'name' => 'Tax Compliance (URA)', 'description' => 'Uganda Revenue Authority compliance: PAYE, NSSF, VAT 18%, LED.', 'category' => 'Compliance'],
        ];

        foreach ($featuresList as $f) {
            Feature::updateOrCreate(['key' => $f['key']], $f);
        }

        // =================================================================
        // 4. FEATURE ACCESS MATRIX (Per Plan)
        // =================================================================
        $starterFeatures = [
            'vendor_management' => true,
            'early_payment_discount' => false,
            'journal_entries' => false,
            'sales_orders' => false,
            'purchase_orders' => false,
            'inventory_tracking' => false,
            'online_payments' => false,
            'custom_user_roles' => false,
            'profit_loss' => false,
            'balance_sheet' => false,
            'financial_reports' => false,
            'payroll' => false,
            'taxes' => false,
        ];

        $proFeatures = [
            'vendor_management' => true,
            'early_payment_discount' => true,
            'journal_entries' => true,
            'sales_orders' => false,
            'purchase_orders' => false,
            'inventory_tracking' => false,
            'online_payments' => false,
            'custom_user_roles' => false,
            'profit_loss' => true,
            'balance_sheet' => true,
            'financial_reports' => true,
            'payroll' => false,
            'taxes' => false,
        ];

        $premiumFeatures = [
            'vendor_management' => true,
            'early_payment_discount' => true,
            'journal_entries' => true,
            'sales_orders' => true,
            'purchase_orders' => true,
            'inventory_tracking' => true,
            'online_payments' => true,
            'custom_user_roles' => true,
            'profit_loss' => true,
            'balance_sheet' => true,
            'financial_reports' => true,
            'payroll' => true,
            'taxes' => true,
        ];

        foreach ($starterFeatures as $k => $enabled) {
            SubscriptionPlanFeature::updateOrCreate(
                ['subscription_plan_id' => $planStarter->id, 'feature_key' => $k],
                ['is_enabled' => $enabled]
            );
        }

        foreach ($proFeatures as $k => $enabled) {
            SubscriptionPlanFeature::updateOrCreate(
                ['subscription_plan_id' => $planProfessional->id, 'feature_key' => $k],
                ['is_enabled' => $enabled]
            );
        }

        foreach ($premiumFeatures as $k => $enabled) {
            SubscriptionPlanFeature::updateOrCreate(
                ['subscription_plan_id' => $planPremium->id, 'feature_key' => $k],
                ['is_enabled' => $enabled]
            );
        }

        // =================================================================
        // 5. SUBSCRIPTION SETTINGS
        // =================================================================
        $settings = [
            ['key' => 'default_trial_days', 'value' => '30', 'type' => 'integer', 'description' => 'Default trial duration in days for new businesses'],
            ['key' => 'currency', 'value' => 'UGX', 'type' => 'string', 'description' => 'Primary billing currency'],
            ['key' => 'currency_code', 'value' => 'UGX', 'type' => 'string', 'description' => 'ISO 4217 currency code'],
            ['key' => 'currency_symbol', 'value' => 'UGX', 'type' => 'string', 'description' => 'Currency symbol'],
            ['key' => 'monthly_billing_enabled', 'value' => '1', 'type' => 'boolean', 'description' => 'Enable monthly billing'],
            ['key' => 'annual_billing_enabled', 'value' => '1', 'type' => 'boolean', 'description' => 'Enable annual billing'],
            ['key' => 'payment_gateway', 'value' => 'PesaPal', 'type' => 'string', 'description' => 'Payment gateway (PesaPal via store.pesapal.com/taskbusinessmanagerpos)'],
            ['key' => 'trial_payment_required', 'value' => '0', 'type' => 'boolean', 'description' => 'Require payment for trial'],
            ['key' => 'grace_period_days', 'value' => '3', 'type' => 'integer', 'description' => 'Grace period days after expiration'],
            ['key' => 'renewal_reminder_days', 'value' => '7,3,1', 'type' => 'string', 'description' => 'Reminder days before expiration'],
            ['key' => 'expiration_behavior', 'value' => 'redirect_subscription', 'type' => 'string', 'description' => 'Action on expiration'],
            ['key' => 'checkout_url', 'value' => 'https://store.pesapal.com/taskbusinessmanagerpos', 'type' => 'string', 'description' => 'PesaPal checkout URL'],
        ];

        foreach ($settings as $s) {
            SubscriptionSetting::updateOrCreate(['key' => $s['key']], $s);
        }

        // =================================================================
        // 6. CURRENCIES
        // =================================================================
        $currencies = [
            ['code' => 'UGX', 'name' => 'Ugandan Shilling', 'symbol' => 'UGX', 'exchange_rate' => 3710.0, 'is_active' => true, 'country' => 'Uganda', 'flag' => '🇺🇬'],
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'exchange_rate' => 1.0, 'is_active' => true, 'country' => 'United States', 'flag' => '🇺🇸'],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'exchange_rate' => 0.93, 'is_active' => true, 'country' => 'European Union', 'flag' => '🇪🇺'],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'exchange_rate' => 0.79, 'is_active' => true, 'country' => 'United Kingdom', 'flag' => '🇬🇧'],
            ['code' => 'KES', 'name' => 'Kenyan Shilling', 'symbol' => 'KES', 'exchange_rate' => 130.0, 'is_active' => true, 'country' => 'Kenya', 'flag' => '🇰🇪'],
            ['code' => 'TZS', 'name' => 'Tanzanian Shilling', 'symbol' => 'TZS', 'exchange_rate' => 2550.0, 'is_active' => true, 'country' => 'Tanzania', 'flag' => '🇹🇿'],
            ['code' => 'RWF', 'name' => 'Rwandan Franc', 'symbol' => 'RWF', 'exchange_rate' => 1285.0, 'is_active' => true, 'country' => 'Rwanda', 'flag' => '🇷🇼'],
            ['code' => 'ZAR', 'name' => 'South African Rand', 'symbol' => 'R', 'exchange_rate' => 18.6, 'is_active' => true, 'country' => 'South Africa', 'flag' => '🇿🇦'],
            ['code' => 'NGN', 'name' => 'Nigerian Naira', 'symbol' => '₦', 'exchange_rate' => 1600.0, 'is_active' => true, 'country' => 'Nigeria', 'flag' => '🇳🇬'],
            ['code' => 'GHS', 'name' => 'Ghanaian Cedi', 'symbol' => '₵', 'exchange_rate' => 13.5, 'is_active' => true, 'country' => 'Ghana', 'flag' => '🇬🇭'],
        ];

        foreach ($currencies as $c) {
            Currency::updateOrCreate(['code' => $c['code']], $c);
        }

        $this->command->info('✅ TBM database seeded successfully with essential platform data only.');
        $this->command->info('   Admin: admin@tbm-enterprise.com | Password: Admin@TBM2024!');
        $this->command->info('   Plans: Starter, Professional, Premium (all with 30-day free trial)');
        $this->command->info('   No demo data was created — ready for real users to register.');
    }
}
