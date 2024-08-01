<?php

declare(strict_types=1);

namespace Rodrom\Advent202203\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202203\Rucksack;

class RucksackTest extends TestCase
{
    private Rucksack $rucksack;

    protected function setUp(): void
    {
        $this->rucksack = new Rucksack("vJrwpWtwJgWrhcsFMMfFFhFp");
    }

    public function test_create_rucksack_object(): void
    {    
        $this->assertInstanceOf(Rucksack::class, $this->rucksack);
        $this->assertEquals(2, $this->rucksack->compartments);
        $this->assertEquals("vJrwpWtwJgWr", $this->rucksack->comp1);
        $this->assertEquals("hcsFMMfFFhFp", $this->rucksack->comp2);
    }

    public function test_search_repeated_item_in_compartments(): void
    {
        $this->assertEquals('p', $this->rucksack->repeatedItem());
    }

    public function test_prioritize_value_of_repeated_item(): void
    {
        $this->assertEquals(16, $this->rucksack->itemPrioritizeValue('p'));
        $this->assertEquals(38, $this->rucksack->itemPrioritizeValue('L'));
        $this->assertEquals(42, $this->rucksack->itemPrioritizeValue('P'));
        $this->assertEquals(22, $this->rucksack->itemPrioritizeValue('v'));
        $this->assertEquals(20, $this->rucksack->itemPrioritizeValue('t'));
        $this->assertEquals(19, $this->rucksack->itemPrioritizeValue('s'));
        $this->assertEquals(1, $this->rucksack->itemPrioritizeValue('a'));
        $this->assertEquals(26, $this->rucksack->itemPrioritizeValue('z'));
        $this->assertEquals(27, $this->rucksack->itemPrioritizeValue('A'));
        $this->assertEquals(52, $this->rucksack->itemPrioritizeValue('Z'));
    }
}