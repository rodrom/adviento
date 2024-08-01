<?php

declare(strict_types=1);
namespace Rodrom\Advent202219\Test;
use PHPUnit\Framework\TestCase;

use Rodrom\Advent202219\Blueprint;

class BlueprintTest extends TestCase
{
    public function test_read_input_blueprint(): void
    {
        $bluprint = Blueprint::readBlueprint("Blueprint 1: Each ore robot costs 4 ore. Each clay robot costs 2 ore. Each obsidian robot costs 3 ore and 14 clay. Each geode robot costs 2 ore and 7 obsidian.");
        $this->assertInstanceOf(Blueprint::class, $bluprint);
        $expected = new Blueprint(1, [
            "ore" => ["ore" => 4],
            "clay" => ["ore" => 2],
            "obsidian" => ["ore" => 3, "clay" => 14],
            "geode" => ["ore" => 2, "obsidian" => 7]
        ]);

        $this->assertEquals($expected, $bluprint);
    }

    public function test_maxQtyOre(): void
    {
        $bluprint = Blueprint::readBlueprint("Blueprint 1: Each ore robot costs 4 ore. Each clay robot costs 2 ore. Each obsidian robot costs 3 ore and 14 clay. Each geode robot costs 2 ore and 7 obsidian.");
        $result = $bluprint->maxQtyResource('ore');
        $this->assertEquals(4 + 2 + 3 + 2, $result);
    }

    public function test_maxQtyClay(): void
    {
        $bluprint = Blueprint::readBlueprint("Blueprint 1: Each ore robot costs 4 ore. Each clay robot costs 2 ore. Each obsidian robot costs 3 ore and 14 clay. Each geode robot costs 2 ore and 7 obsidian.");
        $result = $bluprint->maxQtyResource('clay');
        $this->assertEquals(14, $result);
    }

    public function test_maxQtyObsidian(): void
    {
        $bluprint = Blueprint::readBlueprint("Blueprint 1: Each ore robot costs 4 ore. Each clay robot costs 2 ore. Each obsidian robot costs 3 ore and 14 clay. Each geode robot costs 2 ore and 7 obsidian.");
        $result = $bluprint->maxQtyResource('obsidian');
        $this->assertEquals(7, $result);
    }
}