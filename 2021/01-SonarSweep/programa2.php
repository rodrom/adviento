<?php

$input = file("input.txt", FILE_IGNORE_NEW_LINES);

// we need to compare from window measurements until end of input.
// we compare the A = sum (current - window -  1..current - 1) to
// B = sum (current - window..current). The math point is that:
// A = a + b + c
// B =     b + c + d
// A - B = a + b + c - b - c - d = a - d
// A < B => a < d
// A > B => a > d
// So, it's equivalent to compare input[current - window - 1] to input[current]
$last = intval($input[0]);
$window = 3;
$increases = 0;
for ($i = $window; $i < count($input); $i++) {
    $current = intval($input[$i]);
    if ($last < $current) {
        $increases++;
    }
    $last = intval($input[$i - $window + 1]);
}

echo "Increases measurments: $increases\n";