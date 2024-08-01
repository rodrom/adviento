<?php

declare(strict_types=1);
namespace Rodrom\Advent202211;

class Monkey
{
    
    public function __construct(
        private array $items = [],
        private WorrynessUpdate|null $operation = null,
        public DestinationSelector|null $selector = null,
        public readonly int $id = 0,
        public int $inspections = 0,
    ) { }

    public function playTurn(): array
    {
        $throws = [];
        if (empty($this->items)) {
            return $throws;
        }

        foreach ($this->items as $item => &$value) {
            $this->inspections++;
            $value = $this->inspect($value);
            //$value = $this->bored($value);
            $destination = $this->destination($value);
            $throws[] = [$value, $destination];
        }
        // Theorically, all items are thrown to other monkeys each turn. If not, revised this function.
        $this->items = [];
        return $throws;
    }
    
    public function inspect(string $value): string
    {
        return $this->operation->update($value);
    }

    public function bored(string $value, string $factor = "3"): string
    {
        return bcdiv($value, $factor);
    }

    public function destination(string $value): int
    {
        return $this->selector->destination($value);
    }

    public function hasItems(): bool
    {
        return count($this->items) > 0;
    }

    public function receiveItem(string $value): void
    {
        $this->items[] = $value;
    }

}
