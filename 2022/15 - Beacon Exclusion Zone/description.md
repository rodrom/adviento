# Day 15: Beacon Exclusion Zone

Each *sensor* finds a spot that gives a good reading of the nearest signal *beacon*.

Sensors and beacons are placed in integer coordinates.

Each sensor knows its own position and can *determine the position of a beacon precisely*. Only the closest one, as measured by the Manhattan distance. Which is the distance between Point 1 and Point 2:
```text
P1 = (x1, y1), P2 = (x2, y2)
|x1 - x2| + |y1 - y2|
```

There is never a tie between two beacons and a sensor.

The **input** is the data from the sensors:
```text
Sensor at x=2, y=18: closest beacon is at x=-2, y=15
Sensor at x=9, y=16: closest beacon is at x=10, y=16
Sensor at x=13, y=2: closest beacon is at x=15, y=3
Sensor at x=12, y=14: closest beacon is at x=10, y=16
Sensor at x=10, y=20: closest beacon is at x=10, y=16
Sensor at x=14, y=17: closest beacon is at x=10, y=16
Sensor at x=8, y=7: closest beacon is at x=2, y=10
Sensor at x=2, y=0: closest beacon is at x=2, y=10
Sensor at x=0, y=11: closest beacon is at x=2, y=10
Sensor at x=20, y=14: closest beacon is at x=25, y=17
Sensor at x=17, y=20: closest beacon is at x=21, y=22
Sensor at x=16, y=7: closest beacon is at x=15, y=3
Sensor at x=14, y=3: closest beacon is at x=15, y=3
Sensor at x=20, y=1: closest beacon is at x=15, y=3
```

So, each beacon can be the nearest to several sensors, and could be beacons that are not nearest to any sensor.
However, it could be possible to determine in which coordinates is not possible to have beacons.

The result of this first part, should be indicate how manay coordinates are impossible to have a beacon in a given
`y` row.

In the example is the row 10, and the exercise input `y=2000000`.

## Solution

As usual, the first part is to parse the data.
At the moment, we save in our usual `Coordinate` class and `CollectionMap` two different groups of coordinates.
`Sensors` save the manhattan distance to the nearest `Beacon`, and `Beacons` the number of `Sensors` that detects the Beacon
as the nearsest.
The idea to get the impossible coordinates to have a beacon in certain row consists in get all those sensors that cover
that row. And according to that, which columns are not possible to have them. The sum of all sensors and unique coordinates
that affect that row is the final results.

The first step of this algorithm is filter the sensors that `abs(c->y - c->h) <= abs(c->y - y)`. With this list of sensors,
we can determine the line of coordinates that are not suitable for a beacon according to the range with center in the perpendicular coordinate from the sensor to the inspected row `y`. The difference between the manhattan distance to that perpendicular point is the the range to left and right. In this example, the Sensor have a distance of 6. And the not possible coordinates at the fourth row are sown with an `X` and also with the `*` that indicates the perpendicular coordinate.

```text
....S....
....#....
....#....
....#....
__XX*XX__
....#....
....B....
```

The problem with the exercise data, is that the distances are so big, that is not sense of inserting all points.
We should consider the ranges.
So it would be necessary to modifiy the logic that save lines, and the logic that consider a beacon inside a CoordinateMap.

