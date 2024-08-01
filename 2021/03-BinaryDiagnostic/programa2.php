<?php

declare(strict_types=1);

// In this problem, we need to run for each bit a filtering of entries.
// To get the *oxygen generator rating* (ogr),
// we keep the entries the most common bit (mcb) of each bit.
// To get the *CO2 scrubber rating* (co2sr),
// we keep entries with the least common bit (lcb) of each bit.
// So, a possible solution for ogr:
/*
 * For i in size(word):
 *   mcb = getMcb(input, i)
 *   input = filter(input, i, bit)
 *   if (size(input) === 1):
 *      return input[0]
*/
// For co2sr, the solution will be the same, using getLcb function, which is just the contrary bit of mcb.
// If 1 is the most common bit, the bit for gamma is 1 and for epsilon 0
// 1 is the most common bit just if the sum of 1 bits is bigger than half of the lines

function getMcb(array $words, int $pos): int
{
    $count = array_reduce(
        $words,
        function (int $carry, string $word) use ($pos): int
        {
            return $word[$pos] === '1' ? $carry + 1 : $carry - 1;
        },
        0
    );
    
    return $count >= 0 ? 1 : 0;
}

function filter(array $input, int $pos, int $bit): array
{
    return array_filter(
        $input,
        function (string $word) use ($pos, $bit): bool
        {
            $currentBit = intval($word[$pos]);
            return  $currentBit === $bit;
        });
}

$input = file("input.txt", FILE_IGNORE_NEW_LINES);
// Number of bits of each word
$bits = strlen($input[0]);

$inputOgr = $input;
$ogr = 0;
$inputCo2sr = $input;
$co2sr = 0;

for ($i = 0; $i < $bits; $i++) {
    $mcb = getMcb($inputOgr, $i);
    $inputOgr = filter($inputOgr, $i, $mcb);
    if (count($inputOgr) === 1) {
        $ogr = intval(array_pop($inputOgr), 2);
        break;
    }
}

for ($i = 0; $i < $bits; $i++) {
    $lcb = getMcb($inputCo2sr, $i) ? 0 : 1;
    $inputCo2sr = filter($inputCo2sr, $i, $lcb);
    if (count($inputCo2sr) === 1) {
        $co2sr = intval(array_pop($inputCo2sr), 2);
        break;
    }
}

echo "ogr = $ogr | co2sr = $co2sr\n";
echo "result = " . $ogr * $co2sr . "\n";
