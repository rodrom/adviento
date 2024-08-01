# 20 - Grove Positioning System

This problem requires to read a file with integers and move each number his value in positions.
You shold consider the list as circular.
You should follow the initial order.
You shold watch the 1000th position, 2000th position and 3000th.

I was a bit annoyed with the fact that nowhere in the puzzle description or the sample data did it say that an item
being moved should be removed from the list before determining its final location.

Sum Grove coordinates: 3473

### Part 2

First, you need to apply the decryption key, 811589153.
Multiply each number by the decryption key before you begin; this will produce the actual list of numbers to mix.

Second, you need to mix the list of numbers ten times. The order in which the numbers are mixed does not change during mixing; the numbers are still moved in the order they appeared in the original, pre-mixed list. (So, if -3 appears fourth in the original list of numbers to mix, -3 will be the fourth number to move during each round of mixing.)

Sum Grove coordinates: 7496649006261