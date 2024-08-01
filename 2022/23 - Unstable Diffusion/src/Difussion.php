<?php

declare(strict_types=1);
namespace Rodrom\Advent202223;

class Difussion
{
    public const NORTH = 0;
    public const SOUTH = 1;
    public const WEST = 2;
    public const EAST = 3;

    public function __construct(
        public array $map = [],
        public int $dimensionX = 0,
        public int $dimensionY = 0,
        public array $horizontal = [],
        public array $vertical = [],
        public int $elves = 0,
    )
    { }

    public static function readInput (string $input): static
    {

        $lines = explode("\n", $input);
        $map = [];
        $horizontal = [];
        $dimension = strlen($lines[0]);
        $vertical = array_map(fn ($x) => 2**($x % 32), range(0, $dimension - 1));
        $elves = substr_count($input, '#');
        foreach ($lines as $k => $line) {
            if ($line === "") {
                continue;
            }
            $map[$k] = str_split($line);
            // $words = str_split($line, 32);
            $reverse = strrev($line);
            $rwords = str_split($reverse, 32);
            $horizontal[$k] = array_map(fn ($word) => bindec(
                implode(array_map(fn ($x) => $x === '#' ? 1 : 0, str_split(strrev($word))))
            ), $rwords);
        }
        
        return new static($map, $dimension, count($horizontal), $horizontal, $vertical, $elves);
    }

    public function play(int $rounds): array
    {
        for ($i = 0; $i < $rounds; $i++) {
            $old = $this->__toString();
            echo "round = $i | X = $this->dimensionX | Y = $this->dimensionY\n";
            // $elvesStopped = true;
            $propose = $this->firstHalf($i % 4);
            // foreach ($propose as $y => $words) {
            //     foreach ($words as $w => $word) {
            //         foreach (range(0,3) as $d) {
            //             if ($word[$d] !== 0) {
            //                 $elvesStopped = false;
            //                 break 3;
            //             }
            //         }
            //     }
            // }
            // if ($elvesStopped) {
            //     break;
            // }
            $emptyTiles = $this->secondHalf($propose);
            $new = $this->__toString();
            if (strcmp($old, $new) === 0) {
                break;
            }
        }

        return [ $emptyTiles, $i + 1 ];
    }

    public function firstHalf(int $firstDirection): array
    {
        
        $directions = [
            self::NORTH => $this->adjNorth(...),
            self::SOUTH => $this->adjSouth(...),
            self::WEST => $this->adjWest(...),
            self::EAST => $this->adjEast(...)
        ];
        $orderDirections = array_map(fn ($d) => $d % 4, range($firstDirection, $firstDirection + 3));
        $propose = [];
        // This loop can be improve in readability and optimization.
        // A lot of unnecessary operations are done.
        foreach ($this->horizontal as $y => $words) {
            $propose[$y] = [];
            $x = 0;
            foreach ($words as $w => $word) {
                $propose[$y][$w] = ['isolated' => 0, self::NORTH => 0, self::SOUTH => 0, self::WEST => 0, self::EAST => 0];
                $p = &$propose[$y][$w];
                $endWord = min(($w + 1) * 32, $this->dimensionX);
                for ($x = $w * 32; $x < $endWord; $x++) {
                    $mask = $this->vertical[$x];
                    if ($this->adjEmpty($x, $y)) {
                        $p['isolated'] += ($word & $mask);
                        continue;
                    }
                    $still = true;
                    foreach ($orderDirections as $d) {
                        if (($directions[$d])($x,$y) === 0) {
                            $p[$d] += ($words[$w] & $mask);
                            $still = false;
                            break;
                        }
                    }
                    if ($still) {
                        $p['isolated'] += ($word & $mask);
                    }
                }
            }
        }
        return $propose;
    }

    public function secondHalf(array $p): int
    {
        // Check all proposed moves at the borders of the map and save them on a queue.
        $q = $this->growingMapQueue($p);
        // Check internal updates
        // horizontal
        $h = $this->internalHorizontalMoves($p);
        $v = $this->internalVerticalMoves($h, $p);
        // Now we can update the map.
        $this->updateElvesPosition($v, $p);
        $this->updateBorders($q);
        return $this->emptyTiles();
    }

    public function adjEmpty(int $x, int $y): bool
    {
        return !$this->adjNorth($x, $y) 
            &&  !$this->adjSouth($x, $y)
            && !$this->adjEast($x, $y)
            && !$this->adjWest($x, $y);
    }

    public function adjNorth(int $x, int $y): int
    {
        // North border
        if ($y === 0) {
            return 0;
        }
        return ($this->horizontal[$y - 1][intdiv($x, 32)] & (($this->vertical[$x - 1] ?? 0) + $this->vertical[$x] + ($this->vertical[$x + 1] ?? 0)));
    }

