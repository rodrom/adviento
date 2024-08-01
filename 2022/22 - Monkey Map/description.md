# Day 22: Monkey Map

The problem (input) is a **maze** and a **path**.
Each line have the coordinates of the terrain, that can be:
- Out of range with an space ` ` in the left or a new line on the right.
- The actual coordinates of the maze are corridors `.` or walls `#`.
- The path/password is separated with a blank line from the maze.
- The password is a set of movements in the form of *number of steps* *turn instruction*
- The movement stops when you hit a wall `#`.
- The initial position is the first tile direction 90 degrees (or East).
- `R` turn instruction is 90 degrees clockwise/right.
- `L` turn instruction is 90 degrees counterclockwise/left.
- When your position gets to the last tile of a corridor, the next tile is the first one in the same corridor direction. Examples from A to B going right (horizonatally). C to D south (vertically).
```text
        ...#
        .#..
        #...
        ....
...#.D.....#
........#...
B.#....#...A
.....C....#.
        ...#....
        .....#..
        .#......
        ......#.
```

- When there is a wall (including when, for example, B is a wall), movement stops (at A, in the example).

The output of the problem is the last position of the path (**row**, **column**, **facing/direction**) according to this rules:
- `rows` start at 1 at the top.
- `columns` start at leftmost and count out of range tiles on the left and right.
- `direction` values are:
  - `east`: 0
  - `south`: 1
  - `west`: 2
  - `north`: 3
- The final password (output) is : `1000 * finalRow + 4 * finalColumn + direction`

In the example, the path drawn is (E final position.):
```text
        >>v#    
        .#v.    
        #.v.    
        ..v.    
...#...v..v#    
>>>v...E#.>>    
..#v...#....    
...>>>>v..#.    
        ...#....
        .....#..
        .#......
        ......#.
```

And the output (row:6, col: 8, dir: EAST (0)) is: 1000 * 6 + 4 * 8 + 0 = 6032

The process for move follows this logic:
```pseudocode
while current move = next move:
    while (move.steps > 0):
        next position = f(current position, current direction, 1)
        if (next position === wall):
            break
        current position = next position
        move.steps--
    current direction = next direction // consider last case
    

```

Solution: 89224

## Second Part

The map is a cube unfolded of 50x50 in each side (16x16 in the example):
```text
        1111
        1111
        1111
        1111
222233334444
222233334444
222233334444
222233334444
        55556666
        55556666
        55556666
        55556666
```

So the warping changes, because now the moves are following the cube logic.
For example, `A -> B d: 0 (EAST)` and `C -> D d: 1 (SOUTH)`.

```text
        ...#
        .#..
        #...
        ....
...#..E....#
........#..A
..#....#....
.D........#.
        ...#..B.
        .....#..
        .#......
        ..C...#.
```

One option to adapt the new rules of warping could be automatically give the information of outofbounds like `[row, column, direction]`. With the same sides like those show above, the logic of the cube in the edges and keeping the old coordinates in 2d:

According to the example the logic is, being SIZE = 4:
- Side 1 d: N (3) => Side 2 d: S
  - (1,9)  -> (5,4) | (1, 2*SIZE + 1) -> (SIZE + 1, SIZE)
  - (1,10) -> (5,3) | (1, 2*SIZE + 2) -> (SIZE + 1, 3)
  - (1,11) -> (5,2) | (1, 2*SIZE + 3) -> (SIZE + 1, 2)
  - (1,12) -> (5,1) | (1, 2*SIZE + SIZE) - (SIZE + 1, 1)
- Side 1 d: E (0) => Side 6 d: W
  - (1,12) -> (12, 16) | (1, 3 * SIZE) -> (3 * SIZE, 4 * SIZE)
  - (2,12) -> (11, 16) | (2, 3 * SIZE) -> (3 * SIZE - 1, 4 * SIZE)
  - (3,12) -> (10, 16) | (3, 3 * SIZE) -> (3 * SIZE - 2, 4 * SIZE)
  - (4,12) -> (9, 16)  | (SIZE, 3 * SIZE) -> (3 * SIZE - 3, 4 * SIZE)
