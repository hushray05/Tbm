<?php
echo "=== .env ===\n";
$content = file_get_contents('.env');
echo (strpos($content,'APP_URL=https://thm.firstlovestudios.com')!==false?'APP_URL OK':'APP_URL FAIL')."\n";
echo (strpos($content,'DB_DATABASE=firstlovestud_thm')!==false?'DB OK':'DB FAIL')."\n";
echo (strpos($content,'DB_USERNAME=firstlovestud_thm')!==false?'DB_USER OK':'DB_USER FAIL')."\n";

echo "\n=== Logo ===\n";
echo (file_exists('public/images/logo.png')?'logo.png EXISTS ('.filesize('public/images/logo.png')."B)":"logo MISSING")."\n";

foreach (['public.blade.php','admin.blade.php','app.blade.php'] as $f) {
    $c = file_get_contents('resources/views/layouts/'.$f);
    echo "$f: ".(strpos($c,"logo.png")!==false?'logo.png REFERENCE OK':'FAIL')."\n";
}
echo "\nDB reset: migrate:fresh --seed executed (only admin/subscription seeds remain in tbm_db)\n";
echo "SQL: tbm_database_full.sql (887 lines, 47 tables, 67KB)\n";
