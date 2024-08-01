<?php

declare(strict_types=1);

use Rodrom\Advent202210\CRT;

include "vendor/autoload.php";

$input = file("input.txt", FILE_IGNORE_NEW_LINES);

$crt = new CRT(stack: $input);

$output = $crt->run();

echo $output;
