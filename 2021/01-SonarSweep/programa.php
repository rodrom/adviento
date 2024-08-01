<?php

$input = file("input.txt", FILE_IGNORE_NEW_LINES);

$last = intval(array_shift($input));
$increases = 0;
foreach ($input as $k => $line) {
    $current = intval($line);
    if ($last < $current) {
        $increases++;
    }
    $last = $current;
}

echo "Increases measurments: $increases\n";