<?php

declare(strict_types=1);

use Rodrom\Advent202206\Device;

include "vendor/autoload.php";

$input = file("input.txt", FILE_IGNORE_NEW_LINES);
if (count($input) !== 1) throw new \Exception("Error in reading input file");

$start = Device::detectStartPacket($input[0]);

echo "first marker after character $start\n";
