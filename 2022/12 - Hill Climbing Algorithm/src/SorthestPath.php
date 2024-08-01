<?php

declare(strict_types=1);
namespace Rodrom\Advent202212;

interface SorthestPath
{
    /**
     * Returns minimum distances from source to rest of vertex
     * 
     * Algorithms like BFS, Dijkstra, Bellman-Ford
     * @param mixed $graph
     * @param mixed $source
     * 
     * @return object
     */
    public static function shorthestPath(Node $source, Graph $graph): Graph;
}