<?php

declare(strict_types=1);
namespace Rodrom\Advent202216;

class Game
{
    /**
     * @var Valve[]
     */
    public array $valves;
    /**
     * @var Tunnel[]
     */
    public array $tunnels;
    public Graph $g;
    public array $distances;
    public array $bitmaskRelation;

    public function __construct(array $valves, array $tunnels, Graph $g)
    {
        $this->valves = $valves;
        $this->tunnels = $tunnels;
        $this->g = $g;
    }

    public static function readInput(string $input): static
    {
        $lines = explode("\n", $input);
        $valves = [];
        $tunnels = [];
        $g = new Graph();
        foreach ($lines as $line) {
            if (preg_match(
                '/^Valve ([A-Z]{2}) has flow rate=(\d+); tunnels? leads? to valves? ((?:[A-Z]{2}, )*[A-Z]{2})$/',
                $line,
                $matches)) {
                
                $label = $matches[1];
                $flowRate = intval($matches[2]);
                $origin = new Valve($label, $flowRate);
                $valves[$label] = $origin;
                $g->addVertex($label, $origin);

                if (preg_match_all('/[A-Z]{2}/', $matches[3], $destinations) > 0) {
                    foreach ($destinations[0] as $dest) {
                        $tunnels["$label-$dest"] = ["tunnel" => new Tunnel($label, $dest), "visited" => false];
                        $g->addEdge($label, $dest, 1);
                    }
                }
            }
        }
        return new static($valves, $tunnels, $g);
    }

    public function start(string $source, int $minutes = 30): array
    {
        // Create the Shortest Paths between all valves with Breadth-First Search
        foreach ($this->valves as $label => $valve) {
            $bft[$label] = $this->g->bfs($label);
        }
        // Apply dynamic programming
        // There is only one option you can choose, open a valve, going to the next valve
        // (this could be the nearest or/and the biggest)
        // To approach all solutions, we should maximize the flow of the system in the less minutes possibles.
        // One way to consider all options is: to create the permutations of all the useful valves to open.
        $usefulValves = array_keys(
            array_filter($this->valves, function ($valve) {
                return $valve->flowRate > 0;
            })
        );

        // To keep a register of the open valves, we can assign a bitmask for each valve.
        // This will be the state of possible solutions explored.
        $i = 1;
        foreach ($usefulValves as $valve) {
            $this->bitmaskRelation[$valve] = $i;
            $i = $i << 1;
        }
        
        // Get the distances between all relevant valves and origin.
        foreach(array_merge(["AA"], $usefulValves) as $origin) {
            foreach (array_merge(["AA"], $usefulValves) as $dest) {
                if ($origin === $dest) {
                    $this->distances[$origin][$dest] = 0;
                } else {
                    $this->distances[$origin][$dest] = count(Graph::getPathBft($bft[$origin], $dest));
                }
            }
        }

        // The subproblems that we need to analyze are stored in a memoized. The index or key
        // will be an string with the bitmask of openvalves.
        $cache = [];

        // The recursive function works like this
        $cache = $this->visit("AA", $minutes, 0, 0, $cache);
        return $cache;
    }

    public function visit(string $current, int $minutesLeft, int $bitmaskValves, int $flow, array &$memo): array
    {
        //echo "$current, $minutesLeft, $bitmaskValves, $flow, " . count($memo) . PHP_EOL;
        $memo[$bitmaskValves] = max($memo[$bitmaskValves] ?? 0, $flow);
        foreach ($this->bitmaskRelation as $dest => $bitPosition) {
            $newMinutesLeft = $minutesLeft - $this->distances[$current][$dest] - 1;
            //echo "$current->$dest: " . $this->distances[$current][$dest] . PHP_EOL;
            if (($bitPosition & $bitmaskValves) || $newMinutesLeft <= 0) {
                continue;
            }
            $this->visit(
                $dest,
                $newMinutesLeft,
                $bitmaskValves | $bitPosition,
                $flow + $newMinutesLeft * $this->valves[$dest]->flowRate,
                $memo
            );
        }
        return $memo;
    }
}
