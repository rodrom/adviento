<?php

declare(strict_types=1);
namespace Rodrom\Advent202209\Tests;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202209\Point;

class PointTest extends TestCase
{
    public function testCreateInitialPoint(): void
    {
        $point = new Point(1,0);

        $this->assertInstanceOf(Point::class, $point);
        $this->assertEquals(1, $point->x);
        $this->assertEquals(0, $point->y);
    }
}