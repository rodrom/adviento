<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202205\Crane2;

class Crane2Test extends TestCase
{
    public function test_execute_operations(): void
    {
        $cargo = [
            1 => ['Z', 'N'],
            2 => ['M', 'C', 'D'],
            3 => ['P'],
        ];

        $operations = [
            [1, 2, 1],
            [3, 1, 3],
            [2, 2, 1],
            [1, 1, 2]
        ];

        $crane2 = new Crane2($cargo);
        $crane2->executeOperations($operations);

        $this->assertEquals('MCD', $crane2->upperCrates());
    }
}