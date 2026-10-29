<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== USERS ===\n";
foreach (App\Models\User::all(['id','name','email','role']) as $u) {
    echo "ID: {$u->id}, Name: {$u->name}, Email: {$u->email}, Role: {$u->role}\n";
}

echo "\n=== BUSINESSES ===\n";
foreach (App\Models\Business::all(['id','name','email']) as $b) {
    echo "ID: {$b->id}, Name: {$b->name}, Email: {$b->email}\n";
}

echo "\n=== SUBSCRIPTIONS ===\n";
foreach (App\Models\Subscription::all() as $s) {
    echo "ID: {$s->id}, Business: {$s->business_id}, Status: {$s->status}, Ends: {$s->ends_at}\n";
}
