<?php

declare(strict_types=1);

$input = file_get_contents("input.txt");
$plays = explode("\n", $input);

function resolvePlay(string $original): int
{
    $play = str_split($original);
    $playElf = $play[0];
    $playMe = $play[2];
    return pointsPlay($playMe) + pointsResult($playElf, $playMe);
}

function pointsPlay(string $item): int
{
    return match ($item) {
        'X' => 1,
        'Y' => 2,
        'Z' => 3,
    };
}

function pointsResult(string $p1, string $p2): int
{
    if ($p1 === 'A') {
        return match ($p2) {
            'X' => 3,
            'Y' => 6,
            'Z' => 0
        };
    } elseif ($p1 === 'B') {
        return match ($p2) {
            'X' => 0,
            'Y' => 3,
            'Z' => 6
        };
    } elseif ($p1 === 'C') {
        return match ($p2) {
            'X' => 6,
            'Y' => 0,
            'Z' => 3
        };
    }
    throw new Exception("Error in input");
}

$totalPoints = 0;
foreach ($plays as $k => $play) {
    if (preg_match("/^[ABC] [XYZ]$/", $play)) {
        $totalPoints += resolvePlay($play);
        echo "$k:$play:$totalPoints\n";
    }
}

echo "Total points $totalPoints\n";