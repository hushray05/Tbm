<?php
// Test 1: localhost
$conn1 = @new mysqli('localhost', 'firstlovestud_thm', 'Kulikuli55', 'firstlovestud_thm');
echo "Localhost: " . ($conn1->connect_error ? "❌ " . $conn1->connect_error : "✅ Connected!") . "<br>";

// Test 2: 127.0.0.1
$conn2 = @new mysqli('127.0.0.1', 'firstlovestud_thm', 'Kulikuli55', 'firstlovestud_thm');
echo "127.0.0.1: " . ($conn2->connect_error ? "❌ " . $conn2->connect_error : "✅ Connected!");
?>