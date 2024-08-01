<?php

declare(strict_types=1);
namespace Rodrom\Advent202210;

class Operation
{
    public function __construct(
        public string $instruction,
        public int $cycles,
        public string|null $parameter = null,
    ) { }
}