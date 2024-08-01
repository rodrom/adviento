<?php

declare(strict_types=1);
namespace Rodrom\Advent202215;

use Traversable;

class CoordinateMapBCMath
{
    public \Ds\map $map;

    public function __construct(Traversable|array $initial = [])
    {
        $this->map = new \Ds\Map($initial);
    }

    public function putC(CoordinateBCMath $c): void
    {
        $this->map->put($c->__toString(), $c);
    }

    public function count(): int
    {
        return $this->map->count();
    }

    public function minX(): int
    {
        return $this->map->reduce(function (int $min, string $k, CoordinateBCMath $c) {
            return $c->x < $min ? $c->x : $min;
        }, PHP_INT_MAX);
    }

    public function maxX(): int
    {
        return $this->map->reduce(function (int $max, string $k, CoordinateBCMath $c) {
            return $c->x > $max ? $c->x : $max;
        }, PHP_INT_MIN);
    }

    public function maxY(): int
    {
        return $this->map->reduce(function (int $max, string $k, CoordinateBCMath $c) {
            return $c->y > $max ? $c->y : $max;
        }, PHP_INT_MIN);
    }

    public function exist(CoordinateBCMath $c): bool
    {
        return $this->map->hasKey($c->__toString());
    }

    public function addUnit(CoordinateBCMath $c): string
    {
        $n = $this->map->get($c->__toString());
        $n->h = bcadd($n->h, "1");
        return $n->h;
    }
}
