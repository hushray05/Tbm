<?php
$dir = 'resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$count = 0;
foreach($files as $f) {
    $ext = $f->getExtension();
    if ($ext !== 'php') continue;
    $path = $f->getRealPath();
    $content = file($path);
    foreach($i = 0; $i < count($content); $i++) {
        $line = $content[$i];
        if (stripos($line, 'logo') !== false || stripos($line, 'TBM') !== false || stripos($line, 'favicon') !== false) {
            echo $path . ': Line ' . ($i+1) . ': ' . $line . "\n";
            $count++;
        }
    }
}
echo "Total: $count matches\n";