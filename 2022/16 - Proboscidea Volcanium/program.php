<?php

declare(strict_types=1);

use Rodrom\Advent202216\Game;

include "vendor/autoload.php";

$input = file_get_contents("input.txt");
$game = Game::readInput($input);
$maxflow = $game->start("AA", 30);
echo "MAX FLOW: " . $maxflow . "\n";
