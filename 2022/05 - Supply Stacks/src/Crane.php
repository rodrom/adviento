<?php
declare(strict_types=1);

namespace Rodrom\Advent202205;

class Crane {
    public array $cargo;

    public function __construct(array $cargo)
    {
        $this->cargo = $cargo;
    }

    public function moveCrates(int $numberOfCrates, int $originStack, int $destinationStack): void
    {
        for ($i = 0; $i < $numberOfCrates; $i++) {
            if (count($this->cargo[$originStack]) === 0) {
                throw new \Exception("Stack $originStack is empty, not possible to move $numberOfCrates crates to Stack $destinationStack");
            }
            array_push($this->cargo[$destinationStack], array_pop($this->cargo[$originStack]));
        }
    }

    public function executeOperations(array $operations): void
    {
        foreach($operations as $operation) {
            $this->moveCrates(...$operation);
        }
    }

    public function upperCrates(): string
    {
        $uppercrates = '';
        foreach($this->cargo as $stack) {
            $uppercrates .= $stack[array_key_last($stack)];
        }
        return $uppercrates;
    }
}
