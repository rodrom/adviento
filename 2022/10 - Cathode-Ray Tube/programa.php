<?php

declare(strict_types=1);

use Rodrom\Advent202210\CPU;

include "vendor/autoload.php";

$input = file("input.txt", FILE_IGNORE_NEW_LINES);

$cpu = new CPU(stack: $input);

$acc = $cpu->run();

echo "The sums of all strength signals is = $acc\n";
