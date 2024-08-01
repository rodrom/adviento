<?php

declare(strict_types=1);

use Rodrom\Advent202223\Difussion;

include "vendor/autoload.php";

$dif = Difussion::readInput(file_get_contents($argv[1]));
$emptyTiles = $dif->play(intval($argv[2] ?? 10));

echo $emptyTiles . PHP_EOL;
