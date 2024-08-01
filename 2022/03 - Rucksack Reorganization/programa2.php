<?php

declare(strict_types=1);

use Rodrom\Advent202203\Group;

include "vendor/autoload.php";

$rucksacks = file("input.txt", FILE_IGNORE_NEW_LINES);
$prioritizes = array_map(
    function (array $groupRucksacks): int
    {
        $group = new Group($groupRucksacks);
        $badge = $group->repeatedItem();
        echo "Badge Group: $badge" . PHP_EOL;
        return (Group::itemPrioritizeValue($badge));
    },
    array_chunk($rucksacks, 3)
);

echo "The sum of repeated items in all groups is " . array_sum($prioritizes) . ".\n";