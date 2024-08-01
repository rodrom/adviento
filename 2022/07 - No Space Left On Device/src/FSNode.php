<?php

declare(strict_types=1);

namespace Rodrom\Advent202207;

abstract class FSNode
{
    protected Folder|null $parent = null;
    protected string $name;

    public function __construct(Folder|null $parent = null, string $name)
    {
        $this->parent = $parent;
        $this->name = $name;
        $this->parent?->addSon($this);
    }

    abstract public function size(): int;

    public function parent(): Folder|null
    {
        return $this->parent;
    }

    public function name(): string
    {
        return $this->name;
    }
}
