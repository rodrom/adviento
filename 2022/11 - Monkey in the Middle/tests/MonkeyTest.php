<?php

declare(strict_types=1);
namespace Rodrom\Advent202211\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202211\DestinationSelector;
use Rodrom\Advent202211\Monkey;
use Rodrom\Advent202211\Operation;
use Rodrom\Advent202211\WorrynessUpdate;

class MonkeyTest extends TestCase
{
    public function testCreateEmptyMonkey(): void
    {
        $monkey = new Monkey();
        $this->assertInstanceOf(Monkey::class, $monkey);
    }

    public function testCreateFullOperationalMonkey(): void
    {
        $monkey = new Monkey(
            items: [79, 79],
            operation: new WorrynessUpdate(
                Operation::Multiplication,
                operand: "19"
            ),
            selector: new DestinationSelector(
                Operation::Divisible,
                operand: "23",
                truthy: 2,
                fail: 3
            ),
            id: 0
        );

        $this->assertInstanceOf(Monkey::class, $monkey);
    }

    public function testMonkeyInspectItem(): void
    {
        $monkey = new Monkey(
            items: [79, 79],
            operation: new WorrynessUpdate(
                Operation::Multiplication,
                operand: "19"
            ),
            selector: new DestinationSelector(
                Operation::Divisible,
                operand: "23",
                truthy: 2,
                fail: 3
            ),
            id: 0
        );
    
        $this->assertEquals("1501", $monkey->inspect("79"));
    }

    public function testBored(): void
    {
        $monkey = new Monkey();
        $expected = 500;
    
        $this->assertEquals($expected, $monkey->bored("1501"));
    }

    public function testDestinationSelection(): void
    {
        $monkey = new Monkey(
            selector: new DestinationSelector(
                Operation::Divisible,
                operand: "23",
                truthy: 2,
                fail: 3
            )
        );
        $expected = 3;

        $this->assertEquals($expected, $monkey->destination("1500"));
    }

    public function testPlayTurnMonkey(): void
    {
        $monkey = new Monkey(
            items: ["79", "98"],
            operation: new WorrynessUpdate(
                Operation::Multiplication,
                operand: "19"
            ),
            selector: new DestinationSelector(
                Operation::Divisible,
                operand: "23",
                truthy: 2,
                fail: 3
            ),
            id: 0
        );

        $expected = [
            ["1501", 3],
            ["1862", 3]
        ];

        $throws = $monkey->playTurn();
        
        $this->assertEquals($expected, $throws);
        $this->assertEquals(2, $monkey->inspections);
        $this->assertFalse($monkey->hasItems());
    }
}