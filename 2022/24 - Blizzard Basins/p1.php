<?php

declare(strict_types=1);

use Rodrom\Advent202224\Board;

include "vendor/autoload.php";

$inputfile = $argv[1] ?? "example.txt";
$input = file_get_contents($inputfile);

$b = Board::readInput($input);

echo "TIME TO FINISH: " . $b->bfs(0, 0, -1, 1, 1) . PHP_EOL;
