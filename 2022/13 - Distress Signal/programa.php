<?php

use Rodrom\Advent202213\PairOfPackets;

include "vendor/autoload.php";

$input = file_get_contents("input.txt");

$pairs = explode("\n\n", $input);
$correctPairs = [];
foreach($pairs as $i => $pair) {
    $pair = PairOfPackets::fromString($pair, $i+1);
    $result = $pair->checkOrder();
    $correctPairs[] = $result ? $pair->index : 0;
    echo "Pair $pair->index is " . ($result ? "in CORRECT order" : "in WRONG order") . PHP_EOL;
}

echo "The sum of all correct pair index is " . array_sum($correctPairs) . PHP_EOL;