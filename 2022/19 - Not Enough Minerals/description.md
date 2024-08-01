# Day 19: Not Enough Minerals

| Types of robots | resource needed | starting units | location |
| :- | :- | :- | :- |
| Geode-cracking | obsidian | 0 | pond
| Obsidian-collecting | clay | 0 | west
| Clay-collecting | ore | 0 | east
| Ore-collecting | ore | 1 | with you

- Each robot can collect 1 of its resource per minute.
- It takes 1 minute to make one robot with the resources needed consumed

The input is the different **blueprints** available to make the robots.

For example (this input break each Bluprint in several lines, the real input is one blueprint per line).
```text
Blueprint 1:
  Each ore robot costs 4 ore.
  Each clay robot costs 2 ore.
  Each obsidian robot costs 3 ore and 14 clay.
  Each geode robot costs 2 ore and 7 obsidian.

Blueprint 2:
  Each ore robot costs 2 ore.
  Each clay robot costs 3 ore.
  Each obsidian robot costs 3 ore and 8 clay.
  Each geode robot costs 3 ore and 12 obsidian.
```

Real input format:
```text
Blueprint 1: Each ore robot costs 4 ore. Each clay robot costs 2 ore. Each obsidian robot costs 3 ore and 14 clay. Each geode robot costs 2 ore and 7 obsidian.
Blueprint 2: Each ore robot costs 2 ore. Each clay robot costs 3 ore. Each obsidian robot costs 3 ore and 8 clay. Each geode robot costs 3 ore and 12 obsidian.
```

You need to figure out which blueprint would maximize the number of opened geodes after **24 minutes** by figuring out which robots to build and when to build them.

Using blueprint 1 in the example above, the largest number of geodes oyu could open in 24 minutes is 9. One way to achieve that is:

```text
== Minute 1 ==
1 ore-collecting robot collects 1 ore; you now have 1 ore.

== Minute 2 ==
1 ore-collecting robot collects 1 ore; you now have 2 ore.

== Minute 3 ==
Spend 2 ore to start building a clay-collecting robot.
1 ore-collecting robot collects 1 ore; you now have 1 ore.
The new clay-collecting robot is ready; you now have 1 of them.

== Minute 4 ==
1 ore-collecting robot collects 1 ore; you now have 2 ore.
1 clay-collecting robot collects 1 clay; you now have 1 clay.

== Minute 5 ==
Spend 2 ore to start building a clay-collecting robot.
1 ore-collecting robot collects 1 ore; you now have 1 ore.
1 clay-collecting robot collects 1 clay; you now have 2 clay.
The new clay-collecting robot is ready; you now have 2 of them.

== Minute 6 ==
1 ore-collecting robot collects 1 ore; you now have 2 ore.
2 clay-collecting robots collect 2 clay; you now have 4 clay.

== Minute 7 ==
Spend 2 ore to start building a clay-collecting robot.
1 ore-collecting robot collects 1 ore; you now have 1 ore.
2 clay-collecting robots collect 2 clay; you now have 6 clay.
The new clay-collecting robot is ready; you now have 3 of them.

== Minute 8 ==
1 ore-collecting robot collects 1 ore; you now have 2 ore.
3 clay-collecting robots collect 3 clay; you now have 9 clay.

== Minute 9 ==
1 ore-collecting robot collects 1 ore; you now have 3 ore.
3 clay-collecting robots collect 3 clay; you now have 12 clay.

== Minute 10 ==
1 ore-collecting robot collects 1 ore; you now have 4 ore.
3 clay-collecting robots collect 3 clay; you now have 15 clay.

== Minute 11 ==
Spend 3 ore and 14 clay to start building an obsidian-collecting robot.
1 ore-collecting robot collects 1 ore; you now have 2 ore.
3 clay-collecting robots collect 3 clay; you now have 4 clay.
The new obsidian-collecting robot is ready; you now have 1 of them.

== Minute 12 ==
Spend 2 ore to start building a clay-collecting robot.
1 ore-collecting robot collects 1 ore; you now have 1 ore.
3 clay-collecting robots collect 3 clay; you now have 7 clay.
1 obsidian-collecting robot collects 1 obsidian; you now have 1 obsidian.
The new clay-collecting robot is ready; you now have 4 of them.

== Minute 13 ==
1 ore-collecting robot collects 1 ore; you now have 2 ore.
4 clay-collecting robots collect 4 clay; you now have 11 clay.
1 obsidian-collecting robot collects 1 obsidian; you now have 2 obsidian.

== Minute 14 ==
1 ore-collecting robot collects 1 ore; you now have 3 ore.
4 clay-collecting robots collect 4 clay; you now have 15 clay.
1 obsidian-collecting robot collects 1 obsidian; you now have 3 obsidian.

== Minute 15 ==
Spend 3 ore and 14 clay to start building an obsidian-collecting robot.
1 ore-collecting robot collects 1 ore; you now have 1 ore.
4 clay-collecting robots collect 4 clay; you now have 5 clay.
1 obsidian-collecting robot collects 1 obsidian; you now have 4 obsidian.
The new obsidian-collecting robot is ready; you now have 2 of them.

== Minute 16 ==
1 ore-collecting robot collects 1 ore; you now have 2 ore.
4 clay-collecting robots collect 4 clay; you now have 9 clay.
2 obsidian-collecting robots collect 2 obsidian; you now have 6 obsidian.

== Minute 17 ==
1 ore-collecting robot collects 1 ore; you now have 3 ore.
4 clay-collecting robots collect 4 clay; you now have 13 clay.
2 obsidian-collecting robots collect 2 obsidian; you now have 8 obsidian.

== Minute 18 ==
Spend 2 ore and 7 obsidian to start building a geode-cracking robot.
1 ore-collecting robot collects 1 ore; you now have 2 ore.
4 clay-collecting robots collect 4 clay; you now have 17 clay.
2 obsidian-collecting robots collect 2 obsidian; you now have 3 obsidian.
The new geode-cracking robot is ready; you now have 1 of them.

== Minute 19 ==
1 ore-collecting robot collects 1 ore; you now have 3 ore.
4 clay-collecting robots collect 4 clay; you now have 21 clay.
2 obsidian-collecting robots collect 2 obsidian; you now have 5 obsidian.
1 geode-cracking robot cracks 1 geode; you now have 1 open geode.

== Minute 20 ==
1 ore-collecting robot collects 1 ore; you now have 4 ore.
4 clay-collecting robots collect 4 clay; you now have 25 clay.
2 obsidian-collecting robots collect 2 obsidian; you now have 7 obsidian.
1 geode-cracking robot cracks 1 geode; you now have 2 open geodes.

== Minute 21 ==
Spend 2 ore and 7 obsidian to start building a geode-cracking robot.
1 ore-collecting robot collects 1 ore; you now have 3 ore.
4 clay-collecting robots collect 4 clay; you now have 29 clay.
2 obsidian-collecting robots collect 2 obsidian; you now have 2 obsidian.
1 geode-cracking robot cracks 1 geode; you now have 3 open geodes.
The new geode-cracking robot is ready; you now have 2 of them.

== Minute 22 ==
1 ore-collecting robot collects 1 ore; you now have 4 ore.
4 clay-collecting robots collect 4 clay; you now have 33 clay.
2 obsidian-collecting robots collect 2 obsidian; you now have 4 obsidian.
2 geode-cracking robots crack 2 geodes; you now have 5 open geodes.

== Minute 23 ==
1 ore-collecting robot collects 1 ore; you now have 5 ore.
4 clay-collecting robots collect 4 clay; you now have 37 clay.
2 obsidian-collecting robots collect 2 obsidian; you now have 6 obsidian.
2 geode-cracking robots crack 2 geodes; you now have 7 open geodes.

== Minute 24 ==
1 ore-collecting robot collects 1 ore; you now have 6 ore.
4 clay-collecting robots collect 4 clay; you now have 41 clay.
2 obsidian-collecting robots collect 2 obsidian; you now have 8 obsidian.
2 geode-cracking robots crack 2 geodes; you now have 9 open geodes.
```

