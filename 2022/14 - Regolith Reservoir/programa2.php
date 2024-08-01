<?php
declare(strict_types=1);

use Rodrom\Advent202214\Cave;

include "vendor/autoload.php";

$input = file_get_contents('input.txt');

$cave = Cave::fromString($input);
$numberOfRocks = $cave->rocks->count();

$abyss = $cave->expellSand();
$numberOfTotalElements = $cave->map->count();
echo "The abyss of this cave is $abyss\n";
echo "The number of sand through to reach the abyss is: " . $numberOfTotalElements - $numberOfRocks . PHP_EOL;
// 10648