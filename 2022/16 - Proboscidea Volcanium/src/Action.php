<?php

declare(strict_types=1);
namespace Rodrom\Advent202216;

class Action
{
    public function __construct(
        public readonly string $type,
        public readonly string $label,
        public readonly int $minute,
        public array $open_valves = [],
        public array $visited = [],
    ) { }
    
    public function isValveOpen(string $label): bool
    {
        return in_array($label, $this->open_valves, true);
    }

    public function openValve(): static
    {
        return new static ("OPEN", $this->label, $this->minute + 1, array_merge($this->open_valves, [$this->label]), $this->visited);
    }

    public function moveTo(string $destination): static
    {
        $countVisited = array_key_exists($destination, $this->visited) ? $this->visited[$destination] + 1 : 1;
        $visited = $this->visited;
        $visited[$destination] = $countVisited;
        return new static("MOVE", $destination, $this->minute + 1, $this->open_valves, $visited);
    }
}
