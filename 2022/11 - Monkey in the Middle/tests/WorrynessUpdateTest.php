<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202211\Operation;
use Rodrom\Advent202211\WorrynessUpdate;

class WorrynessUpdateTest extends TestCase
{
    public function testCreateInstanceOfWorrynessUpdate(): void
    {
        $wu = new WorrynessUpdate(Operation::Multiplication, "19");
        
        $this->assertInstanceOf(WorrynessUpdate::class, $wu);
        $this->assertEquals("38", $wu->update("2"));
    }
}