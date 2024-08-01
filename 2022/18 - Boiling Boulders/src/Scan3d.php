<?php

declare(strict_types=1);
namespace Rodrom\Advent202218;

class Scan3d
{
    public array $map3d;

    public function __construct(
        /** @property Block[] $blocks */
        public array $blocks = [],
        public Graph $graph = new Graph(),
        public readonly int $MAX = 20,
        public readonly int $MIN = -1,
    ) {
        foreach ($blocks as $k => $block) {
            $this->map3d[$block->x][$block->y][$block->z] = $k;
        }
    }

    public static function readInput(string $input): static
    {
        $blocks = [];
        $lines = explode("\n", $input);
        foreach ($lines as $k => $line) {
            if (preg_match('/^(\d+),(\d+),(\d+)$/', $line, $matches,)) {
                $x = intval($matches[1]);
                $y = intval($matches[2]);
                $z = intval($matches[3]);
                $block = new Block($x,$y,$z);
                $blocks[$k] = $block;
            }
        }
        return new static($blocks);
    }

    public function createGraph(): int
    {
        foreach ($this->blocks as $k => $p) {
            $this->graph->addVertex($k, $p);
            $neighborghs = array_filter($this->blocks, fn (Block $q) => Block::adjacentBlocks($p, $q));
            foreach ($neighborghs as $i => $q) {
                $this->graph->addEdge($k, $i);
            }
        }
        return count($this->graph->edges) / 2;
    }

    public function surface(): int
    {
        return count($this->blocks) * 6 - (count($this->graph->edges));
    }

    /**
     * @return Block[]
     */
    public function blocksOfAir(): int
    {
        $seen = [];
        $begin = new Block(-1, -1, -1);
        $key = strval($begin);
        $todo[$key] = $begin;
        while (count($todo) > 0) {
            $key = array_key_last($todo);
            $current = array_pop($todo);
            // para cada s en lados de s que no este en bloques ni seen (que este dentro de las coordenadas limite)
            $sides = Block::sides($current);
            foreach ($sides as $direction => $blockDirection) {
                $keyDirection = strval($blockDirection);
                if ($blockDirection->limits($this->MIN, $this->MAX) && $this->inside($blockDirection) === false && array_key_exists($keyDirection, $seen) === false) {
                    $todo[$keyDirection] = $blockDirection;
                }
            }
            $seen[$key] = $current;
        }
        $surfaces = 0;
        foreach ($seen as $seenKey => $s) {
            foreach ($this->blocks as $k => $block) {
                foreach (Block::sides($block) as $side) {
                    if ($seenKey === strval($side)) {
                        $surfaces++;
                    }
                }
                // s in seen para c en blocks para s en sides(c)
            }
        }
        return $surfaces;
    }

    public function inside(Block $block): bool
    {
        return isset($this->map3d[$block->x][$block->y][$block->z]);
    }
}
