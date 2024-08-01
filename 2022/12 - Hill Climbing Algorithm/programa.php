<?php

declare(strict_types=1);

use Rodrom\Advent202212\Coordinate;
use Rodrom\Advent202212\Graph;
use Rodrom\Advent202212\Map;
use Rodrom\Advent202212\SpBFS;

include "vendor/autoload.php";

$map = Map::loadMap(file_get_contents("input.txt"));
$graph = new Graph();
$graph->createNodes($map->createNodesFromMap(...));
$graph->createEdges($map->createEdges(...));
$graph->calcSorthestPathFrom(SpBFS::shorthestPath(...), ($graph->nodes)[$map->start->getIndex()]);
echo "START: $map->start | ENDR: $map->end \n";
echo "STEPS FROM START TO END: " . $graph->nodes[$map->end->getIndex()]->distance . PHP_EOL;

$path = $graph->getPath($graph->nodes[$map->start->getIndex()], $graph->nodes[$map->end->getIndex()]);
foreach ($path as $step) {
    $step->value->r = '_';
}
$map->start->r = 'S';
$map->end->r = 'E';

echo $map;