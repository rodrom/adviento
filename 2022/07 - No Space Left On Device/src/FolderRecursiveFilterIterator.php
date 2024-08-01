<?php

declare(strict_types=1);
namespace Rodrom\Advent202207;

class FolderRecursiveFilterIterator extends FolderRecursiveIterator
{

    public function __construct(private Folder $folder) { }

    public function accept(): bool
    {
        return true;
    }
}