    public function adjSouth(int $x, int $y): int
    {
        // South border
        if ($y === $this->dimensionY - 1) {
            return 0;
        }
        $r = $x % 32;
        return ($this->horizontal[$y + 1][intdiv($x, 32)] & (($this->vertical[$r - 1] ?? 0) + $this->vertical[$r] + ($this->vertical[$r + 1] ?? 0)));        
    }

    public function adjEast(int $x, int $y): int
    {
        // East border
        if ($x === 0) {
            return 0;
        }
        $wAdj = intdiv($x - 1, 32);
        return (($this->horizontal[$y - 1][$wAdj] ?? 0) & $this->vertical[$x - 1])
            | ($this->horizontal[$y][$wAdj] & $this->vertical[$x - 1])
            | (($this->horizontal[$y + 1][$wAdj] ?? 0) & $this->vertical[$x - 1]);
    }

    public function adjWest(int $x, int $y): int
    {
        // East border
        if ($x === $this->dimensionX - 1) {
            return 0;
        }
        $wAdj = intdiv($x + 1, 32);
        return (($this->horizontal[$y - 1][$wAdj] ?? 0) & $this->vertical[$x + 1])
            | ($this->horizontal[$y][$wAdj] & $this->vertical[$x + 1])
            | (($this->horizontal[$y + 1][$wAdj] ?? 0) & $this->vertical[$x + 1]);
    }

    public function growingMapQueue(array $p): array
    {
            // NORTH is at y[0].
            $q[self::NORTH] = array_map(fn ($w) => $w[self::NORTH], $p[0]);
            // SOUTH is at y[last]
            $q[self::SOUTH] = array_map(fn ($w) => $w[self::SOUTH], $p[$this->dimensionY - 1]);
            // WEST is at x[last] (our index goes from EAST to WEST horizontally)
            $word = intdiv($this->dimensionX - 1, 32);
            $mask = $this->vertical[$this->dimensionX - 1];
            $q[self::WEST] = array_map(fn ($y) => ($p[$y][$word][self::WEST]) & $mask, array_keys($p));
            // EAST is at x[0] (our index goes from EAST to WEST horizontally)
            $word = 0;
            $mask = $this->vertical[0];
            $q[self::EAST] = array_map(fn ($y) => ($p[$y][$word][self::EAST]) & $mask, array_keys($p));
    
            return $q;
    }

    public function updateBorders(array $q): void
    {
        // South
        if (count(array_filter($q[self::SOUTH], fn($w) => $w !== 0)) > 0) {
            $this->horizontal[$this->dimensionY] = $q[self::SOUTH];
            $this->dimensionY++;
        }

        // West
        // Check for w in limit of size word.
        $newWord = false;
        if (array_sum($q[self::WEST]) > 0) {
            $w = intdiv($this->dimensionX, 32);
            $newWord = ($w > intdiv($this->dimensionX - 1, 32));
            $mask = 2**($this->dimensionX % 32);
            $this->vertical[$this->dimensionX] = $mask;
            $this->dimensionX++;
            foreach ($q[self::WEST] as $y => $origin) {
                if ($newWord) {
                    $this->horizontal[$y][$w] = ($origin & (2 ** 31)) ? 1 : 0;
                } else {
                    $this->horizontal[$y][$w] |= ($origin !== 0) ? $mask : 0;
                }
            }
        }
        // North
        $northGrow = 0;
        if (count(array_filter($q[self::NORTH], fn($w) => $w !== 0)) > 0) {
            if ($newWord) {
                $q[self::NORTH][intdiv($this->dimensionX,32)] = 0;
                array_unshift($this->horizontal, $q[self::NORTH]);
            } else {
                array_unshift($this->horizontal, $q[self::NORTH]);
            }
            $this->dimensionY++;
            $northGrow = 1;
        }

        // East
        $e = array_filter($q[self::EAST], fn ($el) => $el !== 0);
        if (count ($e) > 0) {
            $W = intdiv($this->dimensionX, 32);
            $mask = 2**($this->dimensionX % 32);
            $this->vertical[$this->dimensionX] = $mask;
            $this->dimensionX++;
            if ($mask !== 1) {
                foreach ($this->horizontal as $y => $words) {
                    $this->horizontal[$y][$W] <<= 1;
                }
                // array_walk($this->horizontal, fn ($y) => $this->horizontal[$y][$w] = (0x00FF & ($this->horizontal[$y][$w] << 1)));
            }
            foreach ($this->horizontal as $y => &$words) {
                $w = $W;
                if ($mask === 1) {
                    $words[$w] = 0;
                }
                while ($w > 0) {
                    $words[$w] |= ($words[$w-1] & $this->vertical[$w * 32 - 1]) ? 1 : 0;
                    $words[$w - 1] = $words[$w - 1] << 1;
                    $w--;
                }
                $words[0] |= $q[self::EAST][$y - $northGrow] ?? 0;
            }
            unset ($words);
        }
    }

