<?php
declare(strict_types=1);

use Rodrom\Advent202209\RopeWithKnots;

include "vendor/autoload.php";

$input = file("input.txt", FILE_IGNORE_NEW_LINES);
$rope = new RopeWithKnots();
foreach($input as $move) {
    [$head, $tail, $visitedMove] = $rope->move($move);
}
echo "The head is at $head, tail: $tail. \n";
echo "The visited points of the tail are: " . count($rope->getVisitedByTheTail()) . PHP_EOL;
