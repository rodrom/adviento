<?php

namespace Rodrom\Advent202212;

use SplQueue;

class SpBFS implements SorthestPath
{

    public static function shorthestPath(Node $s, Graph $g): Graph
    {

        array_walk ($g->nodes, function (&$v) {
            $v->distance = PHP_INT_MAX;
            $v->predecessor = null;
            $v->visited = false;
        });

        $s->distance = 0;
        $s->visited = true;

        $queue = new SplQueue();
        $queue->enqueue($s);

        while (!$queue->isEmpty()) {
            $u = $queue->dequeue();

            foreach ($u->neighbourghs() as $coordinate) {
                $v = $g->nodes[$coordinate->getIndex()];
                if (!$v->visited) {
                    $v->visited = true;
                    $v->distance = $u->distance + 1;
                    $v->predecessor = $u;
                    $queue->enqueue($v);
                }
            }
        }

        return $g;
    }
}
