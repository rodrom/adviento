<?php

declare(strict_types=1);
namespace Rodrom\Advent202211;
class WorrynessUpdate
{
    public function __construct(
        public readonly Operation $operator,
        public readonly string $operand
    ) { }

    public function update(string $old): string
    {
        return match($this->operator) {
            Operation::Multiplication => bcmul($old, $this->operand),
            Operation::Sum => bcadd($old, $this->operand),
            Operation::PowerOf => bcpow($old, $this->operand),
        };
    }

}