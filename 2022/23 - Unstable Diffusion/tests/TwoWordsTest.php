<?php

declare(strict_types=1);
namespace Rodrom\Advent202223\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202223\Difussion;

class TwoWordsTest extends TestCase
{
    protected string $input;
    protected Difussion $difu;
    protected function setUp(): void
    {
        $input = <<<EOD
        #...............#...............#
        #...............#...............#
        EOD;
        
        $this->difu = Difussion::readInput($input);
    }

    public function test_simpleInput2Words(): void
    {

        $this->assertInstanceOf(Difussion::class, $this->difu);
        $this->assertEquals(33, $this->difu->dimensionX, "dimensionX");
        $this->assertEquals(2, $this->difu->dimensionY, "dimensionY");
        $this->assertCount(33, $this->difu->vertical, "vertical");
        $this->assertCount(2, $this->difu->horizontal, "horizontal");
        $this->assertContainsOnly('array', $this->difu->horizontal);

        $this->assertCount(2, $this->difu->horizontal[0]);
        $this->assertCount(2, $this->difu->horizontal[1]);
        $this->assertEquals(1 + 2**16, $this->difu->horizontal[0][0]);
        $this->assertEquals(1 + 2**16, $this->difu->horizontal[1][0]);
        $this->assertEquals(1, $this->difu->horizontal[0][1]);
        $this->assertEquals(1, $this->difu->horizontal[1][1]);
    }

    public function test_AdjacentNeighborghs2WordsNorth(): void
    {
        $d = $this->difu;
        $this->assertEquals(0, $d->adjNorth(0,0), "0,0 => 0");
        $this->assertEquals(1, $d->adjNorth(0, 1), "0,1 => 1");
        $this->assertEquals(0, $d->adjNorth(16, 0), "16,0 => 0");
        $this->assertEquals(2 ** 16, $d->adjNorth(16, 1), "16,1 => 2**16");
        $this->assertEquals(0, $d->adjNorth(32, 0), "32,0 => 0");
        $this->assertEquals(1, $d->adjNorth(32, 1), "32,1 => 1");
    }

    public function test_AdjacentNeighborghs2WordsSouth(): void
    {
        $d = $this->difu;
        $this->assertEquals(1, $d->adjSouth(0,0), "0,0 => 1");
        $this->assertEquals(0, $d->adjSouth(0, 1), "0,1 => 0");
        $this->assertEquals(2 ** 16, $d->adjSouth(16, 0), "16,0 => 2**16");
        $this->assertEquals(0, $d->adjSouth(16, 1), "16,1 => 0");
        $this->assertEquals(1, $d->adjSouth(32, 0), "32,0 => 1");
        $this->assertEquals(0, $d->adjSouth(32, 1), "32,1 => 0");
    }

    public function test_AdjacentNeighborghs2WordsEast(): void
    {
        $map = <<<EOD
        #...............#...............#
        ##.............##..............#.
        EOD;
        
        $d = Difussion::readInput($map);

        $this->assertEquals(0, $d->adjEast(0,0), "0,0 => 0");
        $this->assertEquals(1, $d->adjEast(1, 1), "1,1 => 1");
        $this->assertEquals(0, $d->adjEast(16, 0), "16,0 => 0");
        $this->assertEquals(0, $d->adjEast(16, 1), "16,1 => 0");
        $this->assertEquals(2 ** 16, $d->adjEast(17, 1), "17,1 => 2 ** 16");
        $this->assertEquals(2 ** 31, $d->adjEast(32, 0), "32,0 => 2 ** 31");
        $this->assertEquals(0, $d->adjEast(31, 1), "31,1 => 0");
        $this->assertEquals(2 ** 31, $d->adjEast(32, 1), "32,1 => 2 ** 31");
    }

    public function test_AdjacentNeighborghs2WordsWest(): void
    {
        $map = <<<EOD
        #...............#...............#
        ##.............##..............#.
        EOD;
        
        $d = Difussion::readInput($map);

        $this->assertEquals(2, $d->adjWest(0,0), "0,0 => 2");
        $this->assertEquals(0, $d->adjWest(1, 1), "1,1 => 0");
        $this->assertEquals(2 ** 17, $d->adjWest(16, 0), "16,0 => 2 ** 17");
        $this->assertEquals(2 ** 17, $d->adjWest(16, 1), "16,1 => 2 ** 17");
        $this->assertEquals(0, $d->adjWest(17, 1), "17,1 => 0");
        $this->assertEquals(0, $d->adjWest(32, 0), "32,0 => 0");
        $this->assertEquals(1, $d->adjWest(31, 1), "31,1 => 1");
        $this->assertEquals(0, $d->adjWest(32, 1), "32,1 => 0");
    }

