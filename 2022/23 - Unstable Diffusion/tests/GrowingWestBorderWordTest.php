<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202223\Difussion;

class GrowingWestBorderWordTest extends TestCase
{
    public function test_growing_border_two_words(): void
    {
        $input = <<<EOD
        ####............................
        ...............................#
        EOD;

        $d = Difussion::readInput($input);
        $this->assertEquals(32, $d->dimensionX);
        $this->assertEquals(2, $d->dimensionY);
        
        $p = $d->firstHalf(Difussion::WEST);
        $expected = [
            0 => [
                0 => [
                    Difussion::WEST => 2 ** 31
                ]
            ]
        ];
        $this->assertEquals($expected[0][0][Difussion::WEST], $p[0][0][Difussion::WEST]);

        $q = $d->growingMapQueue($p);
        $expected = [
            Difussion::WEST => [ 0 => 2 ** 31, 1 =>  0]
        ];
        $this->assertEquals($expected[Difussion::WEST][0], $q[Difussion::WEST][0]);

        $h = $d->internalHorizontalMoves($p);
        $expected = [
            0 => [ 0 => 0],
            1 => [ 0 => 1]
        ];
        $this->assertEquals($expected, $h);

        $v = $d->internalVerticalMoves($h, $p);
        $expected = [
            0 => [ 0 => 2 ** 27 ],
            1 => [ 0 => 1]
        ];
        $this->assertEquals($expected, $v);

        $d->updateElvesPosition($v, $p);
        $this->assertEquals($expected, $d->horizontal);

        $d->updateBorders($q);
        $expected = [
            0 => [ 0 => 2 ** 29 + 2 ** 30, 1 => 0],
            1 => [ 0 => 2 ** 27, 1 => 1],
            2 => [ 0 => 1, 1 => 0]
        ];

        $this->assertEquals($expected, $d->horizontal);
    }
}