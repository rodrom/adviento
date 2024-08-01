<?php

declare(strict_types=1);
namespace Rodrom\Advent202209\Tests;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202209\Direction;
use Rodrom\Advent202209\Point;

class DirectionTest extends TestCase
{
    public function testDirectionFromTwoPointsUp(): void
    {
        $from = new Point(0,0);
        $to = new Point(0,1);

        $this->assertEquals(
            Direction::Up,
            Direction::fromTwoPoints($from, $to)
        );
    }

    public function testDirectionFromTwoPointsDown(): void
    {
        $from = new Point(0,1);
        $to = new Point(0,0);

        $this->assertEquals(
            Direction::Down,
            Direction::fromTwoPoints($from, $to)
        );
    }

    public function testDirectionFromTwoPointsLeft(): void
    {
        $from = new Point(1,0);
        $to = new Point(0,0);

        $this->assertEquals(
            Direction::Left,
            Direction::fromTwoPoints($from, $to)
        );
    }

    public function testDirectionFromTwoPointsRight(): void
    {
        $from = new Point(0,0);
        $to = new Point(1,0);

        $this->assertEquals(
            Direction::Right,
            Direction::fromTwoPoints($from, $to)
        );
    }

    public function testDirectionFromTwoPointsUpRight(): void
    {
        $from = new Point(0,0);
        $to = new Point(1,1);

        $this->assertEquals(
            Direction::UpRight,
            Direction::fromTwoPoints($from, $to)
        );
    }

    public function testDirectionFromTwoPointsUpLeft(): void
    {
        $from = new Point(1,0);
        $to = new Point(0,1);

        $this->assertEquals(
            Direction::UpLeft,
            Direction::fromTwoPoints($from, $to)
        );
    }

    public function testDirectionFromTwoPointsDownLeft(): void
    {
        $from = new Point(1,1);
        $to = new Point(0,0);

        $this->assertEquals(
            Direction::DownLeft,
            Direction::fromTwoPoints($from, $to)
        );
    }

    public function testDirectionFromTwoPointsDownRight(): void
    {
        $from = new Point(0,1);
        $to = new Point(1,0);

        $this->assertEquals(
            Direction::DownRight,
            Direction::fromTwoPoints($from, $to)
        );
    }
}