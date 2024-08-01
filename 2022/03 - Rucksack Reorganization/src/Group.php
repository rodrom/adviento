<?php

declare(strict_types=1);

namespace Rodrom\Advent202203;

use Exception;

class Group
{
    // Compartments means rucksacks in this case.
    public int $compartments = 3;
    public string $comp1;
    public string $comp2;
    public string $comp3;

    public function __construct(array $elvesRucksacks)
    {
        [$this->comp1, $this->comp2, $this->comp3] = $elvesRucksacks;
    }

    public function repeatedItem(): string
    {
        $c1 = str_split($this->comp1);
        $c2 = str_split($this->comp2);
        $c3 = str_split($this->comp3);

        $repeatedItemsInCompartments = array_unique(array_intersect($c1, $c2, $c3));
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
