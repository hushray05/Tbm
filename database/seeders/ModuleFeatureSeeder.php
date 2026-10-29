<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanFeature;
use Illuminate\Database\Seeder;

class ModuleFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $newFeatures = [
            ['key' => 'crm', 'name' => 'CRM & Lead Pipeline', 'description' => 'Customer relationship management and lead tracking.', 'category' => 'Sales'],
            ['key' => 'products', 'name' => 'Products & Catalog', 'description' => 'Product catalog management with images and pricing.', 'category' => 'Inventory'],
            ['key' => 'invoicing', 'name' => 'Invoicing & Billing', 'description' => 'Create and send customer invoices with VAT.', 'category' => 'Billing'],
            ['key' => 'pos', 'name' => 'POS & Checkout', 'description' => 'Point of sale terminal and shopping cart.', 'category' => 'Sales'],
            ['key' => 'expenses', 'name' => 'Expense Management', 'description' => 'Track and categorize business expenses.', 'category' => 'Accounting'],
            ['key' => 'credit_notes', 'name' => 'Credit Notes & Returns', 'description' => 'Issue credit notes for returns and adjustments.', 'category' => 'Sales'],
            ['key' => 'users_management', 'name' => 'User Management', 'description' => 'Multi-user access with role-based permissions.', 'category' => 'Security'],
            ['key' => 'multi_business', 'name' => 'Multi-Business & Branches', 'description' => 'Manage multiple businesses and branch locations.', 'category' => 'Operations'],
        ];

        foreach ($newFeatures as $f) {
            Feature::updateOrCreate(['key' => $f['key']], $f);
        }

        $plans = SubscriptionPlan::with('planFeatures')->get();

        $featureMatrix = [
            'starter' => [
                'crm' => true,
                'products' => true,
                'invoicing' => true,
                'pos' => true,
                'expenses' => true,
                'credit_notes' => false,
                'users_management' => false,
                'multi_business' => false,
                'inventory_tracking' => false,
                'sales_orders' => false,
                'purchase_orders' => false,
                'payroll' => false,
                'taxes' => false,
                'profit_loss' => false,
                'balance_sheet' => false,
                'financial_reports' => false,
                'online_payments' => false,
                'custom_user_roles' => false,
                'journal_entries' => false,
                'vendor_management' => true,
                'early_payment_discount' => false,
            ],
            'professional' => [
                'crm' => true,
                'products' => true,
                'invoicing' => true,
                'pos' => true,
                'expenses' => true,
                'credit_notes' => true,
                'users_management' => true,
                'multi_business' => false,
                'inventory_tracking' => true,
                'sales_orders' => true,
                'purchase_orders' => true,
                'payroll' => true,
                'taxes' => true,
                'profit_loss' => true,
                'balance_sheet' => true,
                'financial_reports' => true,
                'online_payments' => true,
                'custom_user_roles' => false,
                'journal_entries' => true,
                'vendor_management' => true,
                'early_payment_discount' => true,
            ],
            'premium' => [
                'crm' => true,
                'products' => true,
                'invoicing' => true,
                'pos' => true,
                'expenses' => true,
                'credit_notes' => true,
                'users_management' => true,
                'multi_business' => true,
                'inventory_tracking' => true,
                'sales_orders' => true,
                'purchase_orders' => true,
                'payroll' => true,
                'taxes' => true,
                'profit_loss' => true,
                'balance_sheet' => true,
                'financial_reports' => true,
                'online_payments' => true,
                'custom_user_roles' => true,
                'journal_entries' => true,
                'vendor_management' => true,
                'early_payment_discount' => true,
            ],
        ];

        foreach ($plans as $plan) {
            $slug = strtolower($plan->slug);
            $features = $featureMatrix[$slug] ?? $featureMatrix['starter'];

            foreach ($features as $key => $enabled) {
                SubscriptionPlanFeature::updateOrCreate(
                    ['subscription_plan_id' => $plan->id, 'feature_key' => $key],
                    ['is_enabled' => $enabled]
                );
            }
        }
    }
}
