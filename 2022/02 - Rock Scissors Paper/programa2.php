<?php

declare(strict_types=1);

$input = file_get_contents("input.txt");
$plays = explode("\n", $input);

function resolvePlay(string $original): int
{
    $play = str_split($original);
    $playElf = $play[0];
    $result = $play[2];
    return pointsPlay($playElf, $result) + pointsResult($result);
}

function pointsResult(string $item): int
{
    return match ($item) {
        'X' => 0,
        'Y' => 3,
        'Z' => 6,
    };
}

function pointsPlay(string $p1, string $result): int
{
    if ($result === 'X') {
        return match ($p1) {
            'A' => 3,
            'B' => 1,
            'C' => 2
        };
    } elseif ($result === 'Y') {
        return match ($p1) {
            'A' => 1,
            'B' => 2,
            'C' => 3
        };
    } elseif ($result === 'Z') {
        return match ($p1) {
            'A' => 2,
            'B' => 3,
            'C' => 1
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