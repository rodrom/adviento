<?php

declare(strict_types=1);
namespace Rodrom\Advent202212;
class Edge
{
    public function __construct(
        public mixed $a,
        public mixed $b,
        public int $w = 0,
    ) { }
}
