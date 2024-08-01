# Day 12: Hill Climbing Algorithm

The input is a map of hights of the surrounding area.
Each level of elevation is code with a lower case from `a` (lowest) to `z` (maximum).

In the map is included your current position (`S`) and the location that should get the
best signal `E`. `S` start in a `a` altitude, and `E` is `z`.

The idea is to reach `E` in the **few steps possible**.
The rule to advance is that next position could be only be higher in one level, (no limit going down in one step).

example:
```
Sabqponm
abcryxxl
accszExk
acctuvwj
abdefghi
```

One solution is:
```text
v..v<<<<
>v.vv<<^
.>vv>E^^
..v>>>^^
..>>>>>^
```

Legend: `v` down | `>` right | `<` left | `^` up.

This reach the path in 31 steps, the fewest possible.

What is the fewest steps required to move from your current position to the location that should get the best signal?

### Solution

The first thing is parse the info from the puzzle.

It should be not very complex, and the data could be keep in the original format.
We could assignate a grid of coordinates in rows and columns. From left to right and up to down.
We could also reuse and adapt the logic of movements from the Trees problem. It should be simpler in some parts.

Then we should have an algorithm to get the best path. These means less steps. Ideally, this should be a path that each step
goes one level. All paths should avoid visiting two times the same coordinate. So in each movement, the best immediate option should be  going up, stay on the level, or going down the less.

The worst path is visiting all the squares, with this logic, the worst move in each option chould be go the unvisited spot with
the lowest level.

This logic is with one deepness of functionality.

To consider the options, could be interesting have a class `Position` that keeps control of the current coordinate and can
determine the posible next **movement**. There should be also a `Coordinate` that indicates the column, the row and hight.
There should be a `Path` wich keeps track of the coordinates visited and is able to return the number of steps and if it is
an invalid or valid path.

We should keep try (the minimum) possible valid paths until find the best one. After find one, it should be possible to avoid
continue looking for paths that are worst that the best in the middle.

This all, was already thought by Dijkstra, using theory of Graphs.
So we also need to create an structure that represents the graph of **nodes** (the Coordinates) and the **edges** (the conection between nodes). There is **no weighed** edges represented by the difference of highness between adjacent nodes. Those are the rules to determine if there is an **edge** (of 1 step).

It could be possible to find the optimal solution with two approachs, checking all the minimum paths from the start to all the
the different nodes. Considering the rules of going higher.

Other solution could be create a graph from the top to the starting point, where the only possibles vertex from one node are
those with... Nop, this solution doesnt work. Wether to arrive to the top, is necessary to going down an up several times.

### Different algorithms to Shortest PATH

Source: [hellokoding.com](https://hellokoding.com/shortest-paths/#:~:text=The%20shortest%20path%20algorithm%20finds%20paths%20between%20two,The%20shortest%20path%20is%20%5B3%2C%202%2C%200%2C%201%5D)

### BFS Algorithm

 directed unweighted `G = (V,E)` graphof `V` vertex, with `s` as source and `E` edges. The idea is to keep the distance from each vertex in `D`. The algorithm initializes all distances to INFINITE
 except `d[s] = 0`. The update formula is `d[v] = d[u] + 1` for
 every edge `(u, v)`.
```
SP_BFS (Graph g, Vertex s): Distances
    int[] distances for g->numberOfVertex();
    int[] predecessors for g->numberOfVertex();

    for (v = 0; v < g.numberOfVertex(); v++):
        distances[v] = INF
        predecessors[v] = UNDEFINED

    distances[s] = 0;

    bool[] visted for b->g.numberOfVertex();
    visited[s] = true;

    Q = new Queue with s

    while(q not empty):
        i = q.pop

        for (v : g.getAdjacents.u)
        si (v not visit):
            visited[v] = true
            distances[v] = distances[u] + 1
            predecessors[v] = u;
            q.push(v)

    return distances,predecessors
```

Onece the algorithm ends, it should be possible to know the steps getting the distance of the `E` vertex.

The complexity: O(V+E)
Space complexity: O(V)

### Part 2

The second part is very similar to Part 1, except that we need to find the smallest point `a` to arrive to `E`.
One option is to consider the start point E. And make a graph that only consider the shorthest path to the rest of points.
The rules for this graph are different, you can only going down or equal. But again, i am not sure, to be able to infer the rules for inversion paths.
Considering that, we already have a method to go from any point to another. The solution will be find all the mininmum distances between a points and e. 
After creating the map and the nodes, we check every point again, and if its an `a` we calc the shorthest path
```
for each a in map:
    pathsSteps.add(spBFS(a, E))

minimum(paths)
```