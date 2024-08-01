# Day 5: Supply Stacks

Supplies are stored in stacks of marked **crates**, which need to be rearranged.

The ship has a *giant cargo crane* capable of moving crates between stacks.
To avoid crates fall over or get crushed, the crane operator must follow carefully-planned steps.
After the crates are rearranged, the desired crates will be at the top of each stack.

The Elves want to know *which* crate will end up where.

They have a drawing of the starting stacks of crates and the rearrangement procedure (input).

For example:
```text
    [D]    
[N] [C]    
[Z] [M] [P]
 1   2   3 

move 1 from 2 to 1
move 3 from 1 to 3
move 2 from 2 to 1
move 1 from 1 to 2
```

After the first instruction `move 1 from 2 to 1`, `[D]` is moved from stack 2 to 1. And the result is:
```text
[D]        
[N] [C]    
[Z] [M] [P]
 1   2   3 
```
So, the instructions follow this: `move <number_of_crates> from <origin_stack> to <destination_stack>`
In our example, after following all the orders, the crates are ordered in this way:
```text
        [Z]
        [N]
        [D]
[C] [M] [P]
 1   2   3
```

The elves just need to know *which crates will end up on top of each stack*, in the example: `CMZ`.

### Solution Part 1.

The first idea to mind, is to encapsulate the info of the crates in stacks, array in PHP can work as stacks.
These stacks can be saved in a class named cargo, or even another array with the stacks inside numbered.
These stacks only need to stacking characters.

The crane operations can work with the stacks, one method should receive the origin and destination cranes identifier and the number of cranes moved.

At the end, after all operations are executed, there should be a method that read all the top cranes of each stack.

#### Parsing the data

For the parser. It should separete the initial cargo info from the operations.
This could be done splitting the file in '\n\n'.

The problem from the cargo info, is that the info is from bottom to top.
It is possible read from last to first each line of cargo.
Last line includes the number of stacks. So, we can initialise the cargo structure with empty stacks (empty arrays in PHP).

Then, we read each line to identified the crates and push to the corresponding stack.
Considering that they are separated by one space, and every stack info occupies 3 characters, the problem is that stacks with air on the top (no crates at that level), is display with spaces as well. So, there are two options: `[x]` or `   ` for each stack.
In case that there is a crate in the stack, we have read the character and push it to the stack. In the other case, we do nothing.
We advance to the next stack until the last, and then we pop another line (we go up another level)

To read the operations, we can read each line from top to bottom and move the correponding cranes.
Each instruction could be save in an array that then is passed to the crane operator.
After executing operations we need to read the top crates identifiers.

## Part 2

The model of the crane is able to move several crates at the same time. So, instead of popping each crate, it takes the number of crates and moves together to the destination stack. `array_splice` allows to use as offset negative numbers that point to the
last elements of the array, changing the original array (`array_slice` doesn't modify origin array).