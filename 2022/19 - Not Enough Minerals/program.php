<?php

declare(strict_types=1);

use Rodrom\Advent202219\Blueprint;
use Rodrom\Advent202219\Simulation;

include "vendor/autoload.php";

function game(string $input): int
{
    $inputData = file($input, FILE_IGNORE_NEW_LINES);
    $cache = [];
    $result = 0;
    foreach ($inputData as $l => $line) {
        // echo "$l => $line\n";
        $simulation = new Simulation(Blueprint::readBlueprint($line), 5);
        $result += ($l + 1) * $simulation->qualityLevel(
            currentMinute: 24,
            robots: ['ore' => 1, 'clay' => 0, 'obsidian' => 0, 'geode' => 0],
            resources: ['ore' => 0, 'clay' => 0, 'obsidian' => 0, 'geode' => 0],
            cache: $cache
        );
    }
    return $result;
}

$example = game("input.txt");

echo "Quality Level: $example\n";