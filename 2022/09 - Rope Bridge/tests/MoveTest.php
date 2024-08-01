<?php

declare(strict_types=1);
namespace Rodrom\Advent202209\Tests;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202209\Direction;
use Rodrom\Advent202209\Move;

class MoveTest extends TestCase
{
    public function testCreateMove(): void
    {
        $move = new Move(Direction::Up, 3);
        $this->assertInstanceOf (Move::class, $move);
    }

    public function testCreateMoveFromString(): void
    {
        $expected = new Move(Direction::Right, 4);
        $move = Move::fromString("R 4");

        $this->assertInstanceOf(Move::class, $move);
        $this->assertEquals($expected, $move);

    }

    public function testToString(): void
    {
        $input = "R 4";
        $move = Move::fromString($input);

        $this->assertEquals($input, $move->__toString());
    }
}