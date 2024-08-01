# Day 6: Tuning Trouble

### Part 1

One of the Elves gives a **device** with a **communication system**. But is broken. They think you can fix it, according
to your experience in dealing with **signal-based systems**.

The device emits a few colorful sparks.

To be able to commuicate with the Elves, the device need to **lock on to their signal**. The signal is a series of
random characters that the device receives one at a time.

To fix the com. system, you need to add a subroutine to the device that detects a *start-of-packet marker* in the datastream.
In the protocol being used by the Elves, the start of the packet is a
sequence of **four characters that are all different**.

The device will send your subroutine a datastream buffer (input);
your subroutine needs to identify the first position where the four most recently received characters were all different.
Specifically, it needs to report the number of characters from the beginning of the buffer to the end of the first such
four-character marker.

For example, when you receive this datastream buffer:
```text
mjqjpqmgbljsphdztnvjfqwrcgsmlb
```
There is not 4 characters different together until `m`, in the seventh place. `jpqm`.

The subroutine, should return `7`.

#### Solution

The first idea to mind, is create a class `Device` that analyze each input in a method called `detectStartPacket`.

This method returns the index of the last character of the first appearance of the pattern or throw an exception.

The algorithm to detect the start package could be:
```
for(c = size; c <= last_character; c++) {
    slice = slice(input,c-size,c);
    if (characters_in_string_unique(slice))
        return c
}

fn characters_in_string_unique(string) {
    if (repeated characters in string)
        return false
    return true
}
```

### Part 2

The device needs to detect also start-of-message-marker, the difference
is that the size of this marker is 14 characters.
