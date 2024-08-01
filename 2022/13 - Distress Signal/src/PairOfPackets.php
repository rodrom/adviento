<?php

declare(strict_types=1);
namespace Rodrom\Advent202213;

class PairOfPackets
{
    public function __construct(
        public Packet $left,
        public Packet $right,
        public int $index,
    ) { }

    public static function fromString(string $input, int $index): static
    {

        if (preg_match('/^(\[.*\])\n(\[.*\])$/', $input, $matches)) {
            $left = Packet::fromString($matches[1], 1);
            $right = Packet::fromString($matches[2], 1);
        }
        
        return new static($left, $right, $index);
    }

    public static function inOrder (mixed $left, mixed $right): bool|null
    {
        return match(true) {
            
            is_int($left) && is_int($right) =>
                ($left === $right) 
                    ? null 
                    : ($left < $right),
            
            is_array($left) && is_array($right) => 
                static::compareLists($left, $right),

            default => // Mixed Types
                is_int($left)
                    ? static::compareLists([$left], $right)
                    : static::compareLists($left, [$right]),
        };
    }

    public function checkOrder(): bool|null
    {
        return static::inOrder($this->left->payload, $this->right->payload);
    }

    
    public static function compareLists(array $left, array $right): bool|null
    {
        $correct = null;
        for($i = 0; $i < count($left) && $i < count($right); $i++) {
            $l = $left[$i];
            $r = $right[$i];
            
            $result = static::inOrder($l, $r);
            if ($result !== null) {
                return $result;
            }
        }

        if ($i < count($left)) {
            return false;
        }

        if ($i < count($right)) {
            return true;
        }

        return null;
    }
}
