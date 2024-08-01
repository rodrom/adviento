<?php

declare(strict_types=1);
namespace Rodrom\Advent202209\Tests;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202209\Itinerary;

class ItineraryTest extends TestCase
{
    public function testCreateItinerary(): void
    {
        $itinerary = new Itinerary();
        $this->assertInstanceOf(Itinerary::class, $itinerary);
    }
}