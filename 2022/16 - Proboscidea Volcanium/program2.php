<?php

declare(strict_types=1);

use Rodrom\Advent202216\Game;

include "vendor/autoload.php";

$input = file_get_contents("example.txt");
$game = Game::readInput($input);
$cache = $game->start("AA", 26);
$max = 0;
foreach ($cache as $bitmaskP1 => $maxflowP1) {
    foreach ($cache as $bitmaskP2 => $maxflowP2) {
        if (! ($bitmaskP1 & $bitmaskP2)) {
            if ($max < ($maxflowP1 + $maxflowP2)) {
                $max = $maxflowP1 + $maxflowP2;
            }
        }
    }
}
echo "MAX FLOW: " . $max . "\n";
