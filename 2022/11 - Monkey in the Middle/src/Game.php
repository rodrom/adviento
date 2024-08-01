<?php

declare(strict_types=1);
namespace Rodrom\Advent202211;

class Game
{
    public function __construct(
        private array $monkeys = [],
        private int $round = 0,
        private int $turn = 0,
        private int $numberOfMonkeys = 0,
        public readonly int $max_rounds = 1,
        public string $primeMonkeyMaster = "KING-KONG",
    ) {
        
    }
 
    public function loadMonkeysFromFile(string $filename): void
    {
        $input = file_get_contents($filename);
        $input = explode("\n\n", $input);
        $this->primeMonkeyMaster = "1";
        foreach($input as $k => $dataPlayar) {
            $monkey = MonkeyParser::fromString($dataPlayar);
            $this->monkeys[$monkey->id] = $monkey;
            $this->activeMonkeys[] = $monkey->id;
            $this->primeMonkeyMaster = bcmul($monkey->selector->operand, $this->primeMonkeyMaster);
        }
        $this->numberOfMonkeys = count ($this->monkeys);
    }

    public function play(): string
    {
        while ($this->round < $this->max_rounds) {
            $this->round++;
            /** @var Monkey $current */
            foreach ($this->monkeys as $id => $current) {
                $this->turn = $id;
                $throws = $current->playTurn();
                $chinese = $current->selector->truthy;
                // [value, destination]
                $this->throwItems($throws, $chinese);
            }
        }
        return $this->monkeyBusinessLevel();
    }

    public function throwItems(array $throws, int $chinese): void
    {
        foreach ($throws as [$value, $destination]) {
            $new = bcmod($value,$this->primeMonkeyMaster);
            //echo "CHINESE THEOREM $value by $this->primeMonkeyMaster = $new\n";
            $this->monkeys[$destination]->receiveItem($new);
        }
    }

/*     private function activeMonkeys() {
        foreach ($this->active as $id) {
            yield $id => $this->monkeys[$id];
        }
    } */

    public function getMonkeys(): array
    {
        return $this->monkeys;
    }

    public function monkeyBusinessLevel(): string
    {
        // We order from max to min according to number of inspections
        return usort($this->monkeys, fn (Monkey $a, Monkey $b) => $b->inspections <=> $a->inspections)
         ? bcmul(strval($this->monkeys[0]->inspections), strval($this->monkeys[1]->inspections))
         : "BUFFER MONKEYS" ;
    }
}