<?php

declare(strict_types=1);
namespace Rodrom\Advent202209;

class Move
{
    public function __construct(
        public readonly Direction $direction,
        public readonly int $steps = 1
    ) {}

    public static function fromString(string $input): static
    {
        if (preg_match('/^([LRUD]) (\d+)$/', $input, $results)) {
            return new static(Direction::fromString($results[1]), intval($results[2]));
        }
    }

    public function breakMove()
    {
        for ($i = 0; $i < $this->steps; $i++) {
            yield new static($this->direction, 1);
        }
    }

    public function __toString()
    {
        return $this->direction->toString() . " $this->steps";
    }
}
