# Day 14 - Regolith Reservoir

You scan a two-dimensional vertical slice of the cave above you (input).
And discover that it is mostly *air* with structures of *rock*.

Your scan traces the path of each solid rock structure and reports `x,y`
coordinates that form the shape of the path. Where `x` goes right, and `y` goes down.
Each path appears as a single line of thext in your scan.
After the first point of each path,
each point indicates the end of a straight horizontal or vertical line from the
previous point:
```
498,4 -> 498,6 -> 496,6
503,4 -> 502,4 -> 502,9 -> 494,9
```

This scan means that there are two paths of rock;

The sand is poursing into the cave from 500,0.
Drawing rock as #, air as `.`, and the source of the sand as `+`, this becomes:
```text

  4     5  5
  9     0  0
  4     0  3
0 ......+...
1 ..........
2 ..........
3 ..........
4 ....#...##
5 ....#...#.
6 ..###...#.
7 ........#.
8 ........#.
9 #########.
```

Sand is produces **one unit at a time**,
and the next unit of sand is not produced,
until the previous unit of sand comes to rest.
A unit of sand is large enough to fill one tile of air
in your scan.

A unit of sand always falls *down one step* if posisible.
If the tile immediately below is blocked (by rock or sand),
the unit of sand moves diagonally *one step down and the left*.
If that tile is blocked, the unit of sand moves *one step down and to the right*.
Sand keeps moving as long as it is able to do so, at each step trying to move down,
then down-left, then down-right.
If all three are blocked, the unit of sand no longer moves. The next unit of sand is
created back at the source.

So: `s0` as first unit of sand `o`, will go down like this:
```text
......+...
..........
..........
..........
....#...##
....#...#.
..###...#.
........#.
......o.#.
#########.
```

`s1`, lands on the first, and then goes down left.
```text
......+...
..........
..........
..........
....#...##
....#...#.
..###...#.
........#.
.....oo.#.
#########.
```

After 24 units of sand, `s23`, only the sand can goes left to an abyss on the left side.
```text
......+...
..........
......o...
.....ooo..
....#ooo##
...o#ooo#.
..###ooo#.
....oooo#.
.o.ooooo#.
#########.
```

Using your scan simulate the falling sand. How many units of sand come to the rest before flowing to the abyss.

### Solution

The first problem is to create a system of coordinates, the problem of the sorthest path can help. Taking the Coordinate
classes, and maybe some parts of the map, like Edge representing lines of rocks.

As usual, one of the first things to do, is parsing the input.
In this case, we should break each line and read the data point after point until the end.
Without knowing in advance what are the dimensions of the map. We should keep
track of the coordinates in a order way.
One solution could be read all the lines (using some kind of Edge class, also used in the climbing problem), and after having
them, save enough memory for the dimensions of the map.

In theory, all lines are straight. That means, that a section of rocks could be updated directly, after reading each point.
```php
origin = line_input.pop
while (destination = line_input.pop):
    for (point from origin to destination):
        point = rock;
    origin = destination;
```



Then, it comes simulate the sand.