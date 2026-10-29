<?php
$lines = file('tbm_database_full.sql');
echo "Total lines: ".count($lines)."\n";
for ($i=0; $i<15; $i++) echo ($i+1).": ".$lines[$i];
echo "\n...\n";
echo "Contains firstlovestud_thm: ".(strpos(file_get_contents('tbm_database_full.sql'),'firstlovestud_thm')!==false?'YES':'NO')."\n";
echo "Contains users table: ".(strpos(file_get_contents('tbm_database_full.sql'),'CREATE TABLE `users`')!==false?'YES':'NO')."\n";
echo "Contains subscriptions: ".(strpos(file_get_contents('tbm_database_full.sql'),'CREATE TABLE `subscriptions`')!==false?'YES':'NO')."\n";
echo "File size: ".filesize('tbm_database_full.sql')." bytes\n";
