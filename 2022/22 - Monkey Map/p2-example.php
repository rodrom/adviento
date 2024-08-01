<?php

declare(strict_types=1);

class Maze
{
    public function __construct(
    public array $map,
    public array $path,
    public array $moves,
    public array $directions,
    public int $c,
    public int $r,
    public int $d,
    public int $size,
    ) { }

    public static function readInput(string $input)//: static
    {
        $lines = explode ("\n", $input);
        $rows = count($lines) - 3;
        $columns = max(array_map(strlen(...), array_slice($lines, 0, $rows)));
        $r = 1;
        $size = strlen(trim($lines[0]));
        $map = [0 => array_fill(1, $columns, 0)];
        while (preg_match('/^(\s*)([#.]+)$/', $lines[$r - 1], $matches)) {
            $outOfBoundsLeft = strlen($matches[1]);
            $beginCorridor = $outOfBoundsLeft + 1;
            $endCorridor = strlen($matches[0]);
            $outOfBoundsRight = $endCorridor + 1;
            
            $map[$r] = 
                [$outOfBoundsLeft => $endCorridor] 
                + array_combine(range($beginCorridor, $endCorridor), str_split($matches[2]))
                + [$outOfBoundsRight => $beginCorridor];
            $r++;
        }
        // Get vertical warps
        $map[$rows + 1] = array_fill(1, $columns, $rows);
        for($r = 1; $r <= $rows; $r++) {
            foreach ($map[$r] as $c => $v) {
                if (is_string($v)) {
                    $map[0][$c] = max($map[0][$c], $r);
                    $map[$rows + 1][$c] = min($map[$rows + 1][$c], $r);
                }
            }
        }

        // Get Password
        $password = $lines[count($lines) - 2];
        preg_match_all('/(\d+)/', $password, $moves);
        $moves = array_map(intval(...), $moves[0]);
        preg_match_all('/([LR])/', $password, $directions);

        return new static($map, [], $moves, $directions[0], array_key_first($map[1]) + 1, 1, 0, $size);
    }

    public function solve(): array
    {
        while ($m = array_shift($this->moves)) {
            while ($m > 0 ) {
                [$nr, $nc, $tile, $newD] = $this->nextPosition();
                if ($tile === '#') {
                    break;
                }
                $this->r = $nr;
                $this->c = $nc;
                $this->d = $newD;
                $m--;
            }
            $turn = array_shift($this->directions);
            if ($turn === 'L') {
                $this->d = ($this->d - 1) % 4;
                $this->d = ($this->d >= 0) ? $this->d : 3;
            } elseif ($turn === 'R') {
                $this->d = ($this->d + 1) % 4;
            }
        }
        return [$this->r, $this->c, $this->d];
    }

    public function nextPosition(): array
    {
        [$nr, $nc] = match ($this->d) {
            0 => [$this->r, $this->c + 1],
            1 => [$this->r + 1, $this->c],
            2 => [$this->r, $this->c - 1],
            3 => [$this->r - 1, $this->c],
        };

        // Check warps
        // EAST -> WEST WARP || WEST -> EAST WARP || NORMAL MOVE
        $tile = $this->map[$nr][$nc] ?? "VERTICAL WARP";

        // CHECK VERTICAL WARP (EVEN WHEN THERE IS A CORNER OUT OF BOUNDS (WITH HORIZONTAL WARP INFO NOT RELEVANT)
        // SOUTH
        if ($this->d === 1 && (is_array($tile) || $tile === 'VERTICAL WARP')) {
            $tile = $this->map[array_key_last($this->map)][$this->c];
        // NORTH
        } elseif ($this->d === 3 && (is_array($tile) || $tile === 'VERTICAL WARP')) {
            $tile = $this->map[0][$this->c];
        }

        return match (true) {
            $tile === '.' => [$nr, $nc, $tile, $this->d],
            $tile === '#' => [$this->r, $this->c, $tile, $this->d],
            is_array($tile) => $this->warp($this->r, $this->c, $tile),
        };

    }

    public function warp(int $cr, int $cc, array $warp): array
    {
        return match ($this->map[$warp['r']][$warp['c']]) {
            '#' => [$cr, $cc, '#', $this->d],
            '.' => [$warp['r'], $warp['c'], '.', $warp['d']],
        };
    }

