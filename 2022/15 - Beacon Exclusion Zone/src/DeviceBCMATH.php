<?php

declare(strict_types=1);
namespace Rodrom\Advent202215;
class DeviceBCMath
{

    public function __construct(
        public CoordinateMapBCMath $sensors,
        public CoordinateMapBCMath $beacons
    ) { }

    public static function readInput(string $input): static
    {
        $sensors = new CoordinateMapBCMath();
        $beacons = new CoordinateMapBCMath();
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
        $sensorX = $input[1];
        $sensorY = $input[2];
        $beaconX = $input[3];
        $beaconY = $input[4];

        $manhattanDistance = static::manhattanDistance($sensorX, $sensorY, $beaconX, $beaconY);

        return [
            new CoordinateBCMath($sensorX, $sensorY, $manhattanDistance, 'S'),
            new CoordinateBCMath($beaconX, $beaconY, "1", 'B'),
        ];
    }


    public static function manhattanDistance(string $x1, string $y1, string $x2, string $y2): string
    {
        // | $x1 - $x2 | + | $y1 - $y2|
        return bcadd(static::bcAbsSub($x1, $x2), static::bcAbsSub($y1, $y2));
    }

    private static function bcAbsSub(string $a, string $b): string
    {
        $result = bcsub($a, $b);
        return (bccomp($result, "0") >= 0) ? $result : bcmul("-1", $result);
    }

    public function forbiddenCoordinatesRange(string $y): int
    {
        $sensorsInRange = $this->forbiddenSensorsRange($y);
        $beaconsInRow = $this->beaconsInRow($y);
        $sensorsInRow = $this->sensorsInRow($y);
        $ranges = [];
        foreach ($sensorsInRange as $k => $s)
        {
            $minDistance = static::bcAbsSub($this->manhattanDistance($s->x, $s->y, $s->x, $y), $s->h);
            $this->addRange(bcsub($s->x, $minDistance), bcadd($s->x, $minDistance), $ranges);
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
        return $coordinatesFromRanges - $beaconsToDiscount + $sensorsToDiscount;
    }

    public function forbiddenSensorsRange(string $y): \Ds\Map
    {
        $sensorsInRange = $this->sensors->map->filter(
            function (string $key, CoordinateBCMath $c) use ($y): bool
            {
                return bccomp(static::bcAbsSub($c->y, $c->h), static::bcAbsSub($c->y,$y)) >= 0;
            }
        );

        return $sensorsInRange;
    }

    public function beaconsInRow(string $y): \Ds\Map
    {
        $beaconsInRow = $this->beacons->map->filter(
            function (string $key, CoordinateBCMath $c) use ($y): bool
            {
                return bccomp($c->y, $y) === 0;
            }
        );
        return $beaconsInRow;
    }

    public function sensorsInRow(string $y): \Ds\Map
    {
        $sensorsInRow = $this->sensors->map->filter(
            function (string $key, CoordinateBCMath $c) use ($y): bool
            {
                return bccomp($c->y, $y) === 0;
            }
        );
        return $sensorsInRow;
    }

    public static function addRange(string $min, string $max, array &$ranges): int
    {
        if (count($ranges) === 0) {
            $ranges[] = [$min, $max];
            return 1;
        }
        $included = false;
        foreach ($ranges as [&$m0, &$m1]) {
            echo "min: $min max: $max m0: $m0 m1: $m1\n";
            // Input Range inside Current Range
            if (bccomp($min, $m0) >= 0 && bccomp($max, $m1) <= 0) {
                return -2;
            }
            // Current Range inside Input Range
            if (bccomp($min, $m0) <= 0 && bccomp($max, $m1) >= 0) {
                $m0 = $min;
                $m1 = $max;
                return 2;
            }
            // Input Range smaller in the left.
            if (bccomp($min, $m0) < 0 && bccomp($max, $m0) >= 0 && bccomp($max, $m1) <= 0) {
                $m0 = $min;
                return -1;
            }

            // Input Range bigger in the right
            if (bccomp($min, $m0) >= 0 && bccomp($min, $m1) <= 0 && bccomp($max, $m1) > 0) {
                $m1 = $max;
                return 1;
            }

            // Input Range and Current Range continous (smaller Input Range)
            if (bccomp(bcadd($max, "1"), $m0) === 0) {
                $m0 = $min;
                return -3;
            }

            // Current Range and Input Range continous
            if (bccomp(bcadd($m1, "1"), $min) === 0)  {
                $m1 = $max;
                return 3;
            }
            // Current and Input Range are totally split.
            echo "THE INPUT $min | $max IS NOT inside CURRENT RANGE $m0 | $m1\n";
        }
        $ranges[] = [$min, $max];
        return 0;
    }

    public static function inRange(CoordinateBCMath $c, string $min, string $max): bool
    {
        return bccomp($c->x, $min) >= 0 && bccomp($c->x, $max) <= 0;
    }
}