    public function test_adjacent_empty_two_words(): void
    {
        $input = <<<EOD
        #...............#...............#
        .................................
        .#...............................
        .................................
        #.#..............................
        EOD;

        $d = Difussion::readInput($input);
        
        $this->assertTrue($d->adjEmpty(32,0));
        $this->assertTrue($d->adjEmpty(16,0));
        $this->assertTrue($d->adjEmpty(0,0));
        $this->assertTrue($d->adjEmpty(31,2));
        $this->assertTrue($d->adjEmpty(32,4));
        $this->assertTrue($d->adjEmpty(30,4));
    }

    public function test_adjacent_non_empty_two_words(): void
    {
        $input = <<<EOD
        #...............#...............#
        .#...............................
        .................................
        ##...............................
        .................................
        #................................
        EOD;

        $d = Difussion::readInput($input);
        
        $this->assertFalse($d->adjEmpty(32,0));
        $this->assertTrue($d->adjEmpty(16,0));
        $this->assertTrue($d->adjEmpty(0,0));
        $this->assertFalse($d->adjEmpty(32,2));
        $this->assertFalse($d->adjEmpty(31,2));
        $this->assertTrue($d->adjEmpty(32,5));
    }

    public function test_isolated_first_half_proposed_two_words(): void
    {
        $input = <<<EOD
        #...............#...............#
        .................................
        .#...............................
        .................................
        #.#..............................
        EOD;

        $d = Difussion::readInput($input);
        $p = $d->firstHalf(Difussion::NORTH);

        $expected = [
            // y
            0 => [
                // w 
                0 => [ 'isolated' => 1 + 2 ** 16],
                1 => [ 'isolated' => 1 ],
            ],
            1 => [ 
                0 => [ 'isolated' => 0],
                1 => [ 'isolated' => 0 ],
            ],
            2 => [
                0 => [ 'isolated' => 2 ** 31],
                1 => [ 'isolated' => 0 ]
            ],
            3 => [ 
                0 => [ 'isolated' => 0 ],
                1 => [ 'isolated' => 0],
            ],
            4 => [
                0 => [ 'isolated' => 2 ** 30 ],
                1 => [ 'isolated' => 1 ]
            ]
        ];

        foreach ($expected as $y => $ex) {
            $this->assertEquals($ex[0]['isolated'], $p[$y][0]['isolated']);
            $this->assertEquals($ex[1]['isolated'], $p[$y][1]['isolated']);    
        }
    }

