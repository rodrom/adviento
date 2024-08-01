# Day 16: Proboscidea Volcanium

There is an estimated time of **30 minutes** before the volcano erupts.

After scanning the cave, there is a network of *pipes* and pressure-release *valves*.

The new device produces a report (input) of each valve's **flow rate** if it were
opened (in pressure per minute) and the tunnles you could use to move between the valves.

There's even a valve in the *room* you and the elephants are currently standing labeled
`AA`.

You estimate it will take you one minute to open a single valve and one to follow
any *tunnel* from one valve to another.

What is the most pressure you could release?

For example, suppose you had the following scan output:
```text
Valve AA has flow rate=0; tunnels lead to valves DD, II, BB
Valve BB has flow rate=13; tunnels lead to valves CC, AA
Valve CC has flow rate=2; tunnels lead to valves DD, BB
Valve DD has flow rate=20; tunnels lead to valves CC, AA, EE
Valve EE has flow rate=3; tunnels lead to valves FF, DD
Valve FF has flow rate=0; tunnels lead to valves EE, GG
Valve GG has flow rate=0; tunnels lead to valves FF, HH
Valve HH has flow rate=22; tunnel leads to valve GG
Valve II has flow rate=0; tunnels lead to valves AA, JJ
Valve JJ has flow rate=21; tunnel leads to valve II
```

The valves at rate 0 are not levering any pressure.

It should be reasonable to open several or all valves to release as much pressure as possible.
One possible path is:
```text
== Minute 1 ==
No valves are open.
You move to valve DD.

== Minute 2 ==
No valves are open.
You open valve DD.

== Minute 3 ==
Valve DD is open, releasing 20 pressure.
You move to valve CC.

== Minute 4 ==
Valve DD is open, releasing 20 pressure.
You move to valve BB.

== Minute 5 ==
Valve DD is open, releasing 20 pressure.
You open valve BB.

== Minute 6 ==
Valves BB and DD are open, releasing 33 pressure.
You move to valve AA.

== Minute 7 ==
Valves BB and DD are open, releasing 33 pressure.
You move to valve II.

== Minute 8 ==
Valves BB and DD are open, releasing 33 pressure.
You move to valve JJ.

== Minute 9 ==
Valves BB and DD are open, releasing 33 pressure.
You open valve JJ.

== Minute 10 ==
Valves BB, DD, and JJ are open, releasing 54 pressure.
You move to valve II.

== Minute 11 ==
Valves BB, DD, and JJ are open, releasing 54 pressure.
You move to valve AA.

== Minute 12 ==
Valves BB, DD, and JJ are open, releasing 54 pressure.
You move to valve DD.

== Minute 13 ==
Valves BB, DD, and JJ are open, releasing 54 pressure.
You move to valve EE.

== Minute 14 ==
Valves BB, DD, and JJ are open, releasing 54 pressure.
You move to valve FF.

== Minute 15 ==
Valves BB, DD, and JJ are open, releasing 54 pressure.
You move to valve GG.

== Minute 16 ==
Valves BB, DD, and JJ are open, releasing 54 pressure.
You move to valve HH.

== Minute 17 ==
Valves BB, DD, and JJ are open, releasing 54 pressure.
You open valve HH.

== Minute 18 ==
Valves BB, DD, HH, and JJ are open, releasing 76 pressure.
You move to valve GG.

== Minute 19 ==
Valves BB, DD, HH, and JJ are open, releasing 76 pressure.
You move to valve FF.

== Minute 20 ==
Valves BB, DD, HH, and JJ are open, releasing 76 pressure.
You move to valve EE.

== Minute 21 ==
Valves BB, DD, HH, and JJ are open, releasing 76 pressure.
You open valve EE.

== Minute 22 ==
Valves BB, DD, EE, HH, and JJ are open, releasing 79 pressure.
You move to valve DD.

== Minute 23 ==
Valves BB, DD, EE, HH, and JJ are open, releasing 79 pressure.
You move to valve CC.

== Minute 24 ==
Valves BB, DD, EE, HH, and JJ are open, releasing 79 pressure.
You open valve CC.

== Minute 25 ==
Valves BB, CC, DD, EE, HH, and JJ are open, releasing 81 pressure.

== Minute 26 ==
Valves BB, CC, DD, EE, HH, and JJ are open, releasing 81 pressure.

== Minute 27 ==
Valves BB, CC, DD, EE, HH, and JJ are open, releasing 81 pressure.

== Minute 28 ==
Valves BB, CC, DD, EE, HH, and JJ are open, releasing 81 pressure.

== Minute 29 ==
Valves BB, CC, DD, EE, HH, and JJ are open, releasing 81 pressure.

== Minute 30 ==
Valves BB, CC, DD, EE, HH, and JJ are open, releasing 81 pressure.
```

This is the best possible path to release most pressure. It release 1651.

### Solution

As always, the first thing is to model the problem. We need to create first the **newtork of tunnels between rooms and valves**.

```php
/**
 * This class keep info of each Valve
 */
class Valve
{
    string $label;
    int $flowRate;
    bool $close;
}

/**
 * Indicates a connection between to Valves
 */
class Tunnel
{
    string $origin;
    string $destination;
}

/**
 * Keep track of all the tunnels in the network
 */
class TunnelNetwork
{
    array $tunnels;
}

class Action
{
    TypeAction $type;
    int $minute;
}

/**
 * Determine the different actions that are possible
 */
enum TypeAction
{
    case OPEN_VALVE;
    case MOVE_TO_VALVE;
}

/**
 * Indicates all the possibilities for each minute, each node at bottom will should tell
 * the best pressure release.
 */
class ActionTreeCollection
{
    SplMinHeap $actionPerMinute;
}
```

To avoid overcomplications, we will use a unique class that keeps record of each "structure" if the class is simple enough.

The problem with this approach is that the possible branches of the tree decision is so big, that is not possible to try all the options. W we should create a graph that analyse possible paths between valves with a flow rate bigger than 0.

With this Graph, we should create the different approach to open the maximum rate of float rates.

So, the solution should:

1. Read the input data
2. Create the graph of the tunnels and generate the shothest path from `AA` to the other Valves.
3. Create the maximum flow rate decision tree for X turns.
4. This can be done using dynamic programming and memozation, as well, it is very useful to mask the valves with flow rate to a bitmask.

### Part 2

This parte asks you to consider two different players opening valves simultaneously, but in 26 minutes instead of 30.

The idea could be design disjoint groups of "useful" valves, and each player open them in the optimal time. The key of the "cache" table keeps record of every open valves combination.

So those that are complementatary should be each bit 1 and 0 respectively in each player. So and should be 0 in total. `bitmaskPlayer1 & bitMaskPlayer2 = 0`.