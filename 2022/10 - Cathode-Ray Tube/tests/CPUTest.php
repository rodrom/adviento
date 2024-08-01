<?php

declare(strict_types=1);
namespace Rodrom\Advent202210\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202210\CPU;

class CPUTest extends TestCase
{
    public function testCreateCPU(): void
    {
        $cpu = new CPU();
        $this->assertInstanceOf(CPU::class, $cpu);
    }

    public function testRunNoop(): void
    {
        $cpu = new CPU();
        array_push($cpu->stack, "noop");

        $acc = $cpu->run();
        $this->assertEquals(0, $acc);

    }

    public function testRunAddx3(): void
    {
        $cpu = new CPU();
        array_push($cpu->stack, "addx 3");

        $acc =  $cpu->run();
        $this->assertEquals(4, $cpu->x);
    }

    public function testRunNoopAddx3Addx5(): void
    {
        $cpu = new CPU();
        array_push($cpu->stack, "noop", "addx 3", "addx -5");

        $acc =  $cpu->run();
        $this->assertEquals(-1, $cpu->x);
    }

    public function testSignalCycle(): void
    {
        $cpu = new CPU();
        $cpu->cycle = 220;

        $this->assertTrue($cpu->isSignalCycle());
    }

    public function testFirst20InputsExample(): void
    {
        $input = [
            "addx 15",
            "addx -11",
            "addx 6",
            "addx -3",
            "addx 5",
            "addx -1",
            "addx -8",
            "addx 13",
            "addx 4",
            "noop",
            "addx -1",
            "addx 5",
            "addx -1",
            "addx 5",
            "addx -1",
            "addx 5",
            "addx -1",
            "addx 5",
            "addx -1",
            "addx -35",
        ];

        $cpu = new CPU(stack: $input);
        $acc = $cpu->run();

        $this->assertEquals(420, $acc);
    }
}
