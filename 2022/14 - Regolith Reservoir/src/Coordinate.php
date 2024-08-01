<?php

declare(strict_types=1);
namespace Rodrom\Advent202214;

class Coordinate
{

    public function __construct(
        public int $x,
        public int $y,
        public int $h,
        public string $r,
    ) {}

    public function __toString()
    {
        return "$this->x,$this->y";
    }
}
