<?php
declare(strict_types=1);

namespace Rodrom\Advent202205\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202205\Crane;

class CraneTest extends TestCase
{
    private array $cargo = [
        1 => ['Z', 'N'],
        2 => ['M', 'C', 'D'],
        3 => ['P'],
    ];

    private array $operations = [
            [1, 2, 1],
            [3, 1, 3],
            [2, 2, 1],
            [1, 1, 2]
    ];

    public function test_create_crane(): void
    {
        $crane = new Crane($this->cargo);

        $this->assertInstanceOf(Crane::class, $crane);
    }

    public function test_move_crates(): void
    {
        $crane = new Crane([ 
            1 => ['A'],
            2 => []
        ]);

        $crane->moveCrates(1, 1, 2);

        $this->assertEquals([
                1 => [],
                2 => ['A']
            ],
            $crane->cargo
        );
    }

    public function test_execute_operations(): void
    {
        $crane = new Crane($this->cargo);
        $crane->executeOperations($this->operations);

        $expectedCraneDistribution = [
            1 => ['C'],
            2 => ['M'],
            3 => ['P', 'D', 'N', 'Z'],
        ];

        $this->assertEquals($expectedCraneDistribution, $crane->cargo);
    }

    public function test_last_upper_crates(): void
    {
        $crane = new Crane($this->cargo);
        
        $this->assertEquals("NDP", $crane->upperCrates());
    }
}
