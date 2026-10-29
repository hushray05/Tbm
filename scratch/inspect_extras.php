<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ORDERS ===\n";
print_r(Illuminate\Support\Facades\DB::table('orders')->get()->toArray());

echo "=== AUDIT LOGS ===\n";
print_r(Illuminate\Support\Facades\DB::table('audit_logs')->get()->toArray());

echo "=== SUB PAYMENTS ===\n";
print_r(Illuminate\Support\Facades\DB::table('subscription_payments')->get()->toArray());

echo "=== SUB INVOICES ===\n";
print_r(Illuminate\Support\Facades\DB::table('subscription_invoices')->get()->toArray());
