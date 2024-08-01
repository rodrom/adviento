<?php

declare(strict_types=1);

use Rodrom\Advent202203\Rucksack;

include "vendor/autoload.php";

$rucksacks = file("input.txt", FILE_IGNORE_NEW_LINES);
$prioritizes = array_map(
    function ($line): int
    {
        $rucksack = new Rucksack($line);
        return (Rucksack::itemPrioritizeValue($rucksack->repeatedItem()));
    },
    $rucksacks
);

echo "The sum of repeated items in all compartments is " . array_sum($prioritizes) . ".\n";