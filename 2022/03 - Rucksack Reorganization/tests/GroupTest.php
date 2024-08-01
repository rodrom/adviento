<?php

declare(strict_types=1);

namespace Rodrom\Advent202203\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202203\Group;

class GroupTest extends TestCase
{
    private Group $group;

    protected function setUp(): void
    {
        $this->group = new Group([
            "vJrwpWtwJgWrhcsFMMfFFhFp",
            "jqHRNqRjqzjGDLGLrsFMfFZSrLrFZsSL",
            "PmmdzqPrVvPwwTWBwg"
        ]);
    }

    public function test_create_group_object(): void
    {    
        $this->assertInstanceOf(Group::class, $this->group);
        $this->assertEquals(3, $this->group->compartments);
        $this->assertEquals("vJrwpWtwJgWrhcsFMMfFFhFp", $this->group->comp1);
        $this->assertEquals("jqHRNqRjqzjGDLGLrsFMfFZSrLrFZsSL", $this->group->comp2);
        $this->assertEquals("PmmdzqPrVvPwwTWBwg", $this->group->comp3);
    }

    public function test_search_repeated_item_in_group(): void
    {
        $this->assertEquals('r', $this->group->repeatedItem());
    }

}