    public function test_two_words_collision_horizontal(): void
    {
        $input = <<<EOD
        #####............#...............#
        ##.##.............................
        ##.##.............................
        ##.##.............................
        #.###.............................
        #.###.............................
        #.###.............................
        EOD;

        $d = Difussion::readInput($input);
        $this->assertEquals(34, $d->dimensionX, "dimensionX");
        $this->assertEquals(7, $d->dimensionY, "dimensionY");
        $p = $d->firstHalf(Difussion::WEST);

        // Y = 0
        // W = 0
        $this->assertEquals(2 ** 31 + 2 ** 30, $p[0][0][Difussion::NORTH]);
        $this->assertEquals(0, $p[0][0][Difussion::SOUTH]);
        $this->assertEquals(0, $p[0][0][Difussion::WEST]);
        $this->assertEquals(2 ** 29, $p[0][0][Difussion::EAST]);
        $this->assertEquals(1 + 2 ** 16, $p[0][0]['isolated']);
        // W = 1
        $this->assertEquals(1, $p[0][1][Difussion::NORTH]);
        $this->assertEquals(0, $p[0][1][Difussion::SOUTH]);
        $this->assertEquals(2, $p[0][1][Difussion::WEST]);
        $this->assertEquals(0, $p[0][1][Difussion::EAST]);
        $this->assertEquals(0, $p[0][1]['isolated']);
        // Y = 1
        // W = 0
        $this->assertEquals(0, $p[1][0][Difussion::NORTH]);
        $this->assertEquals(0, $p[1][0][Difussion::SOUTH]);
        $this->assertEquals(0, $p[1][0][Difussion::WEST]);
        $this->assertEquals(2 ** 29, $p[1][0][Difussion::EAST]);
        $this->assertEquals(2 ** 30, $p[1][0]['isolated']);
        // W = 1
        $this->assertEquals(0, $p[1][1][Difussion::NORTH]);
        $this->assertEquals(0, $p[1][1][Difussion::SOUTH]);
        $this->assertEquals(2, $p[1][1][Difussion::WEST]);
        $this->assertEquals(0, $p[1][1][Difussion::EAST]);
        $this->assertEquals(1, $p[1][1]['isolated']);
        // Y = 2
        // W = 0
        $this->assertEquals(0, $p[2][0][Difussion::NORTH]);
        $this->assertEquals(0, $p[2][0][Difussion::SOUTH]);
        $this->assertEquals(2 ** 30, $p[2][0][Difussion::WEST]);
        $this->assertEquals(2 ** 29, $p[2][0][Difussion::EAST]);
        $this->assertEquals(0, $p[2][0]['isolated']);
        // W = 1
        $this->assertEquals(0, $p[2][1][Difussion::NORTH]);
        $this->assertEquals(0, $p[2][1][Difussion::SOUTH]);
        $this->assertEquals(2, $p[2][1][Difussion::WEST]);
        $this->assertEquals(1, $p[2][1][Difussion::EAST]);
        $this->assertEquals(0, $p[2][1]['isolated']);
        // Y = 3
        // W = 0
        $this->assertEquals(0, $p[3][0][Difussion::NORTH]);
        $this->assertEquals(0, $p[3][0][Difussion::SOUTH]);
        $this->assertEquals(0, $p[3][0][Difussion::WEST]);
        $this->assertEquals(2 ** 29, $p[3][0][Difussion::EAST]);
        $this->assertEquals(2 ** 30, $p[3][0]['isolated']);
        // W = 1
        $this->assertEquals(0, $p[3][1][Difussion::NORTH]);
        $this->assertEquals(0, $p[3][1][Difussion::SOUTH]);
        $this->assertEquals(2, $p[3][1][Difussion::WEST]);
        $this->assertEquals(0, $p[3][1][Difussion::EAST]);
        $this->assertEquals(1, $p[3][1]['isolated']);

        // Y = 4
        // W = 0
        $this->assertEquals(0, $p[4][0][Difussion::NORTH]);
        $this->assertEquals(0, $p[4][0][Difussion::SOUTH]);
        $this->assertEquals(0, $p[4][0][Difussion::WEST]);
        $this->assertEquals(2 ** 29, $p[4][0][Difussion::EAST]);
        $this->assertEquals(2 ** 30 + 2 ** 31, $p[4][0]['isolated']);
        // W = 1
        $this->assertEquals(0, $p[4][1][Difussion::NORTH]);
        $this->assertEquals(0, $p[4][1][Difussion::SOUTH]);
        $this->assertEquals(2, $p[4][1][Difussion::WEST]);
        $this->assertEquals(0, $p[4][1][Difussion::EAST]);
        $this->assertEquals(0, $p[4][1]['isolated']);

        // Y = 5
        // W = 0
        $this->assertEquals(0, $p[5][0][Difussion::NORTH]);
        $this->assertEquals(0, $p[5][0][Difussion::SOUTH]);
        $this->assertEquals(2 ** 31, $p[5][0][Difussion::WEST]);
        $this->assertEquals(2 ** 29, $p[5][0][Difussion::EAST]);
        $this->assertEquals(2 ** 30, $p[5][0]['isolated']);
        // W = 1
        $this->assertEquals(0, $p[5][1][Difussion::NORTH]);
        $this->assertEquals(0, $p[5][1][Difussion::SOUTH]);
        $this->assertEquals(2, $p[5][1][Difussion::WEST]);
        $this->assertEquals(0, $p[5][1][Difussion::EAST]);
        $this->assertEquals(0, $p[5][1]['isolated']);

        // Y = 6
        // W = 0
        $this->assertEquals(0, $p[6][0][Difussion::NORTH]);
        $this->assertEquals(2 ** 30, $p[6][0][Difussion::SOUTH]);
        $this->assertEquals(2 ** 31, $p[6][0][Difussion::WEST]);
        $this->assertEquals(2 ** 29, $p[6][0][Difussion::EAST]);
        $this->assertEquals(0, $p[6][0]['isolated']);
        // W = 1
        $this->assertEquals(0, $p[6][1][Difussion::NORTH]);
        $this->assertEquals(0, $p[6][1][Difussion::SOUTH]);
        $this->assertEquals(2, $p[6][1][Difussion::WEST]);
        $this->assertEquals(0, $p[6][1][Difussion::EAST]);
        $this->assertEquals(0, $p[6][1]['isolated']);

        // Queue
        $q = $d->growingMapQueue($p);
        $expected = [ 0 => 2 ** 30 + 2 ** 31, 1 => 1 ];
        $this->assertEquals($expected, $q[Difussion::NORTH]);
        
        $expected = [ 0 => 2 ** 30, 1 => 0 ];
        $this->assertEquals($expected, $q[Difussion::SOUTH]);

        $expected = array_fill(0, $d->dimensionY, 0);
        $this->assertEquals($expected, $q[Difussion::EAST]);

        $expected = array_fill(0, $d->dimensionY, 2);
        $this->assertEquals($expected, $q[Difussion::WEST]);

        // Internal Horizontal moves
        $h = $d->internalHorizontalMoves($p);
        $expected = [
            0 => [ 0 => 1 + 2 ** 16, 1 => 0 ],
            1 => [ 0 => 2 ** 30,     1 => 1 ],
            2 => [ 0 => 0,           1 => 0 ], // Checking NORT SOUTH COLLISIONS - NOT EAST - WEST
            3 => [ 0 => 2 ** 30,     1 => 1 ],
            4 => [ 0 => 2 ** 30 + 2 ** 31, 1 => 0 ],
            5 => [ 0 => 2 ** 30, 1 => 0 ],
            6 => [ 0 => 0, 1 => 0 ],
        ];

        $this->assertEquals($expected, $h);

        // Internal vertical moves
        $v = $d->internalVerticalMoves($h, $p);

        $expected = [
            0 => [ 0 => 1 + 2 ** 16 + 2 ** 28, 1 => 0],
            1 => [ 0 => 2 ** 28 + 2 ** 30, 1 => 1],
            2 => [ 0 => 2 ** 28 + 2 ** 30, 1 => 1],
            3 => [ 0 => 2 ** 28 + 2 ** 30, 1 => 1],
            4 => [ 0 => 2 ** 28 + 2 ** 30 + 2 ** 31, 1 => 0],
            5 => [ 0 => 2 ** 28 + 2 ** 30, 1 => 1],
            6 => [ 0 => 2 ** 28, 1 => 1],
        ];
        $this->assertEquals($expected, $v);

        // Update elves positions
        $d->updateElvesPosition($v, $p);
        $expected = [
            0 => [ 0 => 1 + 2 ** 16 + 2 ** 28, 1 => 0],
            1 => [ 0 => 2 ** 28 + 2 ** 30, 1 => 1],
            2 => [ 0 => 2 ** 28 + 2 ** 30, 1 => 1],
            3 => [ 0 => 2 ** 28 + 2 ** 30, 1 => 1],
            4 => [ 0 => 2 ** 28 + 2 ** 30 + 2 ** 31, 1 => 0],
            5 => [ 0 => 2 ** 28 + 2 ** 30, 1 => 1],
            6 => [ 0 => 2 ** 28, 1 => 1],
        ];

        $this->assertEquals($expected, $d->horizontal);

        // Update borders
        $d->updateBorders($q);
        $this->assertEquals(35, $d->dimensionX);
        $this->assertEquals(9, $d->dimensionY);

        $expected = [
            0 => [ 0 => 2 ** 30 + 2 ** 31, 1 => 1],
            1 => [ 0 => 1 + 2 ** 16 + 2 ** 28, 1 => 4],
            2 => [ 0 => 2 ** 28 + 2 ** 30, 1 => 1 + 4],
            3 => [ 0 => 2 ** 28 + 2 ** 30, 1 => 1 + 4],
            4 => [ 0 => 2 ** 28 + 2 ** 30, 1 => 1 + 4],
            5 => [ 0 => 2 ** 28 + 2 ** 30 + 2 ** 31, 1 => 4],
            6 => [ 0 => 2 ** 28 + 2 ** 30, 1 => 1 + 4],
            7 => [ 0 => 2 ** 28, 1 => 1 + 4],
            8 => [ 0 => 2 ** 30, 1 => 0],
        ];

        $this->assertEquals($expected, $d->horizontal);

        // Empty Tiles
        $this->assertEquals(35 * 9 - 31, $d->emptyTiles());

        // $expected = <<<EOD
        // ..###..............................
        // #.....#...........#...............#
        // #.#.#.#............................
        // #.#.#.#............................
        // #.#.#.#............................
        // #..##.#............................
        // #.#.#.#............................
        // #.#...#............................
        // ....#..............................
        // EOD;
        // $emptyTiles = $d->secondHalf($p);
        // // $this->assertEquals(35 * 9 - 31, $emptyTiles);
        // // $this->assertEquals($expected, strval($d));
    }
    
    public function test_second_half(): void
    {
        $input = <<<EOD
        #####............#...............#
        ##.##.............................
        ##.##.............................
        ##.##.............................
        #.###.............................
        #.###.............................
        #.###.............................
        EOD;

        $d = Difussion::readInput($input);
        $emptyTiles = $d->play(10);
        $this->assertEquals($d->dimensionX * $d->dimensionY - 31, $emptyTiles);
    }

    public function test_right_and_left(): void
    {
        $input = <<<EOD
        #####............#...............#
        ##.##.............................
        ##.##.............................
        ##.##.............................
        #.###............................#
        #.###..........................###
        #.###............................#
        EOD;

        $d = Difussion::readInput($input);
        $emptyTiles = $d->play(1);
        $this->assertEquals(36, $d->dimensionX);
        $this->assertEquals(9, $d->dimensionY);
        $this->assertEquals($d->dimensionX * $d->dimensionY - 36, $emptyTiles);
    }
}
