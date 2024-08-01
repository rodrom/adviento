<?php

declare(strict_types=1);

use Rodrom\Advent202215\Device;

include "vendor/autoload.php";
$input = file_get_contents('input.txt');
$device = Device::readInput($input);
$y = 2000000;
// $y = 10;
$range = $device->forbiddenCoordinatesRange($y);
// Solution: 4424278 // My Result: 4890652
echo "The number of coordinates forbidden for Beacons in row $y is: " . $range . PHP_EOL;
