<?php

declare(strict_types=1);
namespace Rodrom\Advent202212;

use stdClass;

/** Coordinate */
class CoordinateCollection
{
    public readonly int $X;
    public readonly int $Y;
    public \Ds\Vector $collection;
    public function __construct(int $X, int $Y, array $initial = null)
    {
        $this->collection = new \Ds\Vector($initial ?? array_fill(0, $X*$Y, new stdClass));
        $this->X = $X;
        $this->Y = $Y;
    }

    private function realIndex(Coordinate $c): int
    {
        return $c->y * $this->X + $c->x; 
    }

    public function set(Coordinate $c): void
    {
        $index = $this->realIndex($c);
        $this->collection->set($index, $c);
    }

    public function getC(int $x, int $y): Coordinate
    {
        return $this->collection->get($y * $this->X + $x);
    }

    public function getH(int $x, int $y): int
    {
        if ($x >= $this->X || $y >= $this->Y) {
            throw new \Exception("ERROR CALLING getH");
        }
        return $this->collection->get($y * $this->X + $x)->h;
    }

    public function getR(Coordinate $c): string
    {
        return $this->collection->get($this->realIndex($c))->r;
    }
}
