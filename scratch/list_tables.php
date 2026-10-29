<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$dbName = config('database.connections.mysql.database');
$tables = Illuminate\Support\Facades\DB::select("SHOW TABLES");
$key = "Tables_in_" . $dbName;
foreach ($tables as $t) {
    $tableName = $t->$key ?? array_values((array)$t)[0];
    $count = Illuminate\Support\Facades\DB::table($tableName)->count();
    echo "Table: {$tableName} (rows: {$count})\n";
}
