<?php

declare(strict_types=1);
namespace Rodrom\Advent202209\Tests;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202209\RopeWithKnots;

class RopeWithKnotsTest extends TestCase
{
    public function testCreateRopeWithKnots(): void
    {
        $rope = new RopeWithKnots();
        $this->assertInstanceOf(RopeWithKnots::class, $rope);

        $expected = "0:(0, 0)1:(0, 0)2:(0, 0)3:(0, 0)4:(0, 0)5:(0, 0)6:(0, 0)7:(0, 0)8:(0, 0)9:(0, 0)";

        $this->assertEquals($expected, $rope->__toString());
    }

    public function testMoveRopeR4(): void
    {
        $rope = new RopeWithKnots();
        [$head, $tail, $visitedInMoveByTheTail] = $rope->move("R 4");

        $expected = "0:(4, 0)1:(3, 0)2:(2, 0)3:(1, 0)4:(0, 0)5:(0, 0)6:(0, 0)7:(0, 0)8:(0, 0)9:(0, 0)";

        $this->assertEquals($expected, $rope->__toString());
        $this->assertEquals("(4, 0)", $head->__toString());
        $this->assertEquals("(0, 0)", $tail->__toString());
        $this->assertEquals(0, $visitedInMoveByTheTail);
        $this->assertEquals(1, count($rope->getVisitedByTheTail()));
    }

    public function testMoveFirstExample(): void
    {
        $rope = new RopeWithKnots();
        $moves = [
            "R 4",
            "U 4",
            "L 3",
            "D 1",
            "R 4",
            "D 1",
            "L 5",
            "R 2",
        ];
        foreach($moves as $move) {
            [$head, $tail, $visitedInMoveByTheTail] = $rope->move($move);
        }

        // ......
        // ......
        // .1H3..  (H covers 2, 4)
        // .5....
        // 6.....  (6 covers 7, 8, 9, s)

        $expected = "0:(2, 2)1:(1, 2)2:(2, 2)3:(3, 2)4:(2, 2)5:(1, 1)6:(0, 0)7:(0, 0)8:(0, 0)9:(0, 0)";
        $this->assertEquals($expected, $rope->__toString());
        $this->assertEquals("(2, 2)", $head->__toString());
        $this->assertEquals("(0, 0)", $tail->__toString());
        $this->assertEquals(0, $visitedInMoveByTheTail);
        $this->assertEquals(1, count($rope->getVisitedByTheTail()));
    }

    public function testMoveR5U8L8(): void
    {
        $rope = new RopeWithKnots();
        $moves = [
            "R 5",
            "U 8",
        ];

        foreach($moves as $m => $move) {
            $rope->move($move);
        }
        [$head, $tail, $visitedInMoveByTheTail] = $rope->move("L 8");

        // H1234.............
        // ....5.............
        // ....6.............
        // ....7.............
        // ....8.............
        // ....9.............
        // ..................
        // ..................
        // ...s..............
        $expected = "0:(-3, 8)1:(-2, 8)2:(-1, 8)3:(0, 8)4:(1, 8)5:(1, 7)6:(1, 6)7:(1, 5)8:(1, 4)9:(1, 3)";
        $this->assertEquals($expected, $rope->__toString());
        $this->assertEquals("(-3, 8)", $head->__toString());
        $this->assertEquals("(1, 3)", $tail->__toString());
        $this->assertEquals(3, $visitedInMoveByTheTail);
        $this->assertEquals(4, count($rope->getVisitedByTheTail()));
    }
    
    public function testMoveSecondExample(): void
    {
        $rope = new RopeWithKnots();
        $moves = [
            "R 5",
            "U 8",
            "L 8",
            "D 3",
            "R 17",
            "D 10",
            "L 25",
            "U 20",
        ];

        foreach($moves as $m => $move) {
            [$head, $tail, $visitedInMoveByTheTail] = $rope->move($move);
        }

        // H.........................
        // 1.........................
        // 2.........................
        // 3.........................
        // 4.........................
        // 5.........................
        // 6.........................
        // 7.........................
        // 8.........................
        // 9.........................
        // ..........................
        // ..........................
        // ..........................
        // ..........................
        // ..........................
        // ...........s..............
        $expected = "0:(-11, 15)1:(-11, 14)2:(-11, 13)3:(-11, 12)4:(-11, 11)5:(-11, 10)6:(-11, 9)7:(-11, 8)8:(-11, 7)9:(-11, 6)";
        $this->assertEquals($expected, $rope->__toString());
        $this->assertEquals("(-11, 15)", $head->__toString());
        $this->assertEquals("(-11, 6)", $tail->__toString());
        $this->assertEquals(11, $visitedInMoveByTheTail);
        $this->assertEquals(36, count($rope->getVisitedByTheTail()));
    }
}