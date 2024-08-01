<?php

declare(strict_types=1);
namespace Rodrom\Advent202211;
enum Operation: string
{
    case Multiplication = '*';
    case Sum = '+';
    case Divisible = '%';
    case PowerOf = '**';
}
