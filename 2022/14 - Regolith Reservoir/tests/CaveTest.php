<?php

declare(strict_types=1);
namespace Rodrom\Advent202214\Test;
use PHPUnit\Framework\TestCase;
use Rodrom\Advent202214\Cave;
use Rodrom\Advent202214\Cell;
use Rodrom\Advent202214\Coordinate;

class CaveTest extends TestCase
{
    public function test_downIsAir(): void
    {
        $cave = Cave::fromString(
            "498,4 -> 498,6 -> 496,6\n503,4 -> 502,4 -> 502,9 -> 494,9\n",
            500, 0
        );

        $this->assertTrue($cave->downIsAir($cave->sandOrigin));
        $this->assertFalse($cave->downIsAir(new Coordinate(498,3,0,'')));
    }

    public function test_downIsBlock(): void
    {
        $cave = Cave::fromString(
            "498,4 -> 498,6 -> 496,6\n503,4 -> 502,4 -> 502,9 -> 494,9\n",
            500, 0
        );

        $this->assertFalse($cave->downIsBlock($cave->sandOrigin));
        $this->assertTrue($cave->downIsBlock(new Coordinate(498,3,0,'')));
    }

    public function test_downToLeft(): void
    {
        $cave = Cave::fromString(
            "498,4 -> 498,6 -> 496,6\n503,4 -> 502,4 -> 502,9 -> 494,9\n",
            500, 0
        );

        $this->assertFalse($cave->downToLeft(new Coordinate(499,3,0,'')));
        $this->assertTrue($cave->downToLeft(new Coordinate(498,3,0,'')));
    }

    public function test_downToRight(): void
    {
        $cave = Cave::fromString(
            "498,4 -> 498,6 -> 496,6\n503,4 -> 502,4 -> 502,9 -> 494,9\n",
            500, 0
        );

        $this->assertFalse($cave->downToRight(new Coordinate(497,3,0,'')));
        $this->assertTrue($cave->downToRight(new Coordinate(498,3,0,'')));
    }

    public function test_downIsAbyss(): void
    {
        $cave = Cave::fromString(
            "498,4 -> 498,6 -> 496,6\n503,4 -> 502,4 -> 502,9 -> 494,9\n",
            500, 0
        );

        $this->assertFalse($cave->downIsAbyss($cave->sandOrigin));
        $this->assertFalse($cave->downIsAbyss(new Coordinate(498,3,0,'')));
        $this->assertFalse($cave->downIsAbyss(new Coordinate(496,0,0,'')));
        $this->assertFalse($cave->downIsAbyss(new Coordinate(496,7,0,'')));
        $this->assertTrue($cave->downIsAbyss(new Coordinate(494,10,0,'')));
        $this->assertTrue($cave->downIsAbyss(new Coordinate(503,5,0,'')));
        $this->assertTrue($cave->downIsAbyss(new Coordinate(504,0,0,'')));
        $this->assertTrue($cave->downIsAbyss(new Coordinate(0,0,0,'')));
    }

    public function testExpellUnitsOfSandUntilAbyssFound(): void
    {
        $numberOfUnits = 0;
        $cave = Cave::fromString(
            "498,4 -> 498,6 -> 496,6\n503,4 -> 502,4 -> 502,9 -> 494,9\n",
            500, 0
        );
        $expected = new Coordinate(493, 9, Cell::Abyss->stiffness(), Cell::Abyss->value);
        $numberOfrocks = $cave->rocks->count();
        $numberOfRocksAndSand = $numberOfrocks + 24;
        $result = $cave->expellSand($numberOfUnits);

    
        $this->assertEquals($expected, $result);
        $this->assertEquals($numberOfRocksAndSand, $cave->map->count());
    }
}