<?php

declare(strict_types=1);
namespace Rodrom\Advent202209\Tests;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202209\Bridge;
use Rodrom\Advent202209\Move;

class BridgeTest extends TestCase
{
    public function testCreateBridge(): void
    {
        $bridge = new Bridge();
        $this->assertInstanceOf(Bridge::class, $bridge);
    }

    public function testMoveRopeFromStartR4(): void
    {
        $bridge = new Bridge();
        $this->assertEquals(3, $bridge->moveRope("R 4"));
    }

    public function testExample(): void
    {
        $input = [
            "R 4",
            "U 4",
            "L 3",
            "D 1",
            "R 4",
            "D 1",
            "L 5",
            "R 2",
        ];
        
        $bridge = new Bridge();
        echo PHP_EOL;
        foreach ($input as $move) {
            $visited = $bridge->moveRope($move);
            echo  "move: $move - visited: $visited\n";
        }
        $this->assertEquals(13, $bridge->visitedPoints());
    }
}
