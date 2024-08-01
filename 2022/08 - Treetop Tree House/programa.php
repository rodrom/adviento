<?php

use Rodrom\Advent202208\Grid;

include "vendor/autoload.php";

$input = file("input.txt", FILE_IGNORE_NEW_LINES);

$grid = Grid::loadMap($input);

echo "number of visible trees: " . $grid->visibleTrees() . PHP_EOL;
echo "maxTsr: " . $grid->maxTsr() . PHP_EOL;