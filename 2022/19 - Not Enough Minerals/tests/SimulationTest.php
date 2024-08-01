<?php

declare(strict_types=1);
namespace Rodrom\Advent202219\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202219\Blueprint;
use Rodrom\Advent202219\Simulation;

class SimulationTest extends TestCase
{
    protected Blueprint $blueprint;

    protected function setUp(): void
    {
        $this->blueprint = Blueprint::readBlueprint("Blueprint 1: Each ore robot costs 1 ore. Each clay robot costs 1 ore. Each obsidian robot costs 1 ore and 1 clay. Each geode robot costs 1 ore and 1 obsidian.");
    }

    public function test_simulates_one_minute(): void
    {
        $simulation = new Simulation($this->blueprint, 0);
        $result = $simulation->qualityLevel(1, ['ore' => 1, 'clay' => 0, 'obsidian' => 0, 'geode' => 0], ['ore' => 0, 'clay' => 0, 'obsidian' => 0, 'geode' => 0]);
        $this->assertEquals(0, $result);
    }

    public function test_simulates_make_clay_robot(): void
    {
        $simulation = new Simulation($this->blueprint, 0);
        $result = $simulation->qualityLevel(1, ['ore' => 0, 'clay' => 0, 'obsidian' => 0, 'geode' => 0], ['ore' => 2, 'clay' => 0, 'obsidian' => 0, 'geode' => 0]);
        $this->assertEquals(0, $result);
    }
}