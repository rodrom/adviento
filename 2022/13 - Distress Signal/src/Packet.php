<?php

declare(strict_types=1);
namespace Rodrom\Advent202213;

class Packet
{
    public function __construct(
        public int|array $payload,
        public int $level,
    ) { }

    public static function fromString(string $list, int $level): static
    {
        return new static (json_decode($list), $level);
    }
}