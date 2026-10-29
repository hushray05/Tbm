<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== USERS ===\n";
$users = \App\Models\User::all();
foreach ($users as $u) {
    echo "ID:{$u->id} | {$u->name} | {$u->email} | super={$u->is_super_admin} | biz={$u->business_id}\n";
}

echo "\n=== PLANS ===\n";
$plans = \App\Models\SubscriptionPlan::all();
foreach ($plans as $p) {
    echo "ID:{$p->id} | {$p->name} | slug={$p->slug} | active={$p->is_active}\n";
}

echo "\n=== SUBSCRIPTIONS ===\n";
$subs = \App\Models\Subscription::all();
foreach ($subs as $s) {
    echo "ID:{$s->id} | biz={$s->business_id} | plan={$s->plan_id} | status={$s->status} | ends={$s->ends_at}\n";
}

echo "\n=== BUSINESSES ===\n";
$bizs = \App\Models\Business::all();
foreach ($bizs as $b) {
    echo "ID:{$b->id} | {$b->name} | owner={$b->owner_id}\n";
}
