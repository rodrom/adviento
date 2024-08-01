<?php

declare(strict_types=1);
namespace Rodrom\Advent202207;

use IteratorAggregate;
use Traversable;

class Folder extends FSNode implements IteratorAggregate
{
    protected \Ds\Set $sons;

    public function __construct(Folder|null $parent = null, string $name)
    {
        parent::__construct($parent, $name);
        $this->sons = new \Ds\Set();
    }

    public function addSon(FSNode &$son): void
    {
        $this->sons->add($son);
    }

    public function sons(): array
    {
        return $this->sons->toArray();
    }

    public function contains(FSNode $node): bool
    {
        return $this->sons->contains($node);
    }

    public function size(): int
    {
        $temp = $this->sons->reduce(
            function (int $carry, FSNode $node): int
            {
                return $carry + $node->size();
            },
            0
        );
/*         if ($temp >= 913445)
            echo $this->name() . ",$temp\n"; */
        return $temp;
    }

    public function count(): int
    {
        return $this->sons->count();
    }

    public function empty(): bool
    {
        return $this->sons->isEmpty();
    }

    public function getSon(int $index): FSNode
    {
        return $this->sons->get($index);
    }

    public function getIterator(): Traversable
    {
        return new FolderRecursiveIterator($this);
    }
}
