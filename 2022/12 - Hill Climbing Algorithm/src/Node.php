<?php

declare(strict_types=1);
namespace Rodrom\Advent202212;

class Node
{
    public function __construct(
        public readonly mixed $value = null,
        public array $edges = [],
        public bool $visited = false,
    ) { }

    public function neighbourghs()
    {
        /** @var Edge $edge */
        foreach ($this->edges as $edge) {
            yield $edge->b;
        }
    }
}
