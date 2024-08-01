<?php

declare(strict_types=1);
namespace Rodrom\Advent202219;

class Blueprint
{
    public readonly int $id;
    public array $robots;

    public function __construct(int $id, array $robots)
    {
        $this->id = $id;
        $this->robots = $robots;
    }

    public static function readBlueprint(string $line): static|null
    {
        if (preg_match(
            '/^Blueprint (\d+): Each ore robot costs (\d+) ore. Each clay robot costs (\d+) ore. Each obsidian robot costs (\d+) ore and (\d+) clay. Each geode robot costs (\d+) ore and (\d+) obsidian.$/',
            $line,
            $matches
        )) {
            $id = intval($matches[1]);
            $robots = [
                "ore" => [ "ore" => intval($matches[2]) ],
                "clay" => [ "ore" => intval($matches[3]) ],
                "obsidian" => [ "ore" => intval($matches[4]), "clay" => intval($matches[5]) ],
                "geode" => [ "ore" => intval($matches[6]), "obsidian" => intval($matches[7]) ]
            ];
            return new static($id, $robots);
        }
        return null;
    }

    public function maxQtyResource(string $resource): int
    {
        return match($resource) {
            'geode' => PHP_INT_MAX,
            'obsidian' => $this->robots['geode']['obsidian'],
            'clay' => $this->robots['obsidian']['clay'],
            'ore' => max($this->robots['geode']['ore'], $this->robots['clay']['ore'], $this->robots['obsidian']['ore'], $this->robots['ore']['ore']),
        };
    }

    public function canMakeRobot(string $resource, array $resources): bool
    {
        foreach ($this->robots[$resource] as $resNeeded => $qty) {
            if ($resources[$resNeeded] < $qty) {
                return false;
            }
        }
        return true;
    }
}