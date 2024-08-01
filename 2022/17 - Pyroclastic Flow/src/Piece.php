<?php

declare(strict_types=1);
namespace Rodrom\Advent202217;

final class Piece
{
    public function __construct(
        public int $height,
        public int $width,
        public array $form,
        public int $id,
    )
    { }

    public function bottom(int $x): int
    {
        for ($y = 0; $y > - $this->height; $y--) {
            if ($this->form[$x][$y] === '#') {
                return $y;
            }
        }
    }

    public function left(int $y): int
    {
        for ($x = 0; $x < $this->width; $x++) {
            if ($this->form[$x][$y] === '#') {
                return $x;
            }
        }
    }

    public function right(int $y): int
    {
        for ($x = $this->width - 1; $x >= 0; $x--) {
            if ($this->form[$x][$y] === '#') {
                return $x;
            }
        }
    }
}
