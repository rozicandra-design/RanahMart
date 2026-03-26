<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

// Buat folder tmp yang dibutuhkan Laravel
$directories = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions',
    '/tmp/logs',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

$root = __DIR__ . '/../';

require $root . 'public/index.php';