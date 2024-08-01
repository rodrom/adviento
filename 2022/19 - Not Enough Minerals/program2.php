<?php

/**
 * One performance improvement I made, which made a lot of difference
 * is before checking the cache, whether the state to evaluate is actually
 * more valuable than the max valuable state already evaluated for that
 * minute. You only need an array of size max_minutes to store the max
 * visited state. A state can never be more valuable if the projected value
 * for any kind of material is not > than the max value already evaluated.
 */

 /**
  * The upper bound on the number of geodes we can hit in the time
  * remaining is the current count so far,
  * plus the number the current set of robots could collect in the
  * remaining time, plus a quadratic sequence assuming we could
  * optimistically build a geode robot every minute. Prune the branch
  * if that's less than the best solution found so far.
  */


declare(strict_types=1);

use Rodrom\Advent202219\Blueprint;
use Rodrom\Advent202219\Simulation;

include "vendor/autoload.php";

function game2(string $input): array
{
    $inputData = file($input, FILE_IGNORE_NEW_LINES);
    $cache = [];
    $result = [];
    for ($i = 0; $i < 1; $i++) {
        // echo "$l => $line\n";
        $line = $inputData[$i];
        $simulation = new Simulation(Blueprint::readBlueprint($line), 5);
        $result[$i] = $simulation->qualityLevel(
            currentMinute: 32,
            robots: ['ore' => 1, 'clay' => 0, 'obsidian' => 0, 'geode' => 0],
            resources: ['ore' => 0, 'clay' => 0, 'obsidian' => 0, 'geode' => 0],
            cache: $cache,
        );
    }
    return $result;
}

$example = game2("example.txt");

echo "Geodes for first 3 bp in 32 minutes: ". json_encode($example) ."\n";
echo "MULTIPLICATION: " . array_product($example);