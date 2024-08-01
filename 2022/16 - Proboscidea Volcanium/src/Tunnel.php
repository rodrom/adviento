<?php

declare(strict_types=1);
namespace Rodrom\Advent202216;

class Tunnel
{
    public function __construct(
        public readonly string $origin,
        public readonly string $destination
    ) { }
}
