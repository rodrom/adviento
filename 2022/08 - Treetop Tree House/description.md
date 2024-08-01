# Day 8: Treetop Tree House

## Part 1

The Elves want to build a tree house. The requirements are keep the house **hidden**.
They need count the number of trees that are **visible from outside the grid** when
looking directly along a row or column.

The Elves gives you an map with the *height* of each **tree** in the grid (input).

For example:
```text
30373
25512
65332
33549
35390
```

Each digit is the height of the tree, between 0 and 9.
A tree is **visible** if all of the other trees between it and an edge of the grid are **shorter** than it.
Only consider rows and columns. And up, down, left and right from the current tree.

All of the trees around the edge of the grid are **visible**.
So the trees in the interior are the only ones that could be hidden.

According to this example, there 21 trees visible from the outside of the grid:
16 from the edge and 5 inside. Using 0index notation for (row,column) the next grid show the hidden trees with ` `
and the Visible tree with `V`
```
VVVVV
VVV V
VV VV
V V V
VVVVV
```

How many trees are visible from outside the grid.

### Part 1

The idea is create an structure for the grid of trees.
First, we should init the grid with their dimensions: height and width.
It could be a Fixed Array of two dimensions which first dimension are the rows, and second dimension is the column.
Then load the data of the trees.
The trees should have an height, so they can be saved just like integers.

The grid should have a function like `isTreeVisible(row, col): bool`.
This returns true, when all trees in four directions are smaller than it.
And another like `counTreesVisible()`, that reduce the structure to the sum of visible trees.
Having methods to move from one tree to another in the four directions could be useful.
`up`, `down`, `left`, and `right`. Could be also useful implmenting functions that consider
things like `visibleFromTheTop(): bool`, `visibleFromTheBottom()`, `visibleFromTheRight()` and `visibleFromTheLeft()`.

The idea, to learn to use generators is very useful, we can define functions that go from current tree until the last tree
in wanted direction.

### Part 2

The Elves want to know the best spot to build their tree house. They want to see a lot of trees. To measure the viewing distance
from a given tree; we should look in each direction. You should stop at the edge, or at the first tree taller or equal.
The distance from an Edge tree to out of bounds is 0.

For example, in the example:
```text
30373
25512
65332
33549
35390
```
Looking at the tree (1,2) (second five in the second row).
- Up: 1 (not blocked)
- Left: 1 (blocked by (1,1))
- Right: 2 (not blocked)
- Down: 2 (blocked by (3,2))

The **tree scenic score** (tss) is multiplying together each view in four directions. `1*1*2*2 = 4`.

However, the best **tss** is (3,2) with a value of 8.

#### Solution

The idea is similar to part 1, in this case we change the condition and the counting. We need to create a function than calc
**tss**, and then look the tree with the highest position.