    public function readjustWarpsToCube(): void
    {
        for($i = 1, $j = $this->size; $i <= $this->size; $i++, $j--) {
            // Vertical WARP SIDE 1 d:N -> SIDE 2 d: S
            // Vertical WARP SIDE 2 d:N -> Side 1 d: S
            $this->map[0][2 * $this->size + $i] = ['r' => $this->size + 1, 'c' => $j, 'd' => 1];
            $this->map[0][$i] = ['r' => 1, 'c' => 2 * $this->size + $j, 'd' => 1];
            // Horizontal WARP SIDE 1 d:E -> SIDE 6 d: W
            // Horizontal WARP SIDE 6 d:E -> Side 1 d: W
            $this->map[$i][3 * $this->size + 1] = ['r' => 3 * $this->size - ($i - 1), 'c' => 4 * $this->size, 'd' => 2];
            $this->map[2 * $this->size + $i][4 * $this->size + 1] = ['r' => $j, 'c' => 3 * $this->size, 'd' => 2];
            // Horizontal WARP SIDE 1 d:W -> SIDE 3 d: S
            // Vertical WARP SIDE 3 d:N -> Side 1 d: E
            $this->map[$i][2 * $this->size] = ['r' => $this->size + 1, 'c' => $this->size + $i, 'd' => 1];
            $this->map[0][$this->size + $i] = ['r' => $i, 'c' => 2 * $this->size + 1, 'd' => 0];

            // Vertical WARP SIDE 2 d: S -> SIDE 5 d: N
            // Vertical WARP SIDE 5 d: S -> Side 2 d: N
            $this->map[3 * $this->size + 1][$i] = ['r' => 3 * $this->size, 'c' => 3 * $this->size - ($i - 1), 'd' => 3];
            $this->map[3 * $this->size + 1][2 * $this->size + $i] = ['r' => 2 * $this->size, 'c' => $j, 'd' => 3];
            // Horizontal WARP SIDE 2 d: W -> SIDE 6 d: N
            // Vertical WARP SIDE 6 d: S -> Side 2 d: E
            $this->map[$this->size + $i][0] = ['r' => 3 * $this->size, 'c' => 4 * $this->size - ($i - 1), 'd' => 3];
            $this->map[3 * $this->size + 1][3 * $this->size + $i] = ['r' => 2 * $this->size - ($i - 1), 'c' => 1, 'd' => 0];

            // Vertical WARP SIDE 3 d: S -> SIDE 5 d: E
            // Horizontal WARP SIDE 5 d: W -> Side 3 d: N
            $this->map[3 * $this->size + 1][$this->size + $i] = ['r' => 3 * $this->size - ($i - 1), 'c' => 2 * $this->size + 1, 'd' => 0];
            $this->map[2 * $this->size + $i][2 * $this->size] = ['r' => 2 * $this->size, 'c' => 2 * $this->size - ($i - 1), 'd' => 3];

            // Horizontal WARP SIDE 4 d:E -> SIDE 6 d: S
            // Vertical WARP SIDE 6 d:N -> Side 4 d: W
            $this->map[$this->size + $i][3 * $this->size + 1] = ['r' => 2 * $this->size + 1, 'c' => 4 * $this->size - ($i - 1), 'd' => 1];
            $this->map[0][3 * $this->size + $i] = ['r' => 2 * $this->size - ($i - 1), 'c' => 3 * $this->size, 'd' => 2];
        }
    }

    public function __toString()
    {
        $output = "(r: $this->r, c: $this->c, d: $this->d)\n";
        $output .= "TOP WARPS: " . json_encode($this->map[0]) . PHP_EOL;
        $output .= "BOTTOM WS: " . json_encode($this->map[array_key_last($this->map)]) . PHP_EOL;
        foreach(array_slice($this->map, 1, count($this->map) - 2, true) as $r => $row) {
            $output .= sprintf("%03d", $row[array_key_first($row)]);
            $output .= str_repeat(' ', array_key_first($row));
            foreach ($row as $c => $v) {
                $output .= ($r === $this->r && $this->c === $c) ?  strval($this->d) : (is_string($row[$c]) ? $row[$c] : ' ');
            }
            $output .= sprintf("%03d\n", $row[array_key_last($row)]);
        };
        return $output;
    }
}

$maze = Maze::readInput(file_get_contents($argv[1]));
echo $maze;
$maze->readjustWarpsToCube();
[$r, $c, $d] = $maze->solve();
echo json_encode([$r, $c, $d]) . PHP_EOL;
echo "SOLUTION: \$r * 1000 + \$c * 4 + \$d = " . $r * 1000 + $c * 4 + $d . PHP_EOL;