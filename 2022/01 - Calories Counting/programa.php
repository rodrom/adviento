<?php

declare(strict_types=1);

$input = file_get_contents("input.txt");

$elves = explode("\n\n", $input);

function calcCalories(string $input): int
{
    $caloriesItems = explode("\n", $input);
    $totalCalories = 0;
    foreach ($caloriesItems as $item) {
        $totalCalories += intval($item);
    }

    return $totalCalories;
}

$maxElf = 0;
$maxCalories = 0;
$order = [];

foreach ($elves as $k => $elf) {
    $calories = calcCalories($elf);
    $order[$k] = $calories;
    if ($maxCalories < $calories) {
        $maxElf = $k;
        $maxCalories = $calories;
    }
}

rsort($order, SORT_NUMERIC);

echo "The ${maxElf}nth elf carries $maxCalories\n";
$topThree = 0;
for($i =0; $i < 3; $i++) {
    echo "The " . $i + 1 . " carries " . $order[$i] . PHP_EOL;
    $topThree += $order[$i];
}

echo "The top 3 elves carrie $topThree calories";
