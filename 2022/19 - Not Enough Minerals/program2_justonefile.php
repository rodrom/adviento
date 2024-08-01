<?php

declare(strict_types=1);
use Rodrom\Advent202219\Blueprint;
include "vendor/autoload.php";

function game3(string $input): array
{
    $inputData = file($input, FILE_IGNORE_NEW_LINES);
    $r = [];
    $o = array_map(
        fn (int $t): int => intdiv(($t - 1) * $t, 2),
        range(0, 32)
    );    
    $numberBp = min (count($inputData), 3);
    for ($n = 0; $n < $numberBp; $n++) {
        // echo "$l => $line\n";
        $line = $inputData[$n];
        $bp = Blueprint::readBlueprint($line);
        $m = 0;
        $a = $bp->robots['ore']['ore'];
        $b = $bp->robots['clay']['ore'];
        $c = $bp->robots['obsidian']['ore'];
        $d = $bp->robots['obsidian']['clay'];
        $e = $bp->robots['geode']['ore'];
        $f = $bp->robots['geode']['obsidian'];
        $mi = $bp->maxQtyResource('ore');
        $mj = $bp->maxQtyResource('clay');
        $mk = $bp->maxQtyResource('obsidian');
        
        $dfs = function (int $t, int $g,
            int $i, int $j, int $k, int $l, // robots: i ore, j clay, k obsidian, l geode
            int $w, int $x, int $y, int $z  // available res.: w ore, x clay, y obsidian, z geode
        ) use (&$m, $mi, $mj, $mk, $a, $b, $c, $d, $e, $f, &$dfs, &$o)
        {
            if ($g === 0 && $i >= $mi ||
                $g === 1 && $j >= $mj ||
                $g === 2 && ($k >= $mk || $j == 0) ||
                $g === 3 && $k === 0 ||
                $z + $l * $t + $o[$t] <= $m
            ) {
                return;
            }
            $G = range(0,3);
            while ($t) {
                if ($g === 0 && $w >= $a) {
                    foreach ($G as $g) {
                        $dfs($t - 1, $g, $i + 1, $j, $k, $l, $w - $a + $i, $x + $j, $y + $k, $z + $l);
                    }
                    return;
                } elseif ($g === 1 && $w >= $b) {
                    foreach ($G as $g) {
                        $dfs($t - 1, $g, $i, $j + 1, $k, $l, $w - $b + $i, $x + $j, $y + $k, $z + $l);
                    }
                    return;
                } elseif ($g === 2 && $w >= $c && $x >= $d) {
                    foreach ($G as $g) {
                        $dfs($t - 1, $g, $i, $j, $k + 1, $l, $w - $c + $i, $x - $d + $j, $y + $k, $z + $l);
                    }
                    return;
                } elseif ($g === 3 && $w >= $e && $y >= $f) {
                    foreach ($G as $g) {
                        $dfs($t - 1, $g, $i, $j, $k, $l + 1, $w - $e + $i, $x + $j, $y - $f + $k, $z + $l);
                    }
                    return;
                }
                $t--; $w += $i; $x += $j; $y += $k; $z += $l;
            }
            $m = max($m, $z);
        };

        foreach (range(0,3) as $g) {
            $dfs(32, $g, 1, 0, 0, 0, 0, 0, 0, 0);
        }

        $r[$n] = $m;
    }
    return $r;
}

$r = game3("input.txt");

echo "Geodes for first 3 bp in 32 minutes: ". json_encode($r) ."\n";
echo "MULTIPLICATION: " . array_product($r);