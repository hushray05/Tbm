<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// 1. Update Admin User to fresh System Administrator
$admin = User::find(1);
if ($admin) {
    $admin->update([
        'name' => 'System Administrator',
        'email' => 'admin@tbm-enterprise.com',
        'password' => Hash::make('Admin@TBM2024!'),
        'role' => 'Super Administrator',
        'is_super_admin' => 1,
        'avatar_url' => '/images/logo.png',
        'job_title' => 'Chief Technology Officer',
        'department' => 'Executive Office',
        'phone' => '+256 700 000000',
        'bio' => 'Lead System Administrator for TBM Enterprise Suite.',
        'status' => 'active',
        'business_id' => null,
    ]);
    echo "Admin User (ID 1) updated to fresh 'System Administrator' (admin@tbm-enterprise.com)\n";
}

// 2. Export full MySQL SQL file
$dbName = config('database.connections.mysql.database');
$tables = DB::select("SHOW TABLES");
$key = "Tables_in_" . $dbName;

$sqlOutput = "-- ====================================================================\n";
$sqlOutput .= "-- TBM Enterprise Suite & Uganda Multi-Business SaaS Database Dump\n";
$sqlOutput .= "-- Exported on: " . date('Y-m-d H:i:s') . "\n";
$sqlOutput .= "-- Database: {$dbName}\n";
$sqlOutput .= "-- Server: MySQL 8.0+ / MariaDB 10.4+\n";
$sqlOutput .= "-- ====================================================================\n\n";
$sqlOutput .= "SET FOREIGN_KEY_CHECKS=0;\n";
$sqlOutput .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
$sqlOutput .= "SET time_zone = \"+00:00\";\n\n";

foreach ($tables as $t) {
    $tableName = $t->$key ?? array_values((array)$t)[0];
    
    // Get CREATE TABLE
    $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
    $createSql = $createTable[0]->{'Create Table'} ?? array_values((array)$createTable[0])[1];
    
    $sqlOutput .= "-- --------------------------------------------------------\n";
    $sqlOutput .= "-- Structure for table `{$tableName}`\n";
    $sqlOutput .= "-- --------------------------------------------------------\n";
    $sqlOutput .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
    $sqlOutput .= $createSql . ";\n\n";
    
    // Get table rows
    $rows = DB::table($tableName)->get();
    if ($rows->count() > 0) {
        $sqlOutput .= "-- Dumping data for table `{$tableName}`\n";
        foreach ($rows as $row) {
            $rowArray = (array)$row;
            $columns = array_keys($rowArray);
            $escapedValues = array_map(function($val) {
                if (is_null($val)) return "NULL";
                return "'" . addslashes((string)$val) . "'";
            }, array_values($rowArray));
            
            $colList = "`" . implode("`, `", $columns) . "`";
            $valList = implode(", ", $escapedValues);
            $sqlOutput .= "INSERT INTO `{$tableName}` ({$colList}) VALUES ({$valList});\n";
        }
        $sqlOutput .= "\n";
    }
}

$sqlOutput .= "SET FOREIGN_KEY_CHECKS=1;\n";

$sqlFile = base_path('database/tbm_database_clean.sql');
file_put_contents($sqlFile, $sqlOutput);
echo "Clean SQL file generated successfully: {$sqlFile} (" . strlen($sqlOutput) . " bytes)\n";

// Also copy to root for quick access
copy($sqlFile, base_path('tbm_database_clean.sql'));
echo "Copied to workspace root: " . base_path('tbm_database_clean.sql') . "\n";
