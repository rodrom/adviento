<?php

declare(strict_types=1);
namespace Rodrom\Advent202207;

use RecursiveIterator;

class FolderRecursiveIterator implements RecursiveIterator
{
    public function __construct(
        private Folder $folder,
        private int $index = 0
    ) { echo "IN FOLDER RECURSIVE ITERATOR\n"; }

    public function current(): FSNode
    {
        echo "current($this->index)\n";
        return $this->folder->getSon($this->index);
    }

    public function key(): int
    {
        echo "key($this->index)\n";
        return $this->index;
    }

    public function next(): void
    {
        echo "next() - index:" . $this->index+1 . "\n";
        $this->index++;
    }

    public function rewind(): void
    {
        echo "rewind()\n";
        $this->index = 0;
    }

    public function valid(): bool
    {
        echo "valid(): $this->index < " . $this->folder->count() . "\n";
        return $this->index < $this->folder->count();
    }

    public function hasChildren(): bool
    {
        echo "hasChildren():" . is_a($this->current(), Folder::class) . "\n";
        return is_a($this->current(), Folder::class);
    }

    public function getChildren(): ?RecursiveIterator
    {
        if ($this->hasChildren()) {
            echo "returnin Recursive iterator from " . $this->folder->name() . " for " . $this->current()->name() . "\n";
            return new FolderRecursiveIterator($this->current());
        }
        return false;
    }
}