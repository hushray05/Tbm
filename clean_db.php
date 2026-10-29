<?php
/**
 * Clean DB Script: removes all seed/demo data.
 * Keeps: Super Admin, Subscription Plans, Plan Features, Subscription Settings, Currencies, Pages.
 * The real user (hushray05@gmail.com, business 4) is also preserved.
 */
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== TBM Database Cleanup ===\n\n";

// Identify what to keep
$superAdminId = 1; // admin@tbm-enterprise.com
$realUserId = 8;   // hushray05@gmail.com
$realBusinessId = 4; // hushrayr

// IDs of seed businesses to remove
$seedBusinessIds = [1, 2, 3];
$seedUserIds = [2, 3, 4, 5, 6, 7];

// Tables to clear based on seed businesses
$businessDependentTables = [
    'payrolls', 'staff', 'tax_records', 'expenses', 'orders',
    'invoice_items', 'invoices', 'products', 'parties',
    'business_locations', 'audit_logs', 'leads', 'tickets',
    'tasks', 'carts', 'cart_items', 'subscription_events',
    'subscription_invoices', 'subscription_payments',
];

// Delete subscription-related for seed businesses
echo "Removing seed subscriptions...\n";
$seedSubIds = DB::table('subscriptions')->whereIn('business_id', $seedBusinessIds)->pluck('id')->toArray();
if (!empty($seedSubIds)) {
    DB::table('subscription_events')->whereIn('subscription_id', $seedSubIds)->delete();
    DB::table('subscription_invoices')->whereIn('subscription_id', $seedSubIds)->delete();
    DB::table('subscription_payments')->whereIn('subscription_id', $seedSubIds)->delete();
    DB::table('subscriptions')->whereIn('id', $seedSubIds)->delete();
}
echo "  ✓ Seed subscriptions removed\n";

// Delete per-table data for seed businesses
foreach ($businessDependentTables as $table) {
    try {
        if (DB::getSchemaBuilder()->hasTable($table)) {
            $deleted = DB::table($table)->whereIn('business_id', $seedBusinessIds)->delete();
            if ($deleted > 0) echo "  ✓ Cleaned {$deleted} rows from {$table}\n";
        }
    } catch (\Exception $e) {
        // Skip if column doesn't exist
    }
}

// Clean audit logs generally (seed data)
try {
    DB::table('audit_logs')->whereIn('user_id', $seedUserIds)->delete();
    DB::table('audit_logs')->whereIn('business_id', $seedBusinessIds)->delete();
    echo "  ✓ Cleaned audit_logs\n";
} catch (\Exception $e) {}

// Remove seed users
echo "\nRemoving seed users...\n";
DB::table('users')->whereIn('id', $seedUserIds)->delete();
echo "  ✓ Seed users removed (IDs: " . implode(', ', $seedUserIds) . ")\n";

// Remove seed businesses
echo "\nRemoving seed businesses...\n";
DB::table('businesses')->whereIn('id', $seedBusinessIds)->delete();
echo "  ✓ Seed businesses removed (IDs: " . implode(', ', $seedBusinessIds) . ")\n";

// Remove messages for seed users
try {
    DB::table('messages')->whereIn('user_id', $seedUserIds)->delete();
    DB::table('messages')->whereIn('business_id', $seedBusinessIds)->delete();
    echo "  ✓ Cleaned messages\n";
} catch (\Exception $e) {}

// Final state report
echo "\n=== REMAINING DATA ===\n";
echo "Users: " . \App\Models\User::count() . "\n";
foreach (\App\Models\User::all() as $u) {
    echo "  - [{$u->id}] {$u->name} | {$u->email} | super={$u->is_super_admin}\n";
}

echo "\nBusinesses: " . \App\Models\Business::count() . "\n";
foreach (\App\Models\Business::all() as $b) {
    echo "  - [{$b->id}] {$b->name}\n";
}

echo "\nSubscriptions: " . \App\Models\Subscription::count() . "\n";
foreach (\App\Models\Subscription::all() as $s) {
    echo "  - [{$s->id}] biz={$s->business_id} | status={$s->status} | ends={$s->ends_at}\n";
}

echo "\nSubscription Plans: " . \App\Models\SubscriptionPlan::count() . "\n";
foreach (\App\Models\SubscriptionPlan::all() as $p) {
    echo "  - [{$p->id}] {$p->name} (slug={$p->slug})\n";
}

echo "\n✅ Cleanup complete!\n";
