<?php

declare(strict_types=1);
namespace Rodrom\Advent202209\Tests;
use PHPUnit\Framework\TestCase;
use Rodrom\Advent202209\Direction;
use Rodrom\Advent202209\Move;
use Rodrom\Advent202209\Point;
use Rodrom\Advent202209\Rope;

class RopeTest extends TestCase
{
    public function testCreateRope(): void
    {
        $rope = new Rope();
        $this->assertInstanceOf(Rope::class, $rope);
    }

    public function testMovingTheHeadOfTheRopeUpOneStep(): void
    {
        $rope = new Rope();
        $move = new Move(Direction::Up);
        $expected = new Point(0,1);

        $this->assertEquals($expected, $rope->moveHead($move));
    }

    public function testMovingTheHeadOfTheRopeDown4Steps(): void
    {
        $rope = new Rope();
        $move = new Move(Direction::Down, 4);
        $expected = new Point(0,-4);

        $this->assertEquals($expected, $rope->moveHead($move));
    }

    public function testMovingTheHeadOfTheRopeLeft3Steps(): void
    {
        $rope = new Rope();
        $move = new Move(Direction::Left, 3);
        $expected = new Point(-3,0);

        $this->assertEquals($expected, $rope->moveHead($move));
    }

    public function testMovingTheHeadOfTheRopeRight2Steps(): void
    {
        $rope = new Rope();
        $move = new Move(Direction::Right, 2);
        $expected = new Point(2,0);

        $this->assertEquals($expected, $rope->moveHead($move));
    }

    public function testMovingTheRopeInWrongDirectionCauseException(): void
    {
        $rope = new Rope();
        $move = new Move(Direction::UpLeft, 1);

        $this->expectException(\Exception::class);

        $rope->moveHead($move);
    }

    public function testDontMoveTheTailWhenHeadIsAtTheSamePoint(): void
    {
        $rope = new Rope();
        $expected = [ ];
        $this->assertFalse($rope->tailShouldMove());
        $this->assertEquals($expected, $rope->moveTail());
    }

    public function testDontMoveTheTailWhenHeadIsUpOneStep(): void
    {
        $rope = (new Rope())->setPosition(0,1,0,0);
        $expected = [];
        $this->assertFalse($rope->tailShouldMove());
        $this->assertEquals($expected, $rope->moveTail());
    }

    public function testDontMoveTheTailWhenHeadIsUpRightOneStep(): void
    {
        $rope = (new Rope())->setPosition(1,1,0,0);
        $expected = [];
        $this->assertFalse($rope->tailShouldMove());
        $this->assertEquals($expected, $rope->moveTail());
    }

    public function testDontMoveTheTailWhenHeadIsDownLefttOneStep(): void
    {
        $rope = (new Rope())->setPosition(0,0,1,1);
        $expected = [];
        $this->assertFalse($rope->tailShouldMove());
        $this->assertEquals($expected, $rope->moveTail());
    }

    public function testDontMoveTheTailWhenHeadIsDownRightOneStep(): void
    {
        $rope = (new Rope())->setPosition(1,0,0,1);
        $expected = [];
        $this->assertFalse($rope->tailShouldMove());
        $this->assertEquals($expected, $rope->moveTail());
    }

    public function testDontMoveTheTailWhenHeadIsUpLeftOneStep(): void
    {
        $rope = (new Rope())->setPosition(0,1,1,0);
        $expected = [];
        $this->assertFalse($rope->tailShouldMove());
        $this->assertEquals($expected, $rope->moveTail());
    }

    public function testMoveTheTailWhenHeadIsUpTwoSteps(): void
    {
        $rope = (new Rope())->setPosition(0,2,0,0);
        $expected = [new Point(0,1)];
        $this->assertTrue($rope->tailShouldMove());
        $this->assertEquals($expected, $rope->moveTail());
    }

    public function testMoveTheTailWhenHeadIsDownTwoSteps(): void
    {
        $rope = (new Rope())->setPosition(0,0,0,2);
        $expected = [new Point(0,1)];
        $this->assertTrue($rope->tailShouldMove());
        $this->assertEquals($expected, $rope->moveTail());
    }

    public function testMoveTheTailWhenHeadIsLeftTwoSteps(): void
    {
        $rope = (new Rope())->setPosition(0,0,2,0);
        $expected = [new Point(1,0)];
        $this->assertTrue($rope->tailShouldMove());
        $this->assertEquals($expected, $rope->moveTail());
    }

    public function testMoveTheTailWhenHeadIsRightTwoSteps(): void
    {
        $rope = (new Rope())->setPosition(2,0,0,0);
        $expected = [new Point(1,0)];
        $this->assertTrue($rope->tailShouldMove());
        $this->assertEquals($expected, $rope->moveTail());
    }

    public function testMoveTheTailWhenHeadIsUpRightTwoSteps(): void
    {
        $rope = (new Rope())->setPosition(2,1,0,0);
        $expected = [new Point(1,1)];
        $this->assertTrue($rope->tailShouldMove());
        $this->assertEquals($expected, $rope->moveTail());
    }

    public function testMoveTheTailWhenHeadIsUpLeftTwoSteps(): void
    {
        $rope = (new Rope())->setPosition(0,2,1,0);
        $expected = [new Point(0,1)];
        $this->assertTrue($rope->tailShouldMove());
        $this->assertEquals($expected, $rope->moveTail());
    }

    public function testMoveTheTailWhenHeadIsDownRightTwoSteps(): void
    {
        $rope = (new Rope())->setPosition(1,0,0,2);
        $expected = [new Point(1,1)];
        $this->assertTrue($rope->tailShouldMove());
        $this->assertEquals($expected, $rope->moveTail());
    }

    public function testMoveTheTailWhenHeadIsDownLeftTwoSteps(): void
    {
        $rope = (new Rope())->setPosition(0,0,2,1);
        $expected = [new Point(1,0)];
        $this->assertTrue($rope->tailShouldMove());
        $this->assertEquals($expected, $rope->moveTail());
    }
}