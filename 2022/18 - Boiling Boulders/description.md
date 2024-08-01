# Day 18: Boiling Boulders

The cooling rate should be based on the surface area of the lava droplets, so you take a quick scan of a droplet as it flies past you (input).

Its resolution is quite low and,
it approximates the shape of the lava droplet with
**1x1x1 cubes on a 3D grid**, each given as its `x,y,z` position.

To approximate the surface area, count the number of sides of each cube that are not immediately connected to another cube.
So, if your scan were only two adjacent cubes like `1,1,1` and `2,1,1`, each cube would have a single side covered and five sides exposed, a total surface area of `10` sides.

With this example:
```text
2,2,2
1,2,2
3,2,2
2,1,2
2,3,2
2,2,1
2,2,3
2,2,4
2,2,6
1,2,5
3,2,5
2,1,5
2,3,5
```

The above example, after counting up all the sides that aren't connected to another cube, the surface is **64**.

### Solution

The first idea is include a type of counter of the total surface sides of each block.

The algorithm can work as follow.

1. Create a Graph $G = (V, E)$ list of adjacency between blocks.

We can consider each block as a node, and the connections or edges of those blocks that share a face. Note that the edge of the graph is different that the edge of each block. To avoid confusion

For example, with the blocks `0: 1,1,1` and `1: 2,1,1`. We assume that the point is the the leftest, bottom, nearest to origin of coordinates (0,0,0).
And the index respects the order of blocks read from the input.
Considering that every edge of the block has 1 unit long (`size`), two blocks are sharing a face if they have four vertex points together.
Besides, the maximum distance between the origin points must be 1. So if the distance, in all possible directions is 1 to another block.
Those blocks share a face. We can save them like an undirected graph in a adjacency list or matrix.

$ d_E(P,Q) = \sqrt{(p_x - q_x)^2 + (p_y - q_y)^2 + (p_z - q_z)^2} = 1  => $ $P$ and $Q$ share a face. We can square both parts of the equation, and get that:

$d^2_E(P,Q) = (p_x - q_x)^2 + (p_y - q_y)^2 + (p_z - q_z)^2 = 1 $

Once we have the number of edges in the graph undirected, $2|E|$, we can get the total surface like this:
Because we keep track of edges like a directed graph, each pair of edges mean a common face that is not an external surface, so.
`surface = number_of_blocks * faces per block - |E|`

### Second Part

This part requires to know what exterior surface area, this requires identified the internal blocks of air, that means, those point in the the 3d map that are not occupied by blocks and creates an internal surface. So the solution will be:

`external surface = total surface - internal surface`.

To be honest, I don't know the formula for this, but I suppose that it could be possible using proyections in all directions, maybe with slices. Another possibility could be generating an Inverse Matrix of points.

After some research, there is a family of algorithms called [*flood fill*](https://en.wikipedia.org/wiki/Flood_fill) that seems can solve this problem.

