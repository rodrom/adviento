<?php

declare(strict_types=1);
namespace Rodrom\Advent202212;

class Graph
{
    public iterable $nodes;
    public iterable $edges;

    public function createNodes(callable $nodesCreationFn): int
    {
        $this->nodes = call_user_func($nodesCreationFn);
        return count($this->nodes);
    }

    public function createEdges(callable $edgeCreationFn): int
    {
        $count = 0;
        foreach ($this->nodes as $i => $node) {
            $node->edges = call_user_func($edgeCreationFn, $node->value);
            $count += count($node->edges);
        }
        return $count;
    }

    public function calcSorthestPathFrom(callable $algorithm, Node $start): static
    {
        return call_user_func($algorithm, $start, $this);
    }

    public function getPath(Node $start, Node $end): array
    {
        $path = [$end];
        $u = $end->predecessor;
        while ($u) {
            $path[] = $u;
            $u = $u->predecessor;
        }
        return $path;
    }
}
