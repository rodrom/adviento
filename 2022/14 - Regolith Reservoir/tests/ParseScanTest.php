<?php

declare(strict_types=1);
namespace Rodrom\Advent202214\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202214\Cave;

class ParseScanTest extends TestCase
{
    public function testMapOfTheCaveIsCorrectlyScan(): void
    {
        $cave = Cave::fromString(
            "498,4 -> 498,6 -> 496,6\n503,4 -> 502,4 -> 502,9 -> 494,9\n",
            500, 0
        );

        $this->assertInstanceOf(Cave::class, $cave);
        $this->assertTrue($cave->map->map->hasKey("498,4"));
        $this->assertCount(20, $cave->map->map);
    }
}