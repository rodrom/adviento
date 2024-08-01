<?php

declare(strict_types=1);
namespace Rodrom\Advent202209;

final class FixedPoints
{
    public readonly Point $up;
    public readonly Point $right;
    public readonly Point $down;
    public readonly Point $left;
    public readonly Point $upLeft;
    public readonly Point $downRight;
    public readonly Point $upRight;
    
    public function __construct(
        $up = 1,
        $down = -1,
        $left = -1,
        $right = +1
    ) {
        $this->up = new Point(0, $up);
        $this->right = new Point($right, 0);
        $this->down = new Point(0, $down);
        $this->left = new Point($left, 0);
        $this->upLeft = new Point($left, $up);
        $this->downLeft = new Point($left, $down);
        $this->upRight = new Point($right, $up);
        $this->downRight = new Point($right, $down);
    }
}
