<?php

declare(strict_types=1);

use Rodrom\Advent202205\Crane;
use Rodrom\Advent202205\ParserInput;

include "vendor/autoload.php";

$input = file_get_contents("input.txt");

[$cargo, $operations] = ParserInput::initialCargoAndOperations($input);

$crane = new Crane($cargo);
$crane->executeOperations($operations);

echo "The upper cranes after rearrangement is: " . $crane->upperCrates() . PHP_EOL;
