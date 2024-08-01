<?php

declare(strict_types=1);
namespace Rodrom\Advent202214;

use SplHeap;

/** Coordinate */
class CoordinateHeap extends SplHeap
{
    protected function compare(mixed $a, mixed $b): int
    {
        return match (true) {
            $a->y !== $b->y => $a->y - $b->y,
            $a->y === $b->y => $a->x - $b->x,
        };
    }

    public function setLine(int $x0, int $y0, int $x1, int $y1, int $v = 0, string $r): int
    {
        if ($x0 === $x1) {
            return $this->setVerticalLine($x0, $y0, $y1, $v, $r);
        } else if ($y0 === $y1) {
            return $this->setHorizontalLine($y0, $x0, $x1, $v, $r);
        } else {
            throw new \Exception("This program doesn accept rocks in diagonal.");
        }
    }

    public function setHorizontalLine(int $x, int $y0, int $y1, int $v, string $r): int
    {
        $correct = 0;
        for ($y = $y0; $y <= $y1; $y++) {
            if ($this->insert(new Coordinate($x, $y, $v, $r))) {
                $correct++;
            }
        }
        return $correct;
        
    }

    public function setVerticalLine(int $y, int $x0, int $x1, int $v, string $r): int
    {
        $correct = 0;
        for ($x = $x0; $x <= $x1; $x++) {
            if ($this->insert(new Coordinate($x, $y, $v, $r))) {
                $correct++;
            }
        }
        return $correct;
    }
}
