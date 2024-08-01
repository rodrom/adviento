<?php

declare(strict_types=1);
namespace Rodrom\Advent202215;
class Device
{

    public function __construct(
        public CoordinateMap $sensors,
        public CoordinateMap $beacons
    ) { }

    public static function readInput(string $input): static
    {
        $sensors = new CoordinateMap();
        $beacons = new CoordinateMap();
        $sensorsString = explode("\n", $input);
        foreach ($sensorsString as $k => $s) {
            if (preg_match(
                '/^Sensor at x=(-?\d+), y=(-?\d+): closest beacon is at x=(-?\d+), y=(-?\d+)$/',
                $s,
                $matches
                )
            ) {
                [$sensor, $beacon] = static::readSensorData($matches);
                $sensors->putC($sensor);
                if ($beacons->exist($beacon)) {
                    $beacons->addUnit($beacon);
                } else {
                    $beacons->putC($beacon);
                }
            }
        }
        return new static($sensors, $beacons);
    }

    private static function readSensorData (array $input): array
    {
        $sensorX = intval($input[1]);
        $sensorY = intval($input[2]);
        $beaconX = intval($input[3]);
        $beaconY = intval($input[4]);

        $manhattanDistance = static::manhattanDistance($sensorX, $sensorY, $beaconX, $beaconY);

        return [
            new Coordinate($sensorX, $sensorY, $manhattanDistance, 'S'),
            new Coordinate($beaconX, $beaconY, 1, 'B'),
        ];
    }

    public static function manhattanDistance(int $x1, int $y1, int $x2, int $y2): int
    {
        $a = ($x1 < $x2) ? $x2 - $x1 : $x1 - $x2;
        $b = ($y1 < $y2) ? $y2 - $y1 : $y1 - $y2;
        return $a + $b;
    }

    public function forbiddenCoordinatesRange(int $y): int
    {
        $sensorsInRange = $this->forbiddenSensorsRange($y);
        $beaconsInRow = $this->beaconsInRow($y);
        $sensorsInRow = $this->sensorsInRow($y);
        $ranges = [];
        //$coordinates = [];
        foreach ($sensorsInRange as $k => $s)
        {
            $minDistance = ($s->y < $y) ? ($s->y - $y) : ($y - $s->y);
            $horizontalLimit = ($minDistance < $s->h) ? ($minDistance - $s->h) : ($s->h - $minDistance);
            $m0 = $s->x - $horizontalLimit;
            $m1 = $s->x + $horizontalLimit;
            $this->addRange($m0, $m1, $ranges);
        }
        $beaconsToDiscount = 0;
        foreach ($beaconsInRow as $b) {
            foreach ($ranges as [$min, $max]) {
                if ($this->inRange($b, $min, $max)) {
                    $beaconsToDiscount++;
                }
            }
        }
        $sensorsToDiscount = 0;
        foreach ($sensorsInRow as $s) {
            foreach ($ranges as [$min, $max]) {
                if ($this->inRange($s, $min, $max)) {
                    $sensorsToDiscount++;
                }
            }
        }
        $coordinatesFromRanges = 0;
        foreach ($ranges as [$min, $max]) {
            echo "$min - $max\n";
            $coordinatesFromRanges += abs($min - $max) + 1;
        }
        return $coordinatesFromRanges - $beaconsToDiscount /*- $sensorsToDiscount*/;
    }

    public function forbiddenSensorsRange(int $y): \Ds\Map
    {
        $sensorsInRange = $this->sensors->map->filter(
            function (string $key, Coordinate $c) use ($y): bool
            {
                //$a = (($c->y - $y) >= 0) ? ($c->y - $y) <= $c->h : ($y - $c->y) <= $c->h;
                return abs($c->y - $y) <= $c->h;
            }
        );

        return $sensorsInRange;
    }

    public function beaconsInRow(int $y): \Ds\Map
    {
        $beaconsInRow = $this->beacons->map->filter(
            function (string $key, Coordinate $c) use ($y): bool
            {
                return $c->y === $y;
            }
        );
        return $beaconsInRow;
    }

    public function sensorsInRow(int $y): \Ds\Map
    {
        $sensorsInRow = $this->sensors->map->filter(
            function (string $key, Coordinate $c) use ($y): bool
            {
                return $c->y === $y;
            }
        );
        return $sensorsInRow;
    }

    public static function addRange(int $min, int $max, array &$ranges): int
    {
        if (count($ranges) === 0) {
            $ranges[] = [$min, $max];
            return 1;
        }
        $included = false;
        foreach ($ranges as [&$m0, &$m1]) {
            echo "min: $min max: $max m0: $m0 m1: $m1\n";
            // Input Range inside Current Range
            if ($min >= $m0 && $max <= $m1) {
                return -2;
            }
            // Current Range inside Input Range
            if ($min <= $m0 && $max >= $m1) {
                $m0 = $min;
                $m1 = $max;
                return 2;
            }
            // Input Range smaller in the left.
            if ($min < $m0 && $max >= $m0 && $max <= $m1) {
                $m0 = $min;
                return -1;
            }

            // Input Range bigger in the right
            if ($min >= $m0 && $min <= $m1 && $max > $m1) {
                $m1 = $max;
                return 1;
            }

            // Input Range and Current Range continous (smaller Input Range)
            if ($max + 1 === $m0) {
                $m0 = $min;
                return -3;
            }

            // Current Range and Input Range continous
            if ($m1 + 1 === $min) {
                $m1 = $max;
                return 3;
            }
            // Current and Input Range are totally split.
            echo "THE INPUT $min | $max IS NOT inside CURRENT RANGE $m0 | $m1\n";
        }
        $ranges[] = [$min, $max];
        return 0;
    }

    public static function inRange(Coordinate $c, int $min, int $max): bool
    {
        return $c->x >= $min && $c->x <= $max;
    }
}