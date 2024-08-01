<?php

declare(strict_types=1);

namespace Rodrom\Advent202214;

class Cave
{
    public CoordinateMap $map;
    public Coordinate $sandOrigin;

    public function __construct(public CoordinateMap $rocks, int $sx = 500, int $sy = 0)
    {
        $this->sandOrigin = new Coordinate($sx, $sy, Cell::Sand->stiffness(), Cell::Sand->value);
        $this->map = clone $rocks;
        
    }

    public static function fromString(string $input): static
    {
        $rockPaths = explode("\n", $input);
        $coordinates = new CoordinateMap();
        foreach ($rockPaths as $path) {
            static::scanSectionOfRocks($path, $coordinates, Cell::Rock->stiffness(), Cell::Rock->value);
        }
        $minX = $coordinates->minX();
        $maxX = $coordinates->maxX();
        $maxY = $coordinates->maxY() + 2;
        $minX = $minX - intdiv($maxY,2) - 100;
        $maxX = $maxX + intdiv($maxY,2) + 100;
        static::scanSectionOfRocks("$minX,$maxY -> $maxX,$maxY", $coordinates, Cell::Rock->stiffness(), Cell::Rock->value);
        return new static($coordinates);
    }

    private static function scanSectionOfRocks(string $path, CoordinateMap &$coordinates, int $value): int
    {
        if (preg_match_all('/(\d{1,3}),(\d{1,3})/', $path, $matches)) {
            /** @var int[] $xs */
            $xs = array_map(intval(...), $matches[1]);
            /** @var int[] $ys */
            $ys = array_map(intval(...), $matches[2]);
            for ($i = 1; $i < count($xs); $i++) {
                $coordinates->setLine($xs[$i - 1], $ys[$i - 1], $xs[$i], $ys[$i], $value, Cell::Rock->value);
            }
        }
        return $coordinates->count();
    }

    public function expellSand(): Coordinate
    {
        $units = 0;
        while (true) {
            $last = $this->fallUnitOfSand();
            if ($this->sandOrigin->x === $last->x && $this->sandOrigin->y === $last->y) {
                return $this->sandOrigin;
            }
            $units++;
            echo "$units: $last\n";
        }
        return $last;
    }

    public function fallUnitOfSand(): Coordinate
    {
        $current = clone $this->sandOrigin;
        //$path[] = $current;
        while ($next = $this->nextPosition($current)) {
            if ($next === $this->sandOrigin) {
                //$path[] = $next;
                return $next;
            }
            if ($next->x === $current->x && $next->y === $current->y && $next->h === Cell::Sand->stiffness()) {
                //$path[] = $next;
                //$this->lastPath = $path;
                $this->map->putC($next);
                return $next;
            }
            //$path[] = $next;
            $current = $next;
        }
    }

    public function nextPosition(Coordinate $current): Coordinate
    {
        $next = match (true) {
            $this->downIsAir($current)
                => new Coordinate (
                    $current->x,
                    $current->y + 1,
                    Cell::Path->stiffness(),
                    Cell::Path->value
                ),
            $this->downIsBlock($current) =>
                match (true) {
                    $this->downToLeft($current) => new Coordinate(
                        $current->x - 1,
                        $current->y + 1,
                        Cell::Path->stiffness(),
                        Cell::Path->value
                    ),
                    $this->downToRight($current) => 
                        new Coordinate(
                            $current->x + 1,
                            $current->y + 1,
                            Cell::Path->stiffness(),
                            Cell::Path->value
                        ),
                    default => $this->setToSand($current)
                },
            default => throw new \Exception("WRONG BRANCH IN PHYSIC SYSTEM")
        };
        return $next;
    }

    public function setToSand(Coordinate &$current): Coordinate
    {
        $current->h = Cell::Sand->stiffness();
        $current->r = Cell::Sand->value;

        return $current;
    }

    public function downIsAbyss(Coordinate $current): Bool
    {
        $rocksAtTheBottom = $this->rocks->map->filter(function (string $key, Coordinate $coordinate) use ($current) {
            return $coordinate->x === $current->x && $current->y < $coordinate->y;
        });
        return $rocksAtTheBottom->count() === 0;
    }

    public function downIsAir(Coordinate $current): Bool
    {
        $downKey = "$current->x," . ($current->y + 1);
        return $this->map->map->hasKey($downKey) === false;
    }

    public function downToLeft(Coordinate $current): Bool
    {
        $downKey = $current->x - 1 . "," . ($current->y) + 1;
        return $this->map->map->hasKey($downKey) === false;
    }

    public function downToRight(Coordinate $current): Bool
    {
        $downKey = $current->x + 1 . "," . ($current->y) + 1;
        return $this->map->map->hasKey($downKey) === false;
    }

    public function downIsBlock(Coordinate $current): Bool
    {
        $downKey = $current->x . "," . ($current->y) + 1;
        return $this->map->map->hasKey($downKey) === true;
    }

}