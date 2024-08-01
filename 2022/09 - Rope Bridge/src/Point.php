<?php

declare(strict_types=1);
namespace Rodrom\Advent202209;

class Point implements \Stringable
{
    public function __construct(
        public int $x = 0,
        public int $y = 0
    )
    { }

    public static function distance(Point $a, Point $b): float
    {
        return sqrt(self::squareDistance($a, $b)*1.0);
    }

    public static function squareDistance(Point $a, Point $b): int
    {
        return ($b->x - $a->x) ** 2 + ($b->y - $a->y) ** 2;
    }

    public static function add(Point $a, Point $b): static
    {
        return new static($a->x + $b->x, $a->y + $b->y);
    }

    public function __toString(): string
    {
        return "($this->x, $this->y)";
    }
}
