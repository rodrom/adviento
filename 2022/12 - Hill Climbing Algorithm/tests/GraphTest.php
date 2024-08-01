<?php

declare(strict_types=1);
namespace Rodrom\Advent202212\Tests;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202212\Graph;
use Rodrom\Advent202212\Map;
use Rodrom\Advent202212\Node;

class GraphTest extends TestCase
{
    public function testCreatesNodesAndEdgeInSimpleMap(): void
    {
        $map = Map::loadMap("SE", 2);

        $graph = new Graph();

        $count = $graph->createNodes($map->createNodesFromMap(...));

        $this->assertEquals(2, $count);

        $count = $graph->createEdges($map->createEdges(...));

        $this->assertEquals(2, $count);
    }

    public function testCreatesNodesInContinousMapOf8LinearNodesButLastOutOfReach(): void
    {
        $map = Map::loadMap("SbcdefgE", 9); // There is one level more, so E is an i, instead of an h.

        $graph = new Graph();

        $count = $graph->createNodes($map->createNodesFromMap(...));

        $this->assertEquals(8, $count);

        $count = $graph->createEdges($map->createEdges(...));

        $this->assertEquals(13, $count);
    }
}