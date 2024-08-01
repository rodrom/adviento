<?php

declare(strict_types=1);
namespace Rodrom\Advent202223\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202223\Difussion;

class SecondHalfTest extends TestCase
{
    protected Difussion $dif;
    protected array $proposed;
    protected array $queue;

    protected function setUp(): void
    {
        $this->dif = Difussion::readInput(file_get_contents(__DIR__ . "/../example.txt"));
        $this->proposed = $this->dif->firstHalf(Difussion::NORTH);
    }

    public function test_grow_map_queue(): void
    {
        $q = $this->dif->growingMapQueue($this->proposed);

        $this->assertCount(4, $q);
        // NORTH BORDER
        $this->assertEquals(4, $q[Difussion::NORTH][0], "NORTH-BORDER");
        // SOUTH BORDER
        $this->assertEquals(32 + 4, $q[Difussion::SOUTH][0], "SOUTH-BORDER");
        // WEST BORDER
        $this->assertEquals([0, 0, 0, 0, 64, 64, 0], $q[Difussion::WEST], "WEST-BORDER");
        // EAST BORDER
        $this->assertEquals([0, 0, 1, 0, 0, 0, 0], $q[Difussion::EAST], "EAST-BORDER");
    }

    public function test_internal_horizontal_moves(): void
    {
        $h = $this->dif->internalHorizontalMoves($this->proposed);

        $this->assertCount(7, $h);

        // 987|6543210|1
        // ...|....#..|. -1
        // ...|..#...#|. 0 = 1 + 16
        // ...|#..#.#.|. 1 = 4
        // ...|....#..|# 2 = 4
        // ...|.#.#.##|. 3 = 2 + 8 + 32
        // ..#|..#.#..|. 4 = 4 + 16 (0,3 -> S and 0,5 -> N collide at 0,4)
        // ..#|.#.#.##|. 5 = 32 + 8 + 2 + 1
        // ...|.......|. 6
        // ...|.#..#..|. 7 // NEXT FRONTIER
        $y = 0;
        $w = 0;
        $this->assertEquals(1 + 16, $h[$y][$w], "y=0");
        $this->assertEquals(64 + 8, $h[1][0], "y=1");
        $this->assertEquals(4, $h[2][0], "y=2");
        $this->assertEquals(32 + 8 + 2, $h[3][0], "y=3");
        $this->assertEquals(4 + 16, $h[4][0], "y=4");
        $this->assertEquals(32 + 8 + 2 + 1, $h[5][0], "y=5");
        $this->assertEquals(0, $h[6][0], "y=6");
    }

    public function test_internal_vertical_moves(): void
    {
        $expected = $this->dif->internalHorizontalMoves($this->proposed);
        // There is only one movements to the west from tile (1, 2) -> (2,2)
        // of the map.
        $expected[1][0] = 72 + 2; // 101010
        $v = $this->dif->internalVerticalMoves($expected, $this->proposed);
        $this->assertEquals($expected, $v);
    }

    public function test_update_elves(): void
    {
        $p = $this->proposed;
        $dif = $this->dif;

        $h = $dif->internalHorizontalMoves($p);
        $v = $dif->internalVerticalMoves($h, $p);
        //  6543210
        // |..#...#| 0
        // |#..#.#.| 1
        // |....#..| 2
        // |.#.#.##| 3
        // |..#.#..| 4
        // |.#.#.##| 5
        // |.......| 6
        $dif->updateElvesPosition($v, $p);
        $expected = [
            0 => [0 => 1 + 16],
            1 => [0 => 2 + 8 + 64],
            2 => [0 => 4],
            3 => [0 => 1 + 2 + 8 + 32],
            4 => [0 => 4 + 16],
            5 => [0 => 1 + 2 + 8 + 32],
            6 => [0 => 0],
        ];
        $this->assertEquals($expected, $dif->horizontal);
    }

    public function test_update_borders(): void
    {
        $p = $this->proposed;
        $dif = $this->dif;
        $q = $this->dif->growingMapQueue($this->proposed);

        $h = $dif->internalHorizontalMoves($p);
        $v = $dif->internalVerticalMoves($h, $p);
        $dif->updateElvesPosition($v, $p);

        $dif->updateBorders($q);

        //  876543210
        //  .....#... 0
        //  ...#...#. 1
        //  .#..#.#.. 2
        //  .....#..# 3
        //  ..#.#.##. 4
        //  #..#.#... 5
        //  #.#.#.##. 6
        //  ......... 7
        //  ..#..#... 8
        $this->assertEquals(9, $dif->dimensionX, "dimensionX");
        $this->assertEquals(9, $dif->dimensionY, "dimensionY");

        $expected = [
            0 => [0 => 8],
            1 => [0 => 2 + 32],
            2 => [0 => 4 + 16 + 128],
            3 => [0 => 1 + 8],
            4 => [0 => 2 + 4 + 16 + 64],
            5 => [0 => 8 + 32 + 256],
            6 => [0 => 2 + 4 + 16 + 64 + 256],
            7 => [0 => 0],
            8 => [0 => 8 + 64]
        ];
        $this->assertEquals($expected, $dif->horizontal, "horizontal array");
    }

    public function test_empty_tiles(): void
    {
        $dif = Difussion::readInput("#.....\n#.....\n");
        $expected = 10;

        $this->assertEquals($expected, $dif->emptyTiles());
    }

    public function test_complete_second_half(): void
    {
        // 
        // .....#... 9 - 1 = 8
        // ...#...#. 9 - 2 = 7
        // .#..#.#.. 9 - 3 = 6
        // .....#..# 9 - 2 = 7
        // ..#.#.##. 9 - 4 = 5
        // #..#.#... 9 - 3 = 6
        // #.#.#.##. 9 - 5 = 4
        // ......... 9 - 0 = 9
        // ..#..#... 9 - 2 = 7
        $expected = 9 + 8 + 7 * 3 + 6 * 2 + 5 + 4;

        $this->assertEquals($expected, $this->dif->secondHalf($this->proposed));
    }
}