<?php

declare(strict_types=1);
namespace Rodrom\Advent202214;

enum Cell: string
{
    case Sand = 'o';
    case Rock = '#';
    case Air = ' ';
    case Abyss = 'A';
    case Path = '~';

    public function stiffness (): int
    {
        return match ($this) {
            Cell::Sand, Cell::Rock => 2,
            Cell::Air, Cell::Path => 0,
            Cell::Abyss => -1,
        };
    }
}
