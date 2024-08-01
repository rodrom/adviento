<?php

declare(strict_types=1);
namespace Rodrom\Advent202211;

class DestinationSelector
{
    public function __construct(
        public readonly Operation $operator,
        public readonly string $operand,
        public readonly int $truthy,
        public readonly int $fail)
    { }

    public function test (string $value): bool
    {
        return match ($this->operator) {
            Operation::Divisible => bcmod($value, $this->operand) === "0"
        };
    }

    public function destination(string $value): int
    {
        return $this->test($value) ? $this->truthy : $this->fail;
    }
}