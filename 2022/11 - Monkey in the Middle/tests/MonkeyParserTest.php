<?php

declare(strict_types=1);
namespace Rodrom\Advent202211\Test;
use PHPUnit\Framework\TestCase;
use Rodrom\Advent202211\Monkey;
use Rodrom\Advent202211\MonkeyParser;

class MonkeyParserTest extends TestCase
{

    public function testParserMonkeyId(): void
    {
        $this->assertEquals(0, MonkeyParser::getId("Monkey 0:"));

        $this->assertEquals(10, MonkeyParser::getId("Monkey 10:"));
    }

    public function testParserMonkeyItems(): void
    {
        $this->assertEquals(["79", "98"], MonkeyParser::getItems("  Starting items: 79, 98"));
    }

    public function testParserMonkeyUpdater(): void
    {
        [$operator, $operand] = MonkeyParser::getUpdaterElements("  Operation: new = old * 19");
        $this->assertEquals(['*', 19], [$operator, $operand]);
        
    }

    public function testParserMonkeyUpdater2(): void
    {
        [$operator, $operand] = MonkeyParser::getUpdaterElements("  Operation: new = old * old");
        $this->assertEquals(['**', 2], [$operator, $operand]);
        
    }

    public function testParserMonkeyTester(): void
    {
        $factor = MonkeyParser::getTesterElements("  Test: divisible by 23");
        $this->assertEquals(23, $factor);
    }

    public function testParserMonkeyTruthy(): void
    {
        $factor = MonkeyParser::getTesterElementsTruthy("    If true: throw to monkey 2");
        $this->assertEquals(2, $factor);
    }

    public function testParserMonkeyFalsy(): void
    {
        $factor = MonkeyParser::getTesterElementsFalsy("    If false: throw to monkey 3");
        $this->assertEquals(3, $factor);
    }

    public function testParserMonkeyAll(): void
    {
        $monkey = MonkeyParser::fromString(
            <<<EOD
            Monkey 0:
            Starting items: 79, 98
            Operation: new = old * 19
            Test: divisible by 23
                If true: throw to monkey 2
                If false: throw to monkey 3
            EOD);

        $this->assertInstanceOf(Monkey::class, $monkey);
        $this->assertEquals(0, $monkey->id);
    }
}
