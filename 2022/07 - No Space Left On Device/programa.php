<?php

declare(strict_types=1);

use Rodrom\Advent202207\Folder;
use Rodrom\Advent202207\Prompt;

include "vendor/autoload.php";

$root = new Folder(null, "/");
$prompt = new Prompt($root);
$input = file("input.txt", FILE_IGNORE_NEW_LINES);
$prompt->loadInput($input);

while($prompt->readCommand());

echo $root->size() . PHP_EOL;
foreach ($root as $f) {
    echo $f->name() . ": " . $f->size() . PHP_EOL;
}

