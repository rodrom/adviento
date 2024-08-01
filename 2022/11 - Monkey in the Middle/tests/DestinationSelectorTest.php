<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202211\DestinationSelector;
use Rodrom\Advent202211\Operation;

class DestinationSelectorTest extends TestCase
{
    public function testCreateInstanceOfDestinationSelector(): void
    {
        $ds = new DestinationSelector(Operation::Divisible, "2", 1, 2);
        
        $this->assertInstanceOf(DestinationSelector::class, $ds);
        $this->assertEquals(true, $ds->test("2"));
        $this->assertEquals(1, $ds->destination("2"));
        $this->assertEquals(2, $ds->destination("1"));
    }
}