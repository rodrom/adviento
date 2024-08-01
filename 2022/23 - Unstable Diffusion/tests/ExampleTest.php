<?php

declare(strict_types=1);
namespace Rodrom\Advent202223\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202223\Difussion;

class ExampleTest extends TestCase
{
    protected Difussion $dif;

    protected function setUp(): void
    {
        $this->dif = Difussion::readInput(file_get_contents(__DIR__ . "/../example.txt"));
    }

    public function test_example_round1(): void
    {
        $d = $this->dif;

        $emptyTiles = $d->play(1);
        $expectedMap = <<<EOD
        .....#...
        ...#...#.
        .#..#.#..
        .....#..#
        ..#.#.##.
        #..#.#...
        #.#.#.##.
        .........
        ..#..#...
        EOD;
        $this->assertEquals($expectedMap, $d->__toString());
        $this->assertEquals(59, $emptyTiles);
    }

    public function test_example_round2(): void
    {
        $d = $this->dif;

        $emptyTiles = $d->play(2);
        $expected = <<<EOD
        ......#....
        ...#.....#.
        ..#..#.#...
        ......#...#
        ..#..#.#...
        #...#.#.#..
        ...........
        .#.#.#.##..
        ...#..#....
        EOD;
        $this->assertEquals($expected, $d->__toString());
        $this->assertEquals(10 + 9 + 8 + 9 + 8 + 7 + 11 + 6 + 9, $emptyTiles);
    }

    public function test_example_round3(): void
    {
        $d = $this->dif;

        $emptyTiles = $d->play(3);
        $expected = <<<EOD
        ......#....
        ....#....#.
        .#..#...#..
        ......#...#
        ..#..#.#...
        #..#.....#.
        ......##...
        .##.#....#.
        ..#........
        ......#....
        EOD;
        $this->assertEquals($expected, $d->__toString());
        $this->assertEquals(10 + 9 + 8 + 9 + 8 + 8 + 9 + 7 + 10 + 10, $emptyTiles);
    }

    public function test_example_round4(): void
    {
        $d = $this->dif;

        $emptyTiles = $d->play(4);
        $expected = <<<EOD
        ......#....
        .....#....#
        .#...##....
        ..#.....#.#
        ........#..
        #...###..#.
        .#......#..
        ...##....#.
        ...#.......
        ......#....
        EOD;
        $this->assertEquals($expected, $d->__toString());
        $this->assertEquals(10 + 9 + 8 + 8 + 10 + 6 + 9 + 8 + 10 + 10, $emptyTiles);
    }

    public function test_example_round5(): void
    {
        $d = $this->dif;

        $emptyTiles = $d->play(5);
        $expected = <<<EOD
        ......#....
        ...........
        .#..#.....#
        ........#..
        .....##...#
        #.#.####...
        ..........#
        ...##..#...
        .#.........
        .........#.
        ...#..#....
        EOD;
        $this->assertEquals($expected, $d->__toString());
        $this->assertEquals(10 + 11 + 8 + 10 + 8 + 5 + 10 + 8 + 10 + 10 + 9, $emptyTiles);
    }

    public function test_example_round6(): void
    {
        $d = $this->dif;

        $emptyTiles = $d->play(6);
        $expected = <<<EOD
        ......#....
        ...........
        .#..#.....#
        .....##.#..
        ..........#
        #.#........
        ....####..#
        .......#...
        .#.##......
        .........#.
        ...#..#....
        EOD;
        $this->assertEquals(11, $d->dimensionX);
        $this->assertEquals(11, $d->dimensionY);
        $this->assertEquals($expected, $d->__toString());
        $this->assertEquals(11*11 - 22, $emptyTiles);
    }

    public function test_example_round7(): void
    {
        $d = $this->dif;

        $emptyTiles = $d->play(7);
        $expected = <<<EOD
        ......#....
        ...........
        .#.#......#
        .......##..
        .....#....#
        #.#..##....
        ...#....#.#
        ........#..
        .##..#.....
        .........#.
        ...#..#....
        EOD;
        $this->assertEquals(11, $d->dimensionX);
        $this->assertEquals(11, $d->dimensionY);
        $this->assertEquals($expected, $d->__toString());
        $this->assertEquals(11*11 - 22, $emptyTiles);
    }

    public function test_example_round8(): void
    {
        $d = $this->dif;

        $emptyTiles = $d->play(8);
        $expected = <<<EOD
        ......#....
        ...........
        .#.#...#..#
        .....#...#.
        ..#.......#
        #......#...
        ....##...##
        .#.......#.
        ...#.#.....
        .........#.
        ...#..#....
        EOD;
        $this->assertEquals(11, $d->dimensionX);
        $this->assertEquals(11, $d->dimensionY);
        $this->assertEquals($expected, $d->__toString());
        $this->assertEquals(11*11 - 22, $emptyTiles);
    }

    public function test_example_round9(): void
    {
        $d = $this->dif;

        $emptyTiles = $d->play(9);
        $expected = <<<EOD
        ......#....
        ..........#
        .#.#...#...
        .....#..#..
        ..#.......#
        #...##.#.#.
        ..........#
        .#.........
        ...#.#...#.
        .........#.
        ...#..#....
        EOD;
        $this->assertEquals(11, $d->dimensionX);
        $this->assertEquals(11, $d->dimensionY);
        $this->assertEquals($expected, $d->__toString());
        $this->assertEquals(11*11 - 22, $emptyTiles);
    }

    public function test_example_round10(): void
    {
        $d = $this->dif;

        $emptyTiles = $d->play(10);
        $expected = <<<EOD
        ......#.....
        ..........#.
        .#.#..#.....
        .....#......
        ..#.....#..#
        #......##...
        ....##......
        .#........#.
        ...#.#..#...
        ............
        ...#..#..#..
        EOD;
        $this->assertEquals(12, $d->dimensionX);
        $this->assertEquals(11, $d->dimensionY);
        $this->assertEquals($expected, strval($d));
        $this->assertEquals(11*12 - 22, $emptyTiles);
    }
}
