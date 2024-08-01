<?php

declare(strict_types=1);
namespace Rodrom\Advent202223\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202223\Difussion;

class ProposeMovesTest extends TestCase
{
    protected Difussion $dif;
    protected array $proposed;

    protected function setUp(): void
    {
        $this->dif = Difussion::readInput(file_get_contents(__DIR__ . "/../example.txt"));
        $this->proposed = $this->dif->firstHalf(Difussion::NORTH);
    }

    public function test_first_half_propose_array_result(): void
    {
        $p = $this->proposed;
    
        $this->assertIsArray($p);
        $this->assertContainsOnly('array', $p);
    }

    public function test_north_proposals(): void
    {
        $p = $this->proposed;
        $y = 0;
        $w = 0;
        $d = Difussion::NORTH;
        // ....(#)..
        $this->assertEquals(4, $p[$y][$w][$d]);
        // ..(#)##.(#)
        $this->assertEquals(1 + 16, $p[1][$w][$d]);
        // (#)...#.#
        $this->assertEquals(64, $p[2][$w][$d]);
        // .#...##
        $this->assertEquals(0, $p[3][$w][$d]);
        // #.#(#)#..
        $this->assertEquals(8, $p[4][$w][$d]);
        // ##.#.#(#)
        $this->assertEquals(1, $p[5][$w][$d]);
        // .#..#..
        $this->assertEquals(0, $p[6][$w][$d]);
    }

    public function test_south_proposals(): void
    {
        $d = Difussion::SOUTH;
        $p = $this->dif->firstHalf($d);
        $y = 0;
        $w = 0;
        
        // ....#..
        $this->assertEquals(0, $p[$y][$w][$d]);
        // ..(#)##.#
        $this->assertEquals(16, $p[1][$w][$d]);
        // #...#.#
        $this->assertEquals(0, $p[2][$w][$d]);
        // .#...#(#)
        $this->assertEquals(1, $p[3][$w][$d]);
        // #.###..
        $this->assertEquals(0, $p[4][$w][$d]);
        // ##.#.#(#)
        $this->assertEquals(1, $p[5][$w][$d]);
        // .(#)..(#)..
        $this->assertEquals(4 + 32, $p[6][$w][$d]);
    }

    public function test_west_proposals(): void
    {
        $d = Difussion::WEST;
        $p = $this->dif->firstHalf($d);
        $y = 0;
        $w = 0;

        // ....#..
        $this->assertEquals(0, $p[$y][$w][$d]);
        // ..(#)##.(#)
        $this->assertEquals(1 + 16, $p[1][$w][$d]);
        // (#)...#.#
        $this->assertEquals(64, $p[2][$w][$d]);
        // .#...##
        $this->assertEquals(0, $p[3][$w][$d]);
        // (#).###..
        $this->assertEquals(64, $p[4][$w][$d]);
        // (#)#.#.##
        $this->assertEquals(64, $p[5][$w][$d]);
        // .#..#..
        $this->assertEquals(0, $p[6][$w][$d]);
    }

    public function test_east_proposals(): void
    {
        $d = Difussion::EAST;
        $p = $this->dif->firstHalf($d);
        $y = 0;
        $w = 0;

        // ....(#)..
        $this->assertEquals(4, $p[$y][$w][$d]);
        // ..##(#).(#)
        $this->assertEquals(1 + 4, $p[1][$w][$d]);
        // #...#.(#)
        $this->assertEquals(1, $p[2][$w][$d]);
        // .#...#(#)
        $this->assertEquals(1, $p[3][$w][$d]);
        // #.###..
        $this->assertEquals(0, $p[4][$w][$d]);
        // ##.#.#(#)
        $this->assertEquals(1, $p[5][$w][$d]);
        // .(#)..#..
        $this->assertEquals(32, $p[6][$w][$d]);
    }

    public function test_example_first_half_first_round(): void
    {
        $p = $this->proposed;
        $y = 0;
        $w = 0;

        // ....N..
        $expected = [
            'isolated' => 0,
            Difussion::NORTH => 4,
            Difussion::SOUTH => 0,
            Difussion::WEST => 0,
            Difussion::EAST => 0
        ];

        $this->assertEquals($expected, $p[$y][$w], "y=0");

        // ..N#E.N (y = 1)
        $expected = [
            'isolated' => 8,
            Difussion::NORTH => 1 + 16,
            Difussion::SOUTH => 0,
            Difussion::WEST => 0,
            Difussion::EAST => 4
        ];

        $this->assertEquals($expected, $p[1][$w], "y=1");

        // N...#.E
        $expected = [
            'isolated' => 4,
            Difussion::NORTH => 64,
            Difussion::SOUTH => 0,
            Difussion::WEST => 0,
            Difussion::EAST => 1
        ];
        $this->assertEquals($expected, $p[2][$w], "y=2");

        // .#...#E
        $expected = [
            'isolated' => 2 + 32,
            Difussion::NORTH => 0,
            Difussion::SOUTH => 1,
            Difussion::WEST => 0,
            Difussion::EAST => 0
        ];
        $this->assertEquals($expected, $p[3][$w],"y=3");

        // W.#N#.. y = 4
        $expected = [
            'isolated' => 4 + 16,
            Difussion::NORTH => 8,
            Difussion::SOUTH => 0,
            Difussion::WEST => 64,
            Difussion::EAST => 0
        ];
        $this->assertEquals($expected, $p[4][$w],"y=4");

        // W#.#.#N y = 5
        $expected = [
            'isolated' => 32 + 8 + 2,
            Difussion::NORTH => 1,
            Difussion::SOUTH => 0,
            Difussion::WEST => 64,
            Difussion::EAST => 0
        ];
        $this->assertEquals($expected, $p[5][$w],"y=5");

        // .S..S.. y = 6
        $expected = [
            'isolated' => 0,
            Difussion::NORTH => 0,
            Difussion::SOUTH => 32 + 4,
            Difussion::WEST => 0,
            Difussion::EAST => 0
        ];
        $this->assertEquals($expected, $p[6][$w],"y=5");
    }
}