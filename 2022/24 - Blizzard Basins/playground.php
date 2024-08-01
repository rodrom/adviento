<?php
declare(strict_types=1);

use Rodrom\Advent202224\Board;

include "vendor/autoload.php";

$input = <<<EOD
    #.####
    #.^..#
    #.^..#
    #.^..#
    #....#
    ####.#

    EOD;

$b = Board::readInput($input);
$ini = 3;
$A = 4;
for ($i = 0; $i < 10; $i++) {
    echo ($A - 1 - (($A - 1 - $ini + $i) % $A)) . PHP_EOL;
}