However, by using blueprint 2, the largest number of geodes you could open in 24 minutes is 12.

Determine the **quality level** of each blueprint by **multiplying that blueprint's ID number (starting in 1) with the largest number of geodes that can be opened in 24 minutes using that blueprint.

In the example, is $ 1 * 9 + 2 * 12 = 33 $. A general formula for $n$ blueprints is:
$$
\displaystyle\sum_{id=1}^{n} id * \max_{id}(\rm Geodes\ recollected\ in\ 24\ minutes)
$$

### Solution Part 1

This problem consists of several parts to solve. As usual, the parsing of the input. From where we read the information of each blueprint.

Then we have to simulate the adquisition of resources according to the robots that we have.
We start with one ore robot.
With the resources available we have to choose what robots we create.
```php
foreach ($robots as $robot)
```

We can model the problem like a flow network and its associated directed Graph. The vertex can be the resources, and we create edges when we create the robots. The **sink** vertex is the geodes created.

PART 1 (inspiration): https://github.com/hyper-neutrino/advent-of-code/blob/main/2022/day19p1.py
PART 2 (inspiration): https://www.reddit.com/r/adventofcode/comments/zpihwi/comment/j0tls7a/?utm_source=share&utm_medium=web2x&context=3
```python
import fileinput, re

r = 1
o = [ ( t - 1 ) * t // 2 for t in range( 32 + 1 ) ]
p = [ list( map( int, re.findall( "-?\d+", l ) ) ) for l in fileinput.input() ]
for n, a, b, c, d, e, f in p[ : 3 ]:
    m = 0
    mi, mj, mk = max( a, b, c, e ), d, f
    def dfs( t, g,         # t:time remaining, g:goal robot type
             i, j, k, l,   # i:ore, j:clay, k:obsidian, l:geode robots
             w, x, y, z ): # w:ore, x:clay, y:obsidian, z:geode available
        global m
        if ( g == 0 and i >= mi or
             g == 1 and j >= mj or
             g == 2 and ( k >= mk or j == 0 ) or
             g == 3 and k == 0 or
             z + l * t + o[ t ] <= m ):
            return
        while t:
            if g == 0 and w >= a:
                for g in range( 4 ):
                    dfs( t - 1, g, i + 1, j, k, l, w - a + i, x + j, y + k, z + l )
                return
            elif g == 1 and w >= b:
                for g in range( 4 ):
                    dfs( t - 1, g, i, j + 1, k, l, w - b + i, x + j, y + k, z + l )
                return
            elif g == 2 and w >= c and x >= d:
                for g in range( 4 ):
                    dfs( t - 1, g, i, j, k + 1, l, w - c + i, x - d + j, y + k, z + l )
                return
            elif g == 3 and w >= e and y >= f:
                for g in range( 4 ):
                    dfs( t - 1, g, i, j, k, l + 1, w - e + i, x + j, y - f + k, z + l )
                return
            t, w, x, y, z = t - 1, w + i, x + j, y + k, z + l
        m = max( m, z )
    for g in range( 4 ):
        dfs( 32, g, 1, 0, 0, 0, 0, 0, 0, 0 )
    r *= m
print( r )

```