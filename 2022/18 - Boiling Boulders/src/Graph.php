<?php

declare(strict_types=1);
namespace Rodrom\Advent202218;

class Graph
{

    public function __construct(
        public array $vertex = [],
        public array $edges = [],
        public array $adj = []
    ) { }

    public function addVertex(int $index, \stdClass $obj): void
    {
        $this->vertex[$index] = $obj;
    }

    public function addEdge(int $origin, int $destination, int $weight = 1): void
    {
        $this->edges[] = "$origin:$destination:[$weight]";
        $this->adj[$origin][$destination] = $weight;
        $this->adj[$destination][$origin] = $weight;
    }

    public function bfs(int $source): array
    {
        $bfs = [];
        foreach ($this->vertex as $label => $v) {
            $bfs[$label] = ["color" => "WHITE", "distance" => PHP_INT_MAX, "parent" => null];
        }
        $bfs[$source] = ["color" => "GRAY", "distance" => 0, "parent" => null];
        $q = new \SplQueue();
        $q->enqueue($source);
        while (count($q) > 0) {
            $u = $q->dequeue();
            foreach ($this->adj[$u] as $dest => $weight) {
                if ($bfs[$dest]["color"] === "WHITE") {
                    $bfs[$dest] = [
                        "color" => "GRAY",
                        "distance" => $bfs[$u]['distance'] + 1, // BFS doesn't consider weights
                        "parent" => $u
                    ];
                    $q->enqueue($dest);
                }
            }
            $bfs[$u]["color"] = "BLACK";
        }
        return $bfs;
    }

    public static function getPathBft(array $bft, int $destination): array
    {
        $path = [];
        while ($bft[$destination]['parent'] !== null) {
            array_unshift($path, $destination);
            $destination = $bft[$destination]['parent'];
        }
        return $path;
    }
}
