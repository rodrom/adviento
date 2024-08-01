<?php

declare(strict_types=1);
namespace Rodrom\Advent202210;

class CRT
{
    public int $internal = 0;
    public Operation|null $current = null;

    public function __construct(
        public int $x = 1,
        public int $cycle = 0,
        public array $stack = [],
        public string|null $op = null,
        public string $output = "",
        private int $pixel = -1,
    ) {}

    public function run(): string
    {
        while (true) {
            
            // echo "END CYCLE: $this->cycle\n";
            $this->cycle++;
            $this->pixel++;
            // echo "BEGIN CYCLE: $this->cycle\n";
            if ($this->isSignalCycle()) {
                $this->output .= "\n";
            }
            
            if ($this->isCPUFree()) {
                // echo "LOAD OPERATION\n";
                if (!$this->loadOperation()) {
                    // echo "END OPERATIONS IN THE STACK\n";
                    break;
                }
            }
            $this->output .= $this->drawPixel();
            // echo "BEFORE EXECUTION\n";
            $this->execute();
            // echo "AFTER EXECUTION\n";
        }
        // echo "BEFORE EXIT PROGRAM\n";
        return $this->output;
    }

    public function drawPixel(): string
    {
        $pixel = $this->pixel;
        return $this->pixelInSprite(pixel: $this->pixel % 40, sprite: $this->x)
            ? "#" : ".";
    }

    function pixelInSprite(int $pixel, int $sprite): bool
    {
        return $pixel - $sprite <= 1 
            && $pixel - $sprite >= -1;
    }

    public function isSignalCycle() : bool
    {
        return $this->pixel % 40 === 0 && $this->pixel !== 0;
    }

    public function isCPUFree(): bool
    {
        return $this->op === null;
    }

    public function loadOperation(): bool
    {
        // echo "BEFORE LOAD OP FROM STACK\n";
        $this->op = array_shift($this->stack);
        if ($this->op === null) {
            return false;
        }
        // echo "AFTER LOAD $this->op FROM STACK\n";
        // charge instruction
        $this->current = match (true) {
            str_starts_with("noop", $this->op) => new Operation("NOOP", 1),
            preg_match('/^(addx) (-?\d+)$/', $this->op, $results) === 1
                => new Operation("ADDX", 2, $results[2]),
            default => throw new \Exception("Operation not recognize")
        };
        $this->internal = $this->current->cycles;

        return true;
    }

    public function execute(): void
    {
        $this->internal--;
        switch ($this->internal) {
            case 1 : 
                // echo "RUN" . PHP_EOL;
                break;
            case 0 : 
                // echo "FREE CPU" . PHP_EOL;
                $this->runOperation();
                $this->current = null;
                $this->op = null;
                break;
        }
    }

    public function runOperation(): void
    {
        match ($this->current->instruction) {
            "NOOP" => null,
            "ADDX" => $this->x += $this->current->parameter,
            default => throw new \Exception("INSTRUCTION NOT RECOGNIZE IN runOperation()"),
        };
    }
}
