# Day 24: Blizzard Basin

The problem consists in find the minimum time to pass a laberinth of blizzards in four directions according to the rules of the game.
- Blizzards advance in their direction one step per minute.
- Player can move in four directions one step per minute (at the same time than blizzards) or stay.
- Blizzard and player cannot occupy the same tile.
- Blizzards can stack up in the same tile.

To save this information we can create an structure.
```pseudocode
Board
  int dimX
  int dimY
  int start
  int exit
  int[] blizzards

  blizzAt(dir, t, index)
  quietAt(dir, t, index)
```

And from this structure, we can create a directed graph that builds a path to the exit.
- The graph begins at the start tile and minute 0.
- The second node is the next position and the weight of the edge is the minutes that waits the player to advance.
- The third node is one of the positions available and the wait time to get there.
- Etc.
- End node is the final path.

We should use some optimization strategies, and, maybe, a greedy strategy. We should decide what strategy is better to analyze the optimal solutions: BFS, Dijkstra / DFS.

