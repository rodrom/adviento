<?php

declare(strict_types=1);
namespace Rodrom\Advent202210\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202210\CRT;

class CRTTest extends TestCase
{
    public function testCreateCPU(): void
    {
        $crt = new CRT();
        $this->assertInstanceOf(CRT::class, $crt);
    }

    public function testRunNoop(): void
    {
        $crt = new CRT();
        array_push($crt->stack, "noop");

        $output = $crt->run();
        $this->assertEquals("#", $output);

    }

    public function testRunAdd15(): void
    {
        $crt = new CRT();
        array_push($crt->stack, "addx 15");

        $output =  $crt->run();
        $this->assertEquals("##", $output);
    }

    public function testRunIntermedios(): void
    {
        $crt = new CRT();
        array_push($crt->stack, "addx 15", "addx -11", "addx 6", "addx -3", "addx 5", "addx -1");

        $output =  $crt->run();
        $this->assertEquals("##..##..##..", $output);
    }

    public function testFirst20InputsExampleCRT(): void
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
        ];

        $crt = new CRT(stack: $input);
        $output = $crt->run();

        $this->assertEquals("##..##..##..##..##..#", $output);
    }
}
