<?php
$tables = ['users', 'subscriptions', 'subscription_plans', 'currencies', 'pages', 'features', 'subscription_plan_features'];
foreach($tables as $t) {
    $count = \Illuminate\Support\Facades\DB::table($t)->count();
    echo "$t: $count rows\n";
}
?>