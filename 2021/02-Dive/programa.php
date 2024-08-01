<?php

/* The submarine takes instructions like:
 * <direction> <number_of_moves>
 * moves: {forward down up}
 * The submarine modifies his position
 * in `depth` with up (-1) and `down` (+1)
 * The horizontal position advance with `forward` (+1)
 * The submarine have a planned course (input)
 * The starting point is (0,0).
 * At the end, the result is the multiplication o both coordinates.
 */

class Submarine
{
    public int $depth;
    public int $horizontal;

    public function __construct(int $depth = 0, int $horizontal = 0)
    {
        $this->depth = $depth;
        $this->horizontal = $horizontal;
    }

    public function move(string $order): void
    {
        if (preg_match("/^(forward|down|up) (\d+)$/", $order, $results)) {
            $move = $results[1];
            $quantity = intval($results[2]);
            match ($move) {
                "forward" => $this->horizontal += $quantity,
                "down" => $this->depth += $quantity,
                "up" => $this->depth -= $quantity,
            };
        }
    }
}

$submarine = new Submarine();
$input = file("input.txt", FILE_IGNORE_NEW_LINES);
array_map($submarine->move(...), $input);
echo "Depth: $submarine->depth | Horizontal: $submarine->horizontal\n";
echo "Multiply: " . $submarine->depth * $submarine->horizontal . PHP_EOL;