# Day 13: Distress Signal

You receive a *distress signal*, the *packets* from the distress signal got decoded *out of order*. (input)

The input looks like this, each pairs of packets is separated.

```text
[1,1,3,1,1]
[1,1,5,1,1]

[[1],[2,3,4]]
[[1],4]

[9]
[[8,7,6]]

[[4,4],4,4]
[[4,4],4,4,4]

[7,7,7,7]
[7,7,7]

[]
[3]

[[[]]]
[[]]

[1,[2,[3,[4,[5,6,7]]]],8,9]
[1,[2,[3,[4,[5,6,0]]]],8,9]
```

Packet data consists of *lists* and *integers*.
Each list starts with `[`, ends with `]`, and contains 0 or more comma-separeted values (either integers or other lists).
Each packet is always a list and appears on its own line.

When comparing two values, the first value is called *left* and the second *right*. Then:
- If **both values are integers** =>
   - *lower integer should come first*. right order.
   - *higher integer come first*. wrong order.
   - *equals*. continue checking the next part of the input.
- If **both values are lists**,
  - *compare the first value of each list*,
  - *then the second value*, and so on.
  - ... => 
    - *if the left list runs out of items first* right order
    - *if the right list...* wrong order
    - *same number of items, and comparison doesnt make a decision* => continue checking
- If **exactly one value is an integer**, convert the integer to a list and make comparisons.

With this rules, it should calc what pairs in the example are in the right order.

Each pair has an index starting in 1 for the first pair. The sum of the index of correct pairs is the output of the problem.

### Solution

The first idea to solve this problem is create a parser to read the data. Each pair can be a structure/class:
```php
class PairOfPackets
{
    public Packet $left;
    public Packet $right;
    public int $index;

    static function fromString(...)
}

class Packet
{
    public int|array $payload;
    public int $level;

    static function fromString(...)
}
```

Using json_decode is pretty easy to get the info from the lists in string format.

With two lists in array format, we should compare them to know wether is out of order, or not.
There are iterators that maybe make easier compare this thing.

```php
class PairOfPackets {
    ...
    function inOrder(left, right): bool
    {
        match (comparisonType(left,right)) {
            both_integers => 
                left === right 
                ? null
                : left < right
            both_lists => compareLists(left, right)
            mixed_types_left_int => compareLists([left], right)
            mixed_types_right_int => compareLists(left, [right])
        }
    }
}


```