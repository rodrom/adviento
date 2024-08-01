<?php

declare(strict_types=1);
namespace Rodrom\Advent202218;

class Block extends \stdClass
{
    public function __construct(
        public int $x = 0,
        public int $y = 0,
        public int $z = 0,
        public int $size = 1
    ) { }

    public static function adjacentBlocks(self $p, self $q): bool
    {
        // This only works with blocks of size 1.
        return (($p->x - $q->x) ** 2 + ($p->y - $q->y) ** 2 + ($p->z - $q->z) ** 2) === 1;
    }

    public static function north(self $p): static
    {
        return new static($p->x, $p->y + 1, $p->z);
    }

    public static function south(self $p): static
    {
        return new static($p->x, $p->y - 1, $p->z);
    }

    public function limits(int $min, int $max): bool
    {
        return $this->x >= $min && $this->x <= $max 
            && $this->y >= $min && $this->y <= $max
            && $this->z >= $min && $this->z <= $max;
    }

    /**
     * @return Block[]
     */
    public static function sides(self $p): array
    {
        return [
            'north' => static::north($p),
            'south' => static::south($p),
            'east' => static::east($p),
            'west' => static::west($p),
            'back' => static::back($p),
            'front' => static::front($p),
        ];
    }

    public static function east(self $p): static
    {
        return new static($p->x + 1, $p->y, $p->z);
    }

    public static function west(self $p): static
    {
        return new static($p->x - 1, $p->y, $p->z);
    }

    public static function front(self $p): static
    {
        return new static($p->x, $p->y, $p->z - 1);
    }

    public static function back(self $p): static
    {
        return new static($p->x, $p->y, $p->z + 1);
    }

    public function __toString()
    {
        return "(" . join(", ", [$this->x, $this->y, $this->z]) . ")";
    }
}
