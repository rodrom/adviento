<?php

declare(strict_types=1);
namespace Rodrom\Advent202212;

class Coordinate
{
    public static int $X = 0;
    public static int $Y = 0;

    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly int $h,
        public string $r,
    ) {}

    public function getIndex(): int
    {
        return $this->y * static::$X + $this->x;
    }

    public function __toString()
    {
        return "($this->x, $this->y)";
    }
}
