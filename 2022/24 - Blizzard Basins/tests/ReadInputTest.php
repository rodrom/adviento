<?php

declare(strict_types=1);
namespace Rodrom\Advent202224\Test;
use PHPUnit\Framework\TestCase;
use Rodrom\Advent202224\Board;

class ReadInputTest extends TestCase
{
    public function test_read_input_example(): void
    {
        $b = Board::readInput(file_get_contents(__DIR__ . "/../example.txt"));
        $this->assertEquals(6, $b->dimX);
        $this->assertEquals(4, $b->dimY);
        $expected = [
            Board::N => [
                0 => [],
                1 => [3],
                2 => [],
                3 => [3],
                4 => [0, 3],
                5 => [],
            ],
            Board::S => [
                0 => [],
                1 => [2],
                2 => [3],
                3 => [],
                4 => [],
                5 => []
            ],
            Board::W => [
                0 => [3, 5],
                1 => [1, 4, 5],
                2 => [4],
                3 => [0],
            ],
            Board::E => [
                0 => [0, 1],
                1 => [],
                2 => [0, 3, 5],
                3 => [5],
            ],
        ];
        $this->assertEquals($expected[Board::N], $b->blizzards[Board::N]);
        $this->assertEquals($expected[Board::S], $b->blizzards[Board::S]);
        $this->assertEquals($expected[Board::W], $b->blizzards[Board::W]);
        $this->assertEquals($expected[Board::E], $b->blizzards[Board::E]);

        $expected = [
            0 => [ 1 => true],
            2 => [ 0 => true, 1 => true, 2 => true],
            3 => [ 1 => true],
        ];

        $this->assertEquals($expected, $b->emptyTiles);
    }
}