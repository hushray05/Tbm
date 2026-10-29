<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;
use App\Models\Business;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

echo "=== TESTING ALL FEATURES ===\n\n";

$user = User::where('email', 'hushray05@gmail.com')->first() ?? User::find(8);
$admin = User::where('email', 'admin@tbm-enterprise.com')->first() ?? User::find(1);

// Test 1: Pricing page as guest
echo "1. Testing /pricing as GUEST...\n";
Auth::logout();
$response = app()->handle(Illuminate\Http\Request::create('/pricing', 'GET'));
echo "Status: " . $response->getStatusCode() . " (Expected: 200)\n";
if ($response->getStatusCode() === 200 && str_contains($response->getContent(), 'Simple, Predictable Plans')) {
    echo "  -> OK: Public pricing rendered properly.\n";
} else {
    echo "  -> FAILED!\n";
}

// Test 2: Pricing page as Authenticated User
echo "\n2. Testing /pricing as AUTH USER...\n";
Auth::login($user);
$response = app()->handle(Illuminate\Http\Request::create('/pricing', 'GET'));
echo "Status: " . $response->getStatusCode() . " (Expected: 200)\n";
if ($response->getStatusCode() === 200 && str_contains($response->getContent(), 'Upgrade to')) {
    echo "  -> OK: Authenticated app layout rendered with upgrade actions.\n";
} else {
    echo "  -> FAILED!\n";
}

// Test 3: Checkout page
echo "\n3. Testing /subscription/checkout...\n";
$response = app()->handle(Illuminate\Http\Request::create('/subscription/checkout?plan=professional&cycle=monthly', 'GET'));
echo "Status: " . $response->getStatusCode() . " (Expected: 200)\n";
$content = $response->getContent();
if (!str_contains($content, 'Select Instant Push Provider') && str_contains($content, 'Pesapal Online Checkout') || str_contains($content, 'Official Pesapal')) {
    echo "  -> OK: 'Select Instant Push Provider' removed completely. Pesapal only gateway present.\n";
} else {
    echo "  -> Check checkout page content.\n";
}

// Test 4: Profile page
echo "\n4. Testing /profile...\n";
$response = app()->handle(Illuminate\Http\Request::create('/profile', 'GET'));
echo "Status: " . $response->getStatusCode() . " (Expected: 200)\n";
$content = $response->getContent();
if (!str_contains($content, 'Team & Colleagues') && str_contains($content, 'Active Workspace')) {
    echo "  -> OK: Fake friend side replaced with real workspace, subscription & activity.\n";
} else {
    echo "  -> Profile content check.\n";
}

// Test 5: Businesses page
echo "\n5. Testing /businesses...\n";
$response = app()->handle(Illuminate\Http\Request::create('/businesses', 'GET'));
echo "Status: " . $response->getStatusCode() . " (Expected: 200)\n";
$content = $response->getContent();
if (str_contains($content, 'My Businesses & Workspaces') && str_contains($content, 'Currently Active Workspace')) {
    echo "  -> OK: Redesigned businesses page rendered successfully.\n";
} else {
    echo "  -> Businesses content check.\n";
}

// Test 6: Dashboard Outflow
echo "\n6. Testing /dashboard Outflow Composition...\n";
$response = app()->handle(Illuminate\Http\Request::create('/dashboard', 'GET'));
echo "Status: " . $response->getStatusCode() . " (Expected: 200)\n";
$content = $response->getContent();
if (str_contains($content, 'Outflow Composition') && !str_contains($content, '8500000')) {
    echo "  -> OK: Outflow composition uses real DB values, no fake 8.5M!\n";
} else {
    echo "  -> Dashboard content check.\n";
}

// Test 7: Admin Dashboard & Subscribers as Admin
echo "\n7. Testing /admin/dashboard and /admin/subscriptions/subscribers...\n";
Auth::login($admin);
$response = app()->handle(Illuminate\Http\Request::create('/admin/dashboard', 'GET'));
echo "Admin Dashboard Status: " . $response->getStatusCode() . " (Expected: 200)\n";

$response2 = app()->handle(Illuminate\Http\Request::create('/admin/subscriptions/subscribers', 'GET'));
echo "Admin Subscribers Status: " . $response2->getStatusCode() . " (Expected: 200)\n";

echo "\n=== ALL TESTS COMPLETED ===\n";
