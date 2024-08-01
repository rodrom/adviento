<?php

declare(strict_types=1);
namespace Rodrom\Advent202215\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202215\Coordinate;
use Rodrom\Advent202215\Device;

class ParseReaderTest extends TestCase
{
    public function testReadInputSimpleData(): void
    {
        $device = Device::readInput(
            "Sensor at x=0, y=0: closest beacon is at x=1, y=1"
        );
        $expectedSensor = new Coordinate(0, 0, 2, 'S');
        $expectedBeacon = new Coordinate(1, 1, 1, 'B');

        $this->assertInstanceOf(Device::class, $device);
        $this->assertTrue($device->sensors->map->hasKey("0,0"));
        $s = $device->sensors->map->get("0,0");
        $this->assertEquals($expectedSensor, $s);
        
        $this->assertTrue($device->beacons->map->hasKey("1,1"));
        $b = $device->beacons->map->get("1,1");
        $this->assertEquals($expectedBeacon, $b);

        $this->assertEquals(1, $device->sensors->count());
        $this->assertEquals(1, $device->beacons->count());
    }

    public function testReadNegativeCoordinates(): void
    {
        $device = Device::readInput(
            "Sensor at x=-2, y=-2: closest beacon is at x=-1, y=-1"
        );
        $expectedSensor = new Coordinate(-2, -2, 2, 'S');
        $expectedBeacon = new Coordinate(-1, -1, 1, 'B');

        $this->assertInstanceOf(Device::class, $device);
        $this->assertTrue($device->sensors->map->hasKey("-2,-2"));
        $s = $device->sensors->map->get("-2,-2");
        $this->assertEquals($expectedSensor, $s);

        $this->assertTrue($device->beacons->map->hasKey("-1,-1"));
        $b = $device->beacons->map->get("-1,-1");
        $this->assertEquals($expectedBeacon, $b);
      
        $this->assertEquals(1, $device->sensors->count());
        $this->assertEquals(1, $device->beacons->count());
    }

    public function testReadExampleData(): void
    {
        $input = file_get_contents(__DIR__ . '\..\example.txt');
        $device = Device::readInput($input);

        $this->assertEquals(14, $device->sensors->count(), "FALLO AL CONTAR SENSORES");
        $this->assertEquals(6, $device->beacons->count(), "FALLO AL CONTAR BALIZAS");
    }
}