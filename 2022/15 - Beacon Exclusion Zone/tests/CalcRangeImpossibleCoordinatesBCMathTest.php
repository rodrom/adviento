<?php

declare(strict_types=1);
namespace Rodrom\Advent202215\Test;
use PHPUnit\Framework\TestCase;
use Rodrom\Advent202215\DeviceBCMath;

class CalcRangeImpossibleCoordinatesBCMathTest extends TestCase
{
    public function test_simple_range_impossible_beacon(): void
    {
        $input = "Sensor at x=0, y=0: closest beacon is at x=0, y=1";
        $device = DeviceBCMath::readInput($input);

        $forbiddenResults = $device->forbiddenSensorsRange("0");
        $this->assertEquals($device->sensors->map, $forbiddenResults);
    }

    public function test_two_sensors_range_impossible_one_beacon(): void
    {
        $input = "Sensor at x=0, y=0: closest beacon is at x=0, y=1\nSensor at x=0, y=3: closest beacon is at x=0, y=1";
        $device = DeviceBCMath::readInput($input);

        $forbiddenResults = $device->forbiddenSensorsRange("0");
        $this->assertEquals(1, $forbiddenResults->count());
        $this->assertEquals(2, $device->sensors->count());
        $this->assertFalse($forbiddenResults->hasKey("0,3"));
        $this->assertTrue($forbiddenResults->hasKey("0,0"));
    }

    public function test_one_sensor_get_impossible_range(): void
    {
        $input = "Sensor at x=0, y=0: closest beacon is at x=0, y=1";
        $device = DeviceBCMath::readInput($input);

        $forbiddenCoordinates = $device->forbiddenCoordinatesRange("-1");
        $this->assertEquals(1, $forbiddenCoordinates);
        //$this->assertTrue($forbiddenCoordinates->map->hasKey("0,-1"));
    }

    public function test_two_sensor_get_impossible_range(): void
    {
        $input = "Sensor at x=0, y=0: closest beacon is at x=0, y=1\nSensor at x=0, y=3: closest beacon is at x=0, y=1";
        $device = DeviceBCMath::readInput($input);

        $forbiddenCoordinates = $device->forbiddenCoordinatesRange("1");
        $this->assertEquals(0, $forbiddenCoordinates); // We should discount Beacon detected?
        //$this->assertFalse($forbiddenCoordinates->map->hasKey("0,1"));
    }

    public function test_example_impossible_range_at_row_ten(): void
    {
        $input = file_get_contents(__DIR__ . '\..\example.txt');
        $device = DeviceBCMath::readInput($input);

        $forbiddenCoordinates = $device->forbiddenCoordinatesRange("10");
        $this->assertEquals(26, $forbiddenCoordinates);
        // $this->assertFalse($forbiddenCoordinates->map->hasKey("2,10"));
        // $this->assertFalse($forbiddenCoordinates->map->hasKey("-3,10"));
        // $this->assertTrue($forbiddenCoordinates->map->hasKey("-2,10"));
        // $this->assertFalse($forbiddenCoordinates->map->hasKey("25,10"));
        // $this->assertTrue($forbiddenCoordinates->map->hasKey("24,10"));
    }

    public function test_custom_example(): void
    {
        $input = file_get_contents(__DIR__ . '\..\custom.txt');
        $device = DeviceBCMath::readInput($input);

        $forbiddenCoordinates = $device->forbiddenCoordinatesRange("1");
        $this->assertEquals(3, $forbiddenCoordinates, "CUSTOM LINE 1");

        $forbiddenCoordinates = $device->forbiddenCoordinatesRange("0");
        $this->assertEquals(3, $forbiddenCoordinates, "CUSTOM LINE 0"); // Delete Sensors in Range

        $forbiddenCoordinates = $device->forbiddenCoordinatesRange("2");
        $this->assertEquals(2, $forbiddenCoordinates, "CUSTOM LINE 2");

        $forbiddenCoordinates = $device->forbiddenCoordinatesRange("3");
        $this->assertEquals(3, $forbiddenCoordinates, "CUSTOM LINE 3");

        $forbiddenCoordinates = $device->forbiddenCoordinatesRange("4");
        $this->assertEquals(3, $forbiddenCoordinates, "CUSTOM LINE 4");

        $forbiddenCoordinates = $device->forbiddenCoordinatesRange("5");
        $this->assertEquals(3, $forbiddenCoordinates, "CUSTOM LINE 5");

        $forbiddenCoordinates = $device->forbiddenCoordinatesRange("6");
        $this->assertEquals(1, $forbiddenCoordinates, "CUSTOM LINE 6");
    }
}