- Side 1 d: S => Side 4 d: S (NO WARP)
- Side 1 d: W (2) => Side 3 d: S
  - (1, 9) -> (5, 5) | (1, 2 * SIZE + 1) -> (SIZE + 1, SIZE + 1)
  - (2, 9) -> (5, 6) | (2, 2 * SIZE + 1) -> (SIZE + 1, SIZE + 2)
  - (3, 9) -> (5, 7) | (3, 2 * SIZE + 1) -> (SIZE + 1, SIZE + 3)
  - (4, 9) -> (5, 8) | (SIZE, 2 * SIZE + 1) -> (SIZE + 1, 2 * SIZE)

- Side 2 d: N => SIDE 1 d: S (INVERSE 1 -> 2) - OK
- Side 2 d: E => SIDE 3 d: E (NO WARP)
- Side 2 d: S => Side 5 d: N
  - (8,4) -> (12,9) | (2 * SIZE, SIZE) -> (3 * SIZE, 2 * SIZE + 1)
  - (8,3) -> (12,10) | (2 * SIZE, SIZE - 1) -> (3 * SIZE, 2 * SIZE + 2)
  - (8,2) -> (12,11) | (2 * SIZE, SIZE - 2) -> (3 * SIZE, 2 * SIZE + 3)
  - (8,1) -> (12,12)  | (2 * SIZE, SIZE - 3) -> (3 * SIZE, 3 * SIZE)
- Side 2 d: W => Side 6 d: N
  - (5,1) -> (12,16) | (SIZE + 1, 1) -> (3 * SIZE, 4 * SIZE)
  - (6,1) -> (12,15) | (SIZE + 2, 1) -> (3 * SIZE, 4 * SIZE - 1)
  - (7,1) -> (12,14) | (SIZE + 3, 1) -> (3 * SIZE, 4 * SIZE - 2)
  - (8,1) -> (12,13) | (SIZE + 4, 1) -> (3 * SIZE, 4 * SIZE - 3)

- Side 3 d: N => Side 1 d: E (Inverse 1 -> 3)
- Side 3 d: E => Side 4 d: E (NO WARP)
- Side 3 d: S => Side 5 d: E
  - (8,5) -> (12,9) | (2 * SIZE, SIZE + 1) -> (3 * SIZE, 2 * SIZE + 1)
  - (8,6) -> (11,9) | (2 * SIZE, SIZE + 2) -> (3 * SIZE - 1, 2 * SIZE + 1)
  - (8,7) -> (10,9) | (2 * SIZE, SIZE + 3) -> (3 * SIZE - 2, 2 * SIZE + 1)
  - (8,8) -> (9,9)  | (2 * SIZE, 2 * SIZE) -> (3 * SIZE - 3, 2 * SIZE + 1)
- Side 3 d: W => Side 2 d: W (NO WARP)

- Side 4 d: N => Side 1 d: N (NO WARP)
- Side 4 d: E => Side 6 d: S
  - (5,12) -> (9,16) | (SIZE + 1, 3 * SIZE) -> (2 * SIZE + 1, 3 * SIZE)
  - (6,12) -> (9,15) | (SIZE + 2, 3 * SIZE) -> (2 * SIZE + 1, 3 * SIZE - 1)
  - (7,12) -> (9,14) | (SIZE + 3, 3 * SIZE) -> (2 * SIZE + 1, 3 * SIZE - 2)
  - (8,12) -> (9,13) | (SIZE + 2, 3 * SIZE) -> (2 * SIZE + 1, 3 * SIZE - 3)
- Side 4 d: S => Side 5 d: S (NO WARP)
- Side 4 d: W => Side 3 d: W (NO WARP)

- Side 5 d: N => Side 4 d: N (NO WARP)
- Side 5 d: E => Side 6 d: E (NO WARP)
- Side 5 d: S => Side 3 d: N (Inverse 3 -> 5)
- Side 5 d: W => Side 2 d: N (Inverse 2 -> 5)

- Side 6 d: N => Side 4 d: W (Inverse 4 -> 6)
- Side 6 d: E => Side 1 d: W (Inverse 1 -> 6)
- Side 6 d: S => Side 2 d: E (Inverse 2 -> 6)
- Side 6 d: W => Side 5 d: W (NO WARP)
