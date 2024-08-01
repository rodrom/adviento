<?php

declare(strict_types=1);
namespace Rodrom\Advent202224;

class Board
{
    public const C = -1;
    public const N = 0;
    public const S = 1;
    public const W = 2;
    public const E = 3;

    public int $end;

    public function __construct(
        public int $dimX,
        public int $dimY,
        public array $blizzards,
        public array $emptyTiles,
        public int $start = 1,
    ) {
        $this->end = $dimY - 1;
    }

    public static function readInput (string $input): static
    {
        $lines = explode("\n", $input);
        $dimX = strlen($lines[0]) - 2;
        $dimY = count($lines) - 3;
        $initialBlizzards = [
            static::N => array_fill(0, $dimX, []),
            static::S => array_fill(0, $dimX, []),
            static::W => array_fill(0, $dimY, []),
            static::E => array_fill(0, $dimY, [])
        ];
        $emptyTiles = [];
        foreach (array_slice($lines, 1, $dimY) as $y => $line) {
            $row = str_split(substr($line, 1, $dimX));
            foreach ($row as $x => $v) {
                switch ($v) {
                    case '.' :
                        $emptyTiles[$x][$y] = true;
                        break;
                    case 'v' :
                        $initialBlizzards[static::S][$x][] = $y;
                        break;
                    case '^' :
                        $initialBlizzards[static::N][$x][] = $y;
                        break;
                    case '>' :
                        $initialBlizzards[static::E][$y][] = $x;
                        break;
                    case '<' :
                        $initialBlizzards[static::W][$y][] = $x;
                        break;
                    default: throw new \DomainException("Invalid input data at $x, $y in the board\n");
                }
            }
        }
        return new static($dimX, $dimY, $initialBlizzards, $emptyTiles);
    }

    // public function discoverPath($path = [[0, -1, 0]]): array
    // {
    //     $grids = [];
    //     $states = gmp_lcm($this->dimY, $this->dimY);
    //     $curr_grid = curr_blizz_grid = set((r, c, grid[r][c]) for c in range(
    //         lcols) for r in range(lrows) if grid[r][c] not in ["#", "."])
    //     for s in range(states + 1):
    //         curr_blizz_grid = next_blizz_grid(curr_blizz_grid)
    //         grids.append(set((t[0], t[1]) for t in curr_blizz_grid))
        
    //     }
    // }
    public function bfs (int $t0, int $sx, int $sy, int $ex, int $ey)
    {
        $nextQ = new \Ds\Set();
        $nextQ->add([$sx, $sy]);
        $t = $t0;

        $done = false;
        while (!$done && !$nextQ->isEmpty()) {
            $q = $nextQ;
            $nextQ = new \Ds\Set();
            foreach ($q as [$x, $y]) {
                if ([$x, $y] === [$ex, $ey]) {
                    $done = true;
                    break 2;
                }
                $ft = $this->freeTiles($x, $y, $t);
                $nextQ->add(...array_map(fn ($tile) => [$tile[0], $tile[1]], $ft));

                if ($done) {
                    break;
                }
            }
            $t++;
        }
        return $t;
    }

    public function freeTiles(int $x, int $y, int $t): array
    {
        $freeTiles = [];
        $s = $y + 1;
        if ($s < $this->dimY && $this->blizzardAt($x, $s, $t + 1) === false) {
            $freeTiles[self::S] = [$x, $s, $t + 1];
        }
        if ($s === $this->dimY && $x === $this->dimX - 1) {
            $freeTiles[self::S] = [$x, $s, $t + 1]; // END POS.
        }
        // Start position
        if ($y === -1) {
            $freeTiles[self::C] = [0, -1, $t + 1];
            return $freeTiles;
        }

        $n = $y - 1;
        if ($n >= 0 && $this->blizzardAt($x, $n, $t + 1) === false) {
            $freeTiles[self::N] = [$x, $n, $t + 1];
        }
        if ($n === -1 && $x === 0) {
            $freeTiles[self::C] = [$x, $n, $t + 1];
        }

        $w = $x - 1;
        if ($w >= 0 && $this->blizzardAt($w, $y, $t + 1) === false) {
            $freeTiles[self::W] = [$w, $y, $t + 1];
        }

        $e = $x + 1;
        if ($e < $this->dimX && $this->blizzardAt($e, $y, $t + 1) === false) {
            $freeTiles[self::E] = [$e, $y, $t + 1];
        }

        if ($this->blizzardAt($x, $y, $t + 1) === false) {
            $freeTiles[self::C] = [$x, $y, $t + 1];
        }
        
        return $freeTiles;
    }

    public function blizzardAt(int $x, int $y, int $t): bool
    {
        if ($x >= 0 && $x < $this->dimX && $y >= 0 && $y < $this->dimY) {
            return $this->blizzardNorthAt($x, $y, $t) || $this->blizzardSouthAt($x, $y, $t)
                || $this->blizzardWestAt($x, $y, $t) || $this->blizzardEastAt($x, $y, $t);
        }
        return false;
    }

    public function blizzardNorthAt(int $x, int $y, int $t): bool
    {
        return in_array(
            $y, (array_map(
                fn ($ini) => $this->dimY - 1 - (($this->dimY - 1 - $ini + $t) % $this->dimY),
                $this->blizzards[self::N][$x])
            ));
    }

    public function blizzardSouthAt(int $x, int $y, int $t): bool
    {
        return in_array($y, (array_map(fn ($ini) => ($ini + $t) % $this->dimY, $this->blizzards[self::S][$x])));
    }

    public function blizzardWestAt(int $x, int $y, int $t): bool
    {
        return in_array($x, (
            array_map(
                fn ($ini) => $this->dimX - 1 - (($this->dimX - 1 - $ini + $t) % $this->dimX),
                $this->blizzards[self::W][$y])
            ));
    }

    public function blizzardEastAt(int $x, int $y, int $t): bool
    {
        return in_array($x, (array_map(fn ($i) => ($i + $t) % $this->dimX, $this->blizzards[self::E][$y])));
    }
}
