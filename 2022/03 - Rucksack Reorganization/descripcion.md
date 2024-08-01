# Rucksack Reorganization

Each rucksack has two large **compartments**.
All **items** of a given **type** are meant to go into exactly one of the two **compartments**. (This means that two elements of the same type cannot go in different compartments in the same rucksack, but different types of items can go in the compartments, and different items types can go in different rucksacks.)
The elf that did the packing failed to follow this rule by exactly one item type per rucksack.

The input is the list of all items currently in each rucksack. Each type is identified by a lowercase or uppercase character, so `A` and `a` are different item types.

Each line of the input is the list of items from one rucksack.
A given rucksack always has the same number of items in each of its compartments, so first half represent the items in the first compartment, and the second half, the second compartment.

To help prioritize item rearrangement, every item type can be converted to a **priority**:

- Lower case item types `a-z` have priorities from `1..26`.
- Uppercase item types `A-Z` have priorites from `27..54`.

So, for each type element wrong placed in each rucksack, there is a value, and the sum of all gives the final output.

## Parte 2

The Elves are divided in groups of three identified by an item type badge, e.g. `B`. This means that they have in their rucksacks
these elements. And at most two of the Elves will be carrying any other item type.

The problem is that the badges are not updated. The only way to know which item type is the right one is finding 
**the common between all three Elves** in each group.

Every set of three lines in the input corresponds to each group's badges, but each group can have a different badge item type.

The sum of each group of the prioritize common element type value is the result of the exercise.