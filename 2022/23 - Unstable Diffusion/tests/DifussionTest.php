<?php

declare(strict_types=1);
namespace Rodrom\Advent202223\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202223\Difussion;

class DifussionTest extends TestCase
{
    protected string $input;
    protected Difussion $difu;
    protected function setUp(): void
    {
        $input = <<<EOD
        .......#
        ..#.....
        .....#..
        #....##.
        ###...#.
        .#......
        ....#...
        ...#....
        EOD;
        
        $this->difu = Difussion::readInput($input);
    }

    public function test_readRealInput(): void
    {
        $input = file_get_contents(__DIR__ . '/../input.txt');
        $this->difu = Difussion::readInput($input);
        $this->assertInstanceOf(Difussion::class, $this->difu);
        $this->assertEquals(74, $this->difu->dimensionX);
        $this->assertEquals(74, $this->difu->dimensionY);
        $this->assertCount(74, $this->difu->map, "map");
        $this->assertCount(74, $this->difu->vertical, "vertical");
        $this->assertCount(74, $this->difu->horizontal, "horizontal");
        $this->assertContainsOnly('array', $this->difu->horizontal);
    }

    public function test_simpleInput(): void
    {

        $this->assertInstanceOf(Difussion::class, $this->difu);
        $this->assertEquals(8, $this->difu->dimensionX);
        $this->assertEquals(8, $this->difu->dimensionY);
        $this->assertCount(8, $this->difu->map, "map");
        $this->assertCount(8, $this->difu->vertical, "vertical");
        $this->assertCount(8, $this->difu->horizontal, "horizontal");
        $this->assertContainsOnly('array', $this->difu->horizontal);
    }

    public function test_AdjacentNeighborghsNorthNone(): void
    {
        $d = $this->difu;
        $this->assertEquals(0, $d->adjNorth(0,0), "0,0 => 0");
        $this->assertEquals(0, $d->adjNorth(5, 1), "5,1 => 0");
        $this->assertEquals(0, $d->adjNorth(3, 2), "3,2 => 0");
        $this->assertEquals(0, $d->adjNorth(7, 3), "7,3 => 0");
        $this->assertEquals(0, $d->adjNorth(5, 4), "5,4 => 0");
    }

    public function test_AdjacentNeighborghsNorthYes(): void
    {
        $d = $this->difu;
        $this->assertEquals(4, $d->adjNorth(1, 3), "1,3 => 4");
        $this->assertEquals(4, $d->adjNorth(2, 3), "2,3 => 4");
        $this->assertEquals(128, $d->adjNorth(7, 4), "7,4 => 128");
        $this->assertEquals(128, $d->adjNorth(6, 4), "6,4 => 128");
        $this->assertEquals(128 + 64 + 32, $d->adjNorth(6, 5), "6,5 => 128 + 64 + 32 = 224");
        $this->assertEquals(8, $d->adjNorth(4, 7), "4,7 => 8");
    }

    public function test_AdjacentNeighborghsSouthNone(): void
    {
        $d = $this->difu;
        $this->assertEquals(0, $d->adjSouth(0, 0), "0,0 => 0");
        $this->assertEquals(0, $d->adjSouth(5, 1), "5,1 => 0");
        $this->assertEquals(0, $d->adjSouth(2, 4), "2,4 => 0");
        $this->assertEquals(0, $d->adjSouth(6, 5), "6,5 => 0");
        $this->assertEquals(0, $d->adjSouth(4, 7), "4,7 => 0");
    }

    public function test_AdjacentNeighborghsSouthYes(): void
    {
        $d = $this->difu;
        $this->assertEquals(6, $d->adjSouth(2, 2), "2,2 => 6");
        $this->assertEquals(2, $d->adjSouth(1, 3), "1,3 => 2");
        $this->assertEquals(2, $d->adjSouth(2, 3), "2,3 => 2");
        $this->assertEquals(128 + 64, $d->adjSouth(7, 3), "7,3 => 128 + 64 = 192");
        $this->assertEquals(64, $d->adjSouth(7, 4), "7,4 => 64");
        $this->assertEquals(64, $d->adjSouth(5, 4), "5,4 => 64");
        $this->assertEquals(16, $d->adjSouth(3, 6), "3,6 => 16");
    }

