<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202223\Difussion;

class RealInpuTest extends TestCase
{
    protected Difussion $dif;
    protected function setUp(): void
    {
        $this->dif = Difussion::readInput(file_get_contents(__DIR__ . "/../input.txt"));
    }

    public function test_initial_state(): void
    {
        $d = $this->dif;
        $this->assertEquals(2780, $d->elves);
        $this->assertEquals(74 * 74 - 2780, $d->emptyTiles());

        $this->assertEquals(intdiv(74, 32) + 1, count($d->horizontal[0]));

        $expected = [
            0 => 0xD6487F1B,
            1 => 0xB0E154CB,
            2 => 0x30
        ];
        $this->assertEquals($expected, $d->horizontal[0]);

        $expected = [
            0 => 0x2BB486DD,
            1 => 0xC7A1B8CC,
            2 => 0x33B
        ];
        $this->assertEquals($expected, $d->horizontal[73]);
    }

    public function test_corner_upper_right(): void
    {
        $input = <<<EOD
        ........................................................................##
        .......................................................................#.#
        .......................................................................###
        EOD;
        $d = Difussion::readInput($input);
        $this->assertEquals(3, $d->dimensionY);
        $this->assertEquals(7, $d->elves);
        $this->assertEquals(72 + 72 + 71, $d->emptyTiles());
        $initialDimensionX = 74;
        
        $result = $d->play(1);

        $this->assertEquals($initialDimensionX + 1, $d->dimensionX);
        $this->assertEquals(5, $d->dimensionY);
        $expected = [
            0 => [ 0 => 6, 1 => 0, 2 => 0],
            1 => [ 0 => 0, 1 => 0, 2 => 0],
            2 => [ 0 => 17, 1 => 0, 2 => 0],
            3 => [ 0 => 0, 1 => 0, 2 => 0],
            4 => [ 0 => 14, 1 => 0, 2 => 0]
        ];
        $this->assertEquals($expected, $d->horizontal);
        $this->assertEquals(75 * 2 + 73 * 2 + 72, $result);
    }

}