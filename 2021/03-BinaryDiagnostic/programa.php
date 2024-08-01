<?php

declare(strict_types=1);

// read file
$input = file("input.txt", FILE_IGNORE_NEW_LINES);
// Number of bits of each word
$bits = strlen($input[0]);
// Should be enough to count the number of 1 in each position
// If 1 is the most common bit, the bit for gamma is 1 and for epsilon 0
// 1 is the most common bit just if the sum of 1 bits is bigger than half of the lines
// So, it is possible to walk the array and create
$countOnes = array_reduce(
    $input,
    function (array $carry, string $item): array
    {
        $word = str_split($item);
        foreach ($word as $pos => $bit) {
            if ($bit === "1") {
                $carry[$pos]++;
            }
        }
        return $carry;
    },
    array_fill(0, $bits, 0)
);

$gamma = "";
$epsilon = "";
//var_dump($countOnes);
$threshold = count($input) / 2;
foreach ($countOnes as $pos => $ones) {
    if ($ones > $threshold) {
        $gamma .= "1";
        $epsilon .= "0";
    } else {
        $gamma .= "0";
        $epsilon .= "1";
    }
}
echo "gamma: $gamma | epsilon: $epsilon\n";
$result = intval($gamma, 2) * intval($epsilon, 2);
echo "Result: $result\n";