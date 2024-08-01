<?php

declare(strict_types=1);

use Rodrom\Advent202217\Board;
use Rodrom\Advent202217\Piece;

include "vendor/autoload.php";

$rocks = [
    0 => new Piece(
        height: 1,
        width: 4,
        form: [ 
            [0][0] => '#', [1][0] => '#', [2][0] => '#', [3][0] => '#'
        ],
        id: 0
    ),
    1 => new Piece(
        height: 3,
        width: 3,
        form: [
            0 => [ -2 => '.', -1 => '#', 0 => '.'],
            1 => [ -2 => '#', -1 => '#', 0 => '#'],
            2 => [ -2 => '.', -1 => '#', 0 => '.']
        ],
        id: 1
    ),
    2 => new Piece(
        height: 3,
        width: 3,
        form: [
            0 => [ -2 => '.', -1 => '.', 0 => '#'],
            1 => [ -2 => '.', -1 => '.', 0 => '#'],
            2 => [ -2 => '#', -1 => '#', 0 => '#']
        ],
        id: 2
    ),
    3 => new Piece(
        height: 4,
        width: 1,
        form: [
            0 => [-3 => '#', -2 => '#', -1 => '#', 0 => '#']
        ],
        id: 3
    ),
    4 => new Piece(
        height: 2,
        width: 2,
        form: [
            0 => [ -1 => '#', 0 => '#' ],
            1 => [ -1 => '#', 0 => '#' ],
        ],
        id: 4
    ),
];

$board = new Board();
// $board2 = new Board();

$board->setPiecesSequence($rocks);
// $board2->setPiecesSequence($rocks);

$board->setJetsSequence(file_get_contents("input.txt"));
// $board2->setJetsSequence(file_get_contents("input.txt"));

define( 'MAX_PLAYS', 10000);
define( 'DEFAULT_CUT', 100);
if (count($argv) > 1) {
    $plays = min(intval($argv[1]), MAX_PLAYS);
    $realPlays = intval($argv[1]);
    if (count($argv) === 3) {
        $cut = intval($argv[2]);
    } else {
        $cut = DEFAULT_CUT;
    }
} else {
    $plays = 2022;
    $realPlays = $plays;
    $cut = DEFAULT_CUT;
}
$seen = [];
for ($i = 0; $i < $plays; $i++) {
    $board->playPieceAtTheTop();
    // $board2->playPieceAtTheTop();
    // $seen2 = [];
    $board->trim($board->getTowerHeight() - $cut);
    if ($i > 20) {
        $bricks = array_sum(array_map(fn (SplStack $el) => $el->count(), $board->columns));
        // $bricks2 = array_map(fn (SplStack $el) => $el->count(), $board2);
        $tops = array_map(fn (SplStack $el) => $el->top(), $board->columns);
        $top = max($tops);
        $totalTops = array_sum($tops);
        // $tops2 = array_map(fn (SplStack $el) => $el->top(), $board);
        $key = $bricks . "-" . $board->lastPiece . "-" . $board->nextJet;
        // $key2 = implode(".", $bricks2) . "-"  . implode(".", $tops2) . "-" . $board2->lastPiece . "-" . $board2->nextJet;
        $seen[$key][] = [$i, $top];
        //echo count($seen[$key]);
        // $seen[$key2][] = [$i, max($tops2)];
        if (count($seen[$key]) > 1) {
            [$r1, $h1] = $seen[$key][count($seen[$key]) - 2];
            [$r2, $h2] = $seen[$key][count($seen[$key]) - 1];
            if (( $realPlays - $r1 ) % ($r2 - $r1) === 0) {
                $result = intdiv(($realPlays - $r1), ($r2 - $r1)) * ($h2 - $h1) + $h1 - 1;
                echo "example.txt=" . $realPlays . PHP_EOL;
                echo "after $r1 rocks height is $h1" . PHP_EOL;
                echo "intdiv(($realPlays - $r1), ($r2 - $r1)) * ($h2 - $h1) + $h1 - 1 = $result" . PHP_EOL;
                exit;
            }
        }
    }
}

echo $board->getTowerHeight() . PHP_EOL;
// echo $board2->getTowerHeight() . PHP_EOL;
// echo $board2->__toString();