<?php

declare(strict_types=1);

use Rodrom\Advent202215\DeviceBCMath;

include "vendor/autoload.php";
$input = file_get_contents('input.txt');
$device = DeviceBCMath::readInput($input);
$y = "2000000";
// $y = "10";
$range = $device->forbiddenCoordinatesRange($y);

echo "The number of coordinates forbidden for Beacons in row $y is: " . $range . PHP_EOL;
