<?php

declare(strict_types=1);
namespace Rodrom\Advent202215;

class CoordinateBCMath
{

    public function __construct(
        public string $x,
        public string $y,
        public string $h,
        public string $r,
    ) {}

    public function __toString()
    {
        return "$this->x,$this->y";
    }
}