    public function internalHorizontalMoves(array &$p): array
    {
        $h = [];
        for ($y = 0; $y < $this->dimensionY; $y++) {
            for ($w = 0; $w < count($p[$y]); $w++) {
                // HOW TO CHECK COLLIDES FROM NORTH AND SOUTH AND KEEP THE ELVES IN THEIR ORIGINAL TILES?
                // First, we do an & between SOUTH PROPOSE y - 1 and NORT PROPOSE y + 1, the result are the tiles that collide
                $collisions = ($p[$y - 1][$w][self::SOUTH] ?? 0) & ($p[$y + 1][$w][self::NORTH] ?? 0);
                // Second, we update "still" tiles with the collisions
                if ($collisions !== 0) {
                    $p[$y - 1][$w]['isolated'] |= $collisions;
                    $p[$y + 1][$w]['isolated'] |= $collisions;
                    // Third, we negate "south" from y - 1 and "north" from y + 1 in that bits (NOR)
                    $p[$y - 1][$w][self::SOUTH] ^= $collisions;
                    $p[$y + 1][$w][self::NORTH] ^= $collisions;
                }
                $h[$y][$w] = ($p[$y - 1][$w][self::SOUTH] ?? 0) ^ $p[$y][$w]['isolated'] ^ ($p[$y + 1][$w][self::NORTH] ?? 0);
            }
        }
        return $h;
    }

    public function internalVerticalMoves(array $h, array &$p): array
    {
        foreach ($this->vertical as $x => $mask) {
            // Check collisions
            $w = intdiv($x, 32);
            $left = (($x % 32) === 0) ? $w - 1 : $w; // WRONG FOR x = 0
            $leftmask = $this->vertical[$x - 1] ?? 0;
            $right = (($x % 32) === 31) ? $w + 1 : $w;
            $rightmask = $this->vertical[$x + 1] ?? 0;
            foreach ($h as $y => $words) {
                $collisions = (($p[$y][$left][self::WEST]  ?? 0)& $leftmask) && (($p[$y][$right][self::EAST] ?? 0) & $rightmask);
                if ($collisions) {
                    // bit on "still" proposed on both sides of x
                    $p[$y][$left]['isolated'] |= $leftmask;
                    $p[$y][$right]['isolated'] |= $rightmask;
                    $h[$y][$left] |= $leftmask;
                    $h[$y][$right] |= $rightmask;
                    // bit off 
                    $p[$y][$left][self::WEST] ^= $leftmask;
                    $p[$y][$right][self::EAST] ^= $rightmask;
                } else {
                    $h[$y][$w] |= ((($p[$y][$left][self::WEST] ?? 0) & $leftmask)) || (($p[$y][$right][self::EAST] ?? 0) & $rightmask)
                        ? $mask : 0;
                }
            }
        }
        return $h;
    }

    public function updateElvesPosition(array $v, array &$p): void
    {
        foreach ($this->horizontal as $y => &$words) {
            foreach ($words as $w => &$word) {
                $word = $v[$y][$w] | $p[$y][$w]['isolated'];
            }
        }       
    }

    // This function can be modify if we save at the beginning the number of elves.
    // Emtpty tiles would be then the dimension of the rectangle minus the number of elves.
    public function emptyTiles(): int
    {
        // $emptyTiles = 0;
        // foreach ($this->horizontal as $y => $words)
        // {
        //     $last = array_key_last($words);
        //     // Not optimum at all.
        //     foreach ($words as $w => $word) {
        //         $size = ($w === $last) ? $this->dimensionX % 32 : 32;
        //         $emptyTiles += $size - substr_count(decbin($word), "1");
        //     }
        // }
        // return $emptyTiles;
        return $this->dimensionX * $this->dimensionY - $this->elves;
    }

    public function __toString()
    {
        $map = [];
        foreach ($this->horizontal as $y => $words) {
            $map[$y] = "";
            foreach ($words as $w => $word) {
                $r = str_replace("1", "#", str_replace("0", ".", decbin($word)));
                if (count($words) > 1 && $w < count($words) - 1) {
                    $r = str_pad($r, 32, ".", STR_PAD_LEFT);
                } else {
                    $r = str_pad($r, $this->dimensionX % 32, ".", STR_PAD_LEFT);
                }
                $map[$y] = $r . $map[$y];
            }
        }
        return join("\n", $map);
    }
}