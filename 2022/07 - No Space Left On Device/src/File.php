<?php

declare(strict_types=1);
namespace Rodrom\Advent202207;

class File extends FSNode
{
    public int $size;

    public function __construct(Folder|null $parent = null, string $name, int $size)
    {
        parent::__construct($parent, $name);
        $this->size = $size;
    }

    public function size(): int
    {
        return $this->size;
    }
}
