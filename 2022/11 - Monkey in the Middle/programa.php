<?php

declare(strict_types=1);

use Rodrom\Advent202211\Game;

include "vendor/autoload.php";
$rounds = 10000;
$game = new Game(max_rounds: $rounds);
$game->loadMonkeysFromFile("input.txt");
$original = $game->getMonkeys();
$mbl = $game->play();
$monkeys = $game->getMonkeys();
echo "The monkey business level after $rounds rounds is: $mbl\n";
echo "The two best monkeys are " . $monkeys[0]->id . " and " . $monkeys[1]->id . PHP_EOL;

foreach ($original as $m) {
    echo "Monkey $m->id inspected items $m->inspections times.\n";
}