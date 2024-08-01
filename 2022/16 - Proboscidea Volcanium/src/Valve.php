<?php

declare(strict_types=1);
namespace Rodrom\Advent202216;

class Valve extends \stdClass
{
    public readonly string $label;
    public readonly int $flowRate;
    public bool $close;

    public function __construct(string $label, int $flowRate)
    {
        $this->label = $label;
        $this->flowRate = $flowRate;
        $this->close = true;
    }
}
