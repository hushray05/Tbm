<?php
$envPath = __DIR__ . '/.env';
$content = file_get_contents($envPath);
echo "=== .env file content (first 50 lines) ===\n";
$lines = explode("\n", $content);
foreach ($i => $line) {
    if ($i >= 50) break;
    echo "$i: $line\n";
}
echo "\n=== Checking for specific vars ===\n";
echo "APP_URL set: " . (getenv('APP_URL') ? 'yes' : 'no') . "\n";
echo "DB_DATABASE set: " . (getenv('DB_DATABASE') ? 'yes' : 'no') . "\n";
echo "DB_USERNAME set: " . (getenv('DB_USERNAME') ? 'yes' : 'no') . "\n";
?>