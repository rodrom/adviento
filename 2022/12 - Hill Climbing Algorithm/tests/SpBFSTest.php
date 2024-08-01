<?php

declare(strict_types=1);


use PHPUnit\Framework\TestCase;
use Rodrom\Advent202212\Graph;
use Rodrom\Advent202212\Map;
use Rodrom\Advent202212\SpBFS;

class SpBFSTest extends TestCase
{
    public function testSpBFS_SimpleGraph(): void
    {
        $map = Map::loadMap("SE", 2);

        $graph = new Graph();

        $count = $graph->createNodes($map->createNodesFromMap(...));

        $count = $graph->createEdges($map->createEdges(...));
        $results = $graph->calcSorthestPathFrom(SpBFS::shorthestPath(...), ($graph->nodes)[0]);

        $this->assertEquals(1, ($graph->nodes[1])->distance);
        $this->assertEquals(($graph->nodes)[0], ($graph->nodes[1])->predecessor);
    }

    public function testSpfBFS_Example(): void
    {
        $map = Map::loadMap("Sabqponm\nabcryxxl\naccszExk\nacctuvwj\nabdefghi");

        $graph = new Graph();

        $count = $graph->createNodes($map->createNodesFromMap(...));

        $count = $graph->createEdges($map->createEdges(...));
        $results = $graph->calcSorthestPathFrom(SpBFS::shorthestPath(...), ($graph->nodes)[0]);

        $this->assertEquals(31, ($graph->nodes[21])->distance);
    }

    public function testSpfBFS_Complete(): void
    {
        $map = Map::loadMap(file_get_contents(__DIR__ . "\\..\\input.txt"));

        $graph = new Graph();

        $count = $graph->createNodes($map->createNodesFromMap(...));

        $count = $graph->createEdges($map->createEdges(...));
        $results = $graph->calcSorthestPathFrom(SpBFS::shorthestPath(...), ($graph->nodes)[$map->start->getIndex()]);

        $this->assertEquals(361, $graph->nodes[$map->end->getIndex()]->distance);
    }
}