# Day 23: Unstable Diffusion

There is a map with no limits and elves inside (input).
Elves `#` at most distance from the center in each orthogonal (`N`, `E`, `S`, `W`) direction indicates how big is the map.
But this map can grow in the simulation described below.
`.` indicates empty ground. Diagonal directions are `NE`, `NW`, `SE`, `SW`.

The problem consists in a number of rounds, each divided in two parts.
- During the **first half**, each *elf* `x` considers the 8 positions around him looking for.
    - No elves around in 8 positions => `x` do nothing in this round.
    - No elf `y` in N, NE, NW => `x` propose advance 1 step N (= `p`).
    - No elf `y` in S, SE, SW => `x` propose advance 1 step S.
    - No elf `y` in W, NW, SW => `x` propose advance 1 step W.
    - No elf `y` in E, NE, SE => `x` propose advance 1 step E.

After each Elf has had a chance to propose a move,
- the **second half** consists in move each elf simoultaneously.
    - If only elf `x` proposed `p` => `x` moves to `p`.
    - If `y` and `x` proposed `p` =>  Noelf moves.

- Finally, at the end of the round:
    - The queue of intentional directions of each elf is updated moving to the end the direction consider in the turn.
    - For example: Turn 1 (N,S,E,W) Turn 2 (S,E,W,N) Turn 3 (E,W,N,S)

The game ends after a number X of rounds or when all elves are in an isolated position.

The output is the number of empty ground tiles `.` after the tenth round.
In the example is 110.

```
......#.....
..........#.
.#.#..#.....
.....#......
..#.....#..#
#......##...
....##......
.#........#.
...#.#..#...
............
...#..#..#..
```
Example:
```text
== Initial State ==
..............
..............
.......#......
.....###.#....
...#...#.#....
....#...##....
...#.###......
...##.#.##....
....#..#......
..............
..............
..............

== End of Round 1 ==
..............
.......#......
.....#...#....
...#..#.#.....
.......#..#...
....#.#.##....
..#..#.#......
..#.#.#.##....
..............
....#..#......
..............
..............

== End of Round 2 ==
..............
.......#......
....#.....#...
...#..#.#.....
.......#...#..
...#..#.#.....
.#...#.#.#....
..............
..#.#.#.##....
....#..#......
..............
..............

== End of Round 3 ==
..............
.......#......
.....#....#...
..#..#...#....
.......#...#..
...#..#.#.....
.#..#.....#...
.......##.....
..##.#....#...
...#..........
.......#......
..............

== End of Round 4 ==
..............
.......#......
......#....#..
..#...##......
...#.....#.#..
.........#....
.#...###..#...
..#......#....
....##....#...
....#.........
.......#......
..............

== End of Round 5 ==
.......#......
..............
..#..#.....#..
.........#....
......##...#..
.#.#.####.....
...........#..
....##..#.....
..#...........
..........#...
....#..#......
```

### Ideas

The fist idea to mind is convert each line in a number, we can consider each elf as a bit. However, the size can be bigger than 64 bit. In fact, the original input is always a square of 74. We can use integers in blocks of 32 bits for the data of the elves, but this complicates considerably calculations in the frontiers and updates.

The logic then could be when the max size is achieved in the block add another adjacent block of 32 bits in the direction required.
Then, the orientation is saved horizontally in an array.
The vertical orientation should be just the correspondant bit in the horizontal information.

Each block is 64 bits however, the last 32 bits can be used for other purposes (or keep them at 0). Bit 33

With this data structure each movement from the elves can be infered together using bit operations (Note x in R to L order). For example,
```
  
  3 2 1 0 (Bits in horizontal number) $x = index  of Vertical array 
  8 4 2 1 (2 power value) $V[i] = 2**$x
0 . . . # => 1 => 0001 => $A
1 . # . . => 4 => 0100 => $B
y
```
We detect that nobody moves, because there is not interference between them.
Considering the north position. The elf at row 0 doesn't have interferences from the north (is at the north limit). We can consider the outbound like 0 always.
The elf at row 1 column 2, we can detect that there is not elves at the row 1 (N), using this bit logic (we consider numbers/words of 4 bits for the example): `$A & 0b1110 => 0` The idea is mask all adjacent 3 bits in row 0 that have a bit on in the row 1.
How?
We should consider each bit on (the index `x`of the vertical array `$V` `x`. The mask for that bit `$V[$x+1] + $V[$x] + $V[$x-1]).
For the shouth at position is similar for elf at row 0, besides that we don't need to consider position `(x= -1, y=1)`. we should mask`$B & 0b0011 => 0`.
For checking W, we should check for elf (0, 0)
`$H[0] & $V[$x + 1] + $H[1] & $V[$x + 1] === 0`
For checking E, we detect that is at the border.

For checking E, we should check for elf (2,1)
`$H[0] & $V[$x - 1] + $H[1] & $V[$x - 1] === 0`
We keep the dimensions of the map, and update them at the end of each round.
However, to make the calculations easier, we keep the frontier as a 0.

...

### Proposing moves

To implement the logic of this part of the problem, we need to check first those elves that doesn't have others elves adjacent.
We can filter them, in the next steps of the algorithm.
This would mean, mask the words/ints with the elf info.
Then, we check orthogonal directions in order.
If no proposition is available for one tile, this elf must be still (the same as isolated). So, we add to that no "proposition".

At the end we receive and array that have this structure:
```pseudocode
p[y][w][direction | 'isolated'] = int|mask
```

### Updating elves positions

With the data structure saved in $proposed array. We should check several special cases.
1. Growth of the map: If some elf in a border is proposing to move out of bounds, we should increment the map in that direction.
2. Reduction of the map: If **all** elves in one border are proposing to move inside, we should reduce the map.
3. We should consider the frontiers created by the size of the word (32, in our case) when elves move to the EAST or WEST.
4. The rest of the cases follow same logic.

For example, if we want to consider the elves that move to a row `2` (starting at 0, we consider a dimension of 8 vertical and horizontal),
- We should `xor`
  - proposed positions from row `1` direction south,
  - proposed positions from row `3` direction north,
  - still positions from row `2`.
we get a partial group of moves available.

- This partial result `xor` east and that `xor` west. (To consider all cases, when there are more than one word, we should check limits at the adjacent words.)

For the special cases we do:
1. **Growth**. We save them in NORTHMOST, EASTMOST, WESTMOST and SOUTHMOST, and use them at the end of the turn to update all.
2. **Reduction**: This operation must be done at the end, after all proposed positions are calculated and updated.
3. **Updating leftmost bit from a word and rightmost bit from the next**: For those bits between words, in east direction, we should xor each leftmost bit from word at (w + 1 = x % 32 = 0) proposing to the east (w + 1) and rightmost bit from (w = x % 32 = 31) proposing to west.

4041 TOO LOW
4066 TOO LOW
4607 TOO HIGH
4109 OK

### Part 2

We need to show which round elves don't need to move.
In the example was 20.

1099 TOO HIGH

1055 CORRECT ANSWER