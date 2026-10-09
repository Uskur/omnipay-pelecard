<?php
declare(strict_types=1);

$root = __DIR__;
do {
    $autoload = $root . '/vendor/autoload.php';
    if (is_file($autoload)) {
        require $autoload;

        return;
    }
    $parent = dirname($root);
    if ($parent === $root) {
        throw new RuntimeException('Unable to locate Composer autoload.php.');
    }
    $root = $parent;
} while (true);
