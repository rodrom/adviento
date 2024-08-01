<?php

declare(strict_types=1);
namespace Rodrom\Advent202215\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202215\CoordinateBCMath;
use Rodrom\Advent202215\DeviceBCMath;

class ParseReaderBCMathTest extends TestCase
{
    public function testReadInputSimpleData(): void
    {
        $device = DeviceBCMath::readInput(
            "Sensor at x=0, y=0: closest beacon is at x=1, y=1"
        );
        $expectedSensor = new CoordinateBCMath("0", "0", "2", 'S');
        $expectedBeacon = new CoordinateBCMath("1", "1", "1", 'B');

        $this->assertInstanceOf(DeviceBCMath::class, $device);
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
        $device = DeviceBCMath::readInput(
            "Sensor at x=-2, y=-2: closest beacon is at x=-1, y=-1"
        );
        $expectedSensor = new CoordinateBCMath("-2", "-2", "2", 'S');
        $expectedBeacon = new CoordinateBCMath("-1", "-1", "1", 'B');

        $this->assertInstanceOf(DeviceBCMath::class, $device);
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
        $device = DeviceBCMath::readInput($input);

        $this->assertEquals(14, $device->sensors->count(), "FALLO AL CONTAR SENSORES");
        $this->assertEquals(6, $device->beacons->count(), "FALLO AL CONTAR BALIZAS");
    }
}