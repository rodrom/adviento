<?php

use Rodrom\Advent202213\PairOfPackets;

include "vendor/autoload.php";

$input = file_get_contents("input.txt");

$pairs = explode("\n\n", $input);
$correctPairs = [];
$dividedPackets = PairOfPackets::fromString("[2]\n[6]", 0);
$packets = [$dividedPackets->left, $dividedPackets->right];
foreach($pairs as $i => $pair) {
    $pair = PairOfPackets::fromString($pair, $i+1);
    array_push($packets, $pair->left, $pair->right);
}
$return = usort($packets, function ($a, $b) {
    return match (PairOfPackets::inOrder($a->payload, $b->payload)) {
        true => -1,
        null => 0,
        false => 1
    };
});

foreach ($packets as $i => $p) {
    if ($p->payload === [2]) {
        $code1 = $i + 1;
    }
    if ($p->payload === [6]) {
        $code2 = $i + 1;
    }
}

$code = $code1 * $code2;
echo "The ordering was " . $return ? "correct" : "failed" . PHP_EOL;
echo "The key for [2] is $code1" . PHP_EOL;
echo "The key for [6] is $code2" . PHP_EOL;
echo "The final code is $code\n";