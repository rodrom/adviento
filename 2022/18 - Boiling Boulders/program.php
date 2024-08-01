<?php

declare(strict_types=1);

use Rodrom\Advent202218\Scan3d;

include "vendor/autoload.php";
$exampleData = file_get_contents("example.txt");
$inputData = file_get_contents("input.txt");

$scan = Scan3d::readInput($exampleData);

$scan->createGraph();

echo "Surface (example): " . $scan->surface() . PHP_EOL;

$scan = Scan3d::readInput($inputData);
$scan->createGraph();
echo "Surface (input): " . $scan->surface() . PHP_EOL;

// PART 2
$scan = Scan3d::readInput($exampleData);
echo "External surface (example): " . $scan->blocksOfAir() . PHP_EOL;

$scan = Scan3d::readInput($inputData);
echo "External surface (input): " . $scan->blocksOfAir() . PHP_EOL;
