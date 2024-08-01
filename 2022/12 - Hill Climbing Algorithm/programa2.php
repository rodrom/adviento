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
$startingPoints = $map->coordinates->collection->filter(
    function (Coordinate $c): bool {
        return $c->h === 0;
});
$distances = [];
// To print the path we should keep saved all the distances for each path.
foreach($startingPoints as $start) {
    $graph->calcSorthestPathFrom(SpBFS::shorthestPath(...), ($graph->nodes)[$start->getIndex()]);
    $steps = $graph->nodes[$map->end->getIndex()]->distance;
    $distances[$start->__toString()] = $steps;
    echo "START: $start | END: $map->end | STEPS: $steps\n" ;
}
$minumum = min($distances);
$index = array_search($minumum, $distances, true);
echo "Start: $index End: $map->end \n";
echo "MINUMU STEPS FROM an A TO END: $minumum\n";

// $path = $graph->getPath($graph->nodes[$map->start->getIndex()], $graph->nodes[$map->end->getIndex()]);
// foreach ($path as $step) {
//     $step->value->r = '_';
// }
// $map->start->r = 'S';
// $map->end->r = 'E';

// echo $map;