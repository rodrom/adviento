<?php

declare(strict_types=1);

namespace Rodrom\Advent202205;

use Rodrom\Advent202205\Crane;

class Crane2 extends Crane
{
    public function moveCrates(int $numberOfCrates, int $originStack, int $destinationStack): void
    {
        if (count($this->cargo[$originStack]) < $numberOfCrates) {
            throw new \Exception("Stack $originStack has not enough crates, not possible to move $numberOfCrates crates to Stack $destinationStack");
        }
        array_push($this->cargo[$destinationStack], ...(array_splice($this->cargo[$originStack], - $numberOfCrates)));

    }
}
