<?php

declare(strict_types=1);
namespace Rodrom\Advent202215;

use Traversable;

class CoordinateMap
{
    public \Ds\map $map;

    public function __construct(Traversable|array $initial = [])
    {
        $this->map = new \Ds\Map($initial);
    }

    public function putC(Coordinate $c): void
    {
        $this->map->put($c->__toString(), $c);
    }

    public function setLine(int $x0, int $y0, int $x1, int $y1, int $v = 0, string $r = null): int
    {
        if ($x0 === $x1) {
            return $this->setVerticalLine($x0, $y0, $y1, $v, $r);
        } else if ($y0 === $y1) {
            return $this->setHorizontalLine($y0, $x0, $x1, $v, $r);
        } else {
            throw new \Exception("This program doesn accept rocks in diagonal.");
        }
    }

    public function setHorizontalLine(int $y, int $x0, int $x1, int $v, string $r): int
    {
        $correct = 0;
        $min = min($x0, $x1);
        $max = max($x0, $x1);
        for ($x = $min; $x <= $max; $x++) {
            $this->map->put("$x,$y", new Coordinate($x, $y, $v, $r));
            $correct++;
        }
        return $correct;
        
    }

    public function setVerticalLine(int $x, int $y0, int $y1, int $v, string $r): int
    {
        $correct = 0;
        $min = min($y0, $y1);
        $max = max($y0, $y1);
        for ($y = $min; $y <= $max; $y++) {
            if ($this->map->put("$x,$y", new Coordinate($x, $y, $v, $r))) {
                $correct++;
            }
        }
        return $correct;
    }

    public function count(): int
    {
        return $this->map->count();
    }

    public function minX(): int
    {
        return $this->map->reduce(function (int $min, string $k, Coordinate $c) {
            return $c->x < $min ? $c->x : $min;
        }, PHP_INT_MAX);
    }

    public function maxX(): int
    {
        return $this->map->reduce(function (int $max, string $k, Coordinate $c) {
            return $c->x > $max ? $c->x : $max;
        }, PHP_INT_MIN);
    }

    public function maxY(): int
    {
        return $this->map->reduce(function (int $max, string $k, Coordinate $c) {
            return $c->y > $max ? $c->y : $max;
        }, PHP_INT_MIN);
    }

    public function exist(Coordinate $c): bool
    {
        return $this->map->hasKey($c->__toString());
    }

    public function addUnit(Coordinate $c): int
    {
        $n = $this->map->get($c->__toString());
        $n->h += 1;
        return $n->h;
    }
}
