<?php
declare(strict_types=1);

use Rodrom\Advent202209\Bridge;

include "vendor/autoload.php";

$input = file("input.txt", FILE_IGNORE_NEW_LINES);
$bridge = new Bridge();
foreach($input as $move) {
    $visited = $bridge->moveRope($move);
}
echo "The visited points of the tail are: " . $bridge->visitedPoints() . PHP_EOL;
