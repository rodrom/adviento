<?php

declare(strict_types=1);

use Rodrom\Advent202223\Difussion;

include "vendor/autoload.php";

$dif = Difussion::readInput(file_get_contents($argv[1]));
[$emptyTiles, $stopRound] = $dif->play(intval($argv[2] ?? PHP_INT_MAX));

echo "P1: $emptyTiles | P2: $stopRound" . PHP_EOL;
