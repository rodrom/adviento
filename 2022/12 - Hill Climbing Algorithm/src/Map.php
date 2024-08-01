<?php

declare(strict_types=1);
namespace Rodrom\Advent202212;

use IteratorAggregate;
use Traversable;

class Map implements IteratorAggregate
{
    public function __construct(
        public readonly int $X,
        public readonly int $Y,
        public Coordinate $start,
        public Coordinate $end,
        public CoordinateCollection $coordinates,
        readonly int $levels = 26,
        public Coordinate|null $current = null,
    ) {
        $this->current = $current ?? $start;
    }

    public static function loadMap(string|array $map, int $levels = 26): self
    {
        $rows = is_string($map) ? explode("\n", $map) : $map;
        $Y = count($rows);
        $X = strlen($rows[0]);
        Coordinate::$X = $X;
        Coordinate::$Y = $Y;
        $coordinates = new CoordinateCollection($X, $Y);
        foreach ($rows as $y => $row) {
            $row = str_split($row);
            foreach ($row as $x => $chr) {
                if ($chr === 'S') {
                    $start = new Coordinate($x, $y, 0, 'a');
                    $coordinates->set($start);
                } elseif ($chr === 'E') {
                    $end = new Coordinate($x, $y, $levels - 1, chr($levels + ord('a') - 1));
                    $coordinates->set($end);
                } else {
                    $coordinates->set(new Coordinate($x, $y, ord($chr) - ord('a'), $chr));
                }
            }
        }
        return new self($X, $Y, $start, $end, $coordinates, $levels);
    }

    public function createEdges(Coordinate $c): array
    {
        $edges = [];
        foreach ($this->adjacents($c) as $edge) {
            $edges[] = $edge;
        }

        return $edges;
    }

    public function adjacents(Coordinate $c) {
        if ($this->isAdjacentUp($c)) {
            yield new Edge($c, $this->coordinates->getC($c->x, $c->y - 1), 1);
        }
        if ($this->isAdjacentLeft($c)) {
            yield new Edge($c, $this->coordinates->getC($c->x - 1, $c->y), 1);
        }
        if ($this->isAdjacentDown($c)) {
            yield new Edge($c, $this->coordinates->getC($c->x, $c->y + 1), 1);
        }
        if ($this->isAdjacentRight($c)) {
            yield new Edge($c, $this->coordinates->getC($c->x + 1, $c->y), 1);
        }
    }

    public function isAdjacentUp(Coordinate $c): bool
    {
        return $c->y > 0 && $this->coordinates->getH($c->x, $c->y - 1) <= $c->h + 1;
    }

    public function isAdjacentLeft(Coordinate $c): bool
    {
        return $c->x > 0 && $this->coordinates->getH($c->x - 1, $c->y) <= $c->h + 1;
    }

    public function isAdjacentDown(Coordinate $c): bool
    {
        return $c->y + 1 < $this->Y && $this->coordinates->getH($c->x, $c->y + 1) <= $c->h + 1;
    }

    public function isAdjacentRight(Coordinate $c): bool
    {
        return $c->x + 1 < $this->X && $this->coordinates->getH($c->x + 1, $c->y) <= $c->h + 1;
    }

    public function createNodesFromMap(): array
    {
        $nodes = [];
        foreach($this->coordinates->collection as $c) {
            $nodes[] = new Node($c);
        }
        return $nodes;
    }

    public function getIterator(): Traversable
    {
        return $this->coordinates->collection;
    }

    public function __toString()
    {
        $outputMap = "";
        foreach($this as $i => $coordinate) {
            
            $outputMap .= $coordinate->r;
            if ((($coordinate->getIndex() + 1) % $this->X) === 0) {
                $outputMap .= "\n";
            }
        }
        return $outputMap;
    }

}
