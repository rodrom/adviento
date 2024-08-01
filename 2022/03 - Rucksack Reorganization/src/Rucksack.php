<?php

declare(strict_types=1);

namespace Rodrom\Advent202203;

use Exception;

class Rucksack
{
    public int $compartments = 2;
    public string $comp1;
    public string $comp2;

    public function __construct(string $items)
    {
        $q = intdiv(strlen($items), $this->compartments);
        [$this->comp1, $this->comp2] = str_split($items, $q);
    }

    public function repeatedItem(): string
    {
        $c1 = str_split($this->comp1);
        $c2 = str_split($this->comp2);

        $repeatedItemsInCompartments = array_unique(array_intersect($c1, $c2));
        if (count($repeatedItemsInCompartments) !== 1) {
            throw new Exception("Error in elements repeated in compartments");
        }
        
        return array_pop($repeatedItemsInCompartments);
    }

    public static function itemPrioritizeValue(string $item): int
    {
        if (preg_match("/[A-Z]/", $item)) {
            return ord($item) - ord('A') + 27;
        }
        if (preg_match("/[a-z]/", $item)) {
            return ord($item) - ord('a') + 1;
        }
        throw new Exception("Error in character item type value");
    }
}
