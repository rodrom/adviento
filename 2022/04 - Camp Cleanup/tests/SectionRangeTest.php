<?php

declare(strict_types=1);

namespace Rodrom\Advent202204\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202204\SectionPairing;

class SectionRangeTest extends TestCase
{
    public function test_parsing_line_from_input(): void
    {
        $pair = SectionPairing::getPairingFromString("2-4,6-8");
        $this->assertInstanceOf(SectionPairing::class, $pair);
        $this->assertEquals(2, $pair->firstRangeMin);
        $this->assertEquals(4, $pair->firstRangeMax);
        $this->assertEquals(6, $pair->secondRangeMin);
        $this->assertEquals(8, $pair->secondRangeMax);
    }

    public function test_parsing_line_from_input_big_ranges(): void
    {
        $pair = SectionPairing::getPairingFromString("2-100,69-800");
        $this->assertInstanceOf(SectionPairing::class, $pair);
        $this->assertEquals(2, $pair->firstRangeMin);
        $this->assertEquals(100, $pair->firstRangeMax);
        $this->assertEquals(69, $pair->secondRangeMin);
        $this->assertEquals(800, $pair->secondRangeMax);
    }

    public function test_pairing_range_overlaps_false_first_range_lower(): void
    {
        $pair = SectionPairing::getPairingFromString("2-4,6-8");
        
        $this->assertFalse($pair->isRangesOverlapping());
    }

    public function test_pairing_range_overlaps_false_second_range_lower(): void
    {
        $pair = SectionPairing::getPairingFromString("6-8,2-4");
        
        $this->assertFalse($pair->isRangesOverlapping());
    }

    public function test_pairing_range_overlaps_true_first_range_inside_second(): void
    {
        $pair = SectionPairing::getPairingFromString("6-8,2-8");
        
        $this->assertTrue($pair->isRangesOverlapping());
    }

    public function test_pairing_range_overlaps_true_second_range_inside_first(): void
    {
        $pair = SectionPairing::getPairingFromString("2-8,6-8");
        
        $this->assertTrue($pair->isRangesOverlapping());
    }

    public function test_pairing_range_overlaps_same_ranges(): void
    {
        $pair = SectionPairing::getPairingFromString("8-8,8-8");
        
        $this->assertTrue($pair->isRangesOverlapping());
    }

    public function test_first_range_fully_overlaps_second(): void
    {
        $pair = SectionPairing::getPairingFromString("1-3,2-2");

        $this->assertTrue($pair->isRangesFullyOverlapping());
    }

    public function test_first_range_not_fully_overlaps_second(): void
    {
        $pair = SectionPairing::getPairingFromString("1-3,2-4");

        $this->assertFalse($pair->isRangesFullyOverlapping());
    }

    public function test_second_range_fully_overlaps_first(): void
    {
        $pair = SectionPairing::getPairingFromString("2-2,1-3");

        $this->assertTrue($pair->isRangesFullyOverlapping());
    }

    public function test_second_range_not_fully_overlaps_first(): void
    {
        $pair = SectionPairing::getPairingFromString("2-4,1-3");

        $this->assertFalse($pair->isRangesFullyOverlapping());
    }

    public function test_equal_ranges_fully_overlaps(): void
    {
        $pair = SectionPairing::getPairingFromString("2-4,2-4");

        $this->assertTrue($pair->isRangesFullyOverlapping());
    }
}
