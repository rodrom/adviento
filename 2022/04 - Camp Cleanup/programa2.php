<?php

declare(strict_types=1);
include("vendor/autoload.php");

use Rodrom\Advent202204\SectionPairing;

// leer input

$input = file("input.txt", FILE_IGNORE_NEW_LINES);
$results = array_map(function ($line) {
    $pair = SectionPairing::getPairingFromString($line);
    return $pair->isRangesOverlapping() ? 1 : 0;
}, $input);

echo "The number of overlaps pairings is " . array_sum($results);