    public function test_AdjacentNeighborghsEastNone(): void
    {
        $d = $this->difu;
        $this->assertEquals(0, $d->adjEast(0, 0), "0,0 => 0");
        $this->assertEquals(0, $d->adjEast(5, 1), "5,1 => 0");
        $this->assertEquals(0, $d->adjEast(1, 3), "1,3 => 0");
        $this->assertEquals(0, $d->adjEast(1, 4), "1,4 => 0");
        $this->assertEquals(0, $d->adjEast(3, 6), "3,6 => 0");
    }

    public function test_AdjacentNeighborghsEastYes(): void
    {
        $d = $this->difu;
        $this->assertEquals(2, $d->adjEast(2, 2), "2,2 => 2");
        $this->assertEquals(2, $d->adjEast(2, 3), "2,3 => 2");
        $this->assertEquals(64, $d->adjEast(7, 3), "7,3 => 64");
        $this->assertEquals(32, $d->adjEast(6, 4), "6,4 => 32");
        $this->assertEquals(8, $d->adjEast(4, 7), "4,7 => 8");
    }

    public function test_AdjacentNeighborghsWestNone(): void
    {
        $d = $this->difu;
        $this->assertEquals(0, $d->adjWest(0, 0), "0,0 => 0");
        $this->assertEquals(0, $d->adjWest(5, 1), "5,1 => 0");
        $this->assertEquals(0, $d->adjWest(2, 2), "2,2 => 0");
        $this->assertEquals(0, $d->adjWest(2, 3), "2,3 => 0");
        $this->assertEquals(0, $d->adjWest(4, 7), "4,7 => 0");
    }

    public function test_AdjacentNeighborghsWestYes(): void
    {
        $d = $this->difu;
        $this->assertEquals(4, $d->adjWest(1, 3), "1,3 => 4");
        $this->assertEquals(4, $d->adjWest(1, 4), "1,4 => 4");
        $this->assertEquals(64, $d->adjWest(5, 4), "5,4 => 64");
        $this->assertEquals(128, $d->adjWest(6, 4), "6,4 => 128");
        $this->assertEquals(16, $d->adjWest(3, 6), "3,6 => 16");
    }

    public function testAdjacentEmpty(): void
    {
        $d = $this->difu;
        $this->assertTrue($d->adjEmpty(5,1));
        $this->assertTrue($d->adjEmpty(0,0));
        $this->assertFalse($d->adjEmpty(2,2));
    }

    public function test_isolated_first_half_proposed():void
    {
        $d = $this->difu;
        $p = $d->firstHalf(Difussion::NORTH);

        $this->assertEquals(1, $p[0][0]['isolated']);
        $this->assertEquals(32, $p[1][0]['isolated']);
        $this->assertEquals(0, $p[2][0]['isolated']);
        $this->assertEquals(0, $p[3][0]['isolated']);
        $this->assertEquals(64, $p[4][0]['isolated']);
        $this->assertEquals(0, $p[5][0]['isolated']);
        $this->assertEquals(0, $p[6][0]['isolated']);
        $this->assertEquals(0, $p[7][0]['isolated']);
    }

    public function test_collision_horizontal(): void
    {
        $map = <<<EOD
        #####
        ##.##
        ##.##
        ##.##
        #####
        EOD;
        $d = Difussion::readInput($map);
        $this->assertEquals(5, $d->dimensionX, "dimensionX");
        $this->assertEquals(5, $d->dimensionY, "dimensionY");
        $p = $d->firstHalf(Difussion::EAST);
        $this->assertEquals(9, $p[2][0][Difussion::EAST]);
        $this->assertEquals(18, $p[2][0][Difussion::WEST]);
        $expected = <<<EOD
        .####..
        ......#
        #.#.#.#
        #.#.#.#
        #.#.#.#
        ......#
        .####..
        EOD;
        $emptyTiles = $d->secondHalf($p);
        $this->assertEquals(49 - 22, $emptyTiles);
        $this->assertEquals($expected, strval($d));
    }
}