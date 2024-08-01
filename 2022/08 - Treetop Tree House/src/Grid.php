<?php

declare(strict_types=1);
namespace Rodrom\Advent202208;

use Generator;

class Grid
{

    private function __construct(
        readonly int $columns,
        readonly int $rows,
        readonly array $grid
    ) { }

    public static function loadMap(string|array $map): self
    {
        $rows = is_string($map) ? explode("\n", $map) : $map;
        $numberOfRows = count($rows);
        $numberOfColumns = strlen($rows[0]);
        $grid = array_fill(0, $numberOfRows, array_fill(0, $numberOfColumns, 0));
        foreach ($rows as $r => $row) {
            $row = str_split($row);
            foreach ($row as $c => $value) {
                //echo "($r,$c): $value \n";
                $grid[$r][$c] = intval($value);
            }
        }
        
        return new self($numberOfRows, $numberOfColumns, $grid);
    }

    public function getTree(int $row, int $col): int
    {
        return $this->grid[$row][$col];
    }

    public function countTrees(): int
    {
        return $this->rows * $this->columns;
    }
    
    public function visibleTrees(): int
    {
        $visibleTrees = 0;
        foreach ($this->grid as $r => $row) {
            foreach($row as $c => $value) {
                if ($this->isVisibleTree($r, $c)) {
                    $visibleTrees++;
                }  
            }
        }
        return $visibleTrees;
    }

    public function isVisibleTree(int $row, int $col): bool
    {
        return $this->isAtTheEdges($row, $col)
            || $this->isVisibleFromTop($row, $col)
            || $this->isVisibleFromBottom($row, $col)
            || $this->isVisibleFromRight($row, $col)
            || $this->isVisibleFromLeft($row, $col);
    }

    public function isAtTheEdges(int $row, int $col): bool
    {
        
        return // In case that row or column is 0, is at the edges top or left.
            ($row === 0) || ($col === 0) ||
            // in case the row or column is the last, is at the edge of the bottom or right
            ($row === $this->rows - 1) || ($col === $this->columns - 1);
    }

    private function isVisibleFrom(int $row, int $col, Generator $gen): bool
    {
        $tree = $this->grid[$row][$col];

        foreach ($gen as $nextTree) {
            if ($tree <= $nextTree) {
                return false;
            }
        }
        return true;
    }

    public function isVisibleFromTop(int $row, int $col): bool
    {
        $gen = $this->toTheTop($row, $col);
        return $this->isVisibleFrom($row, $col, $gen);
    }

    private function toTheTop(int $row, int $col)
    {
        for($i = $row - 1; $i >= 0; $i--) {
            yield $this->grid[$i][$col];
        }
    }
    
    public function isVisibleFromBottom(int $row, int $col): bool
    {
        $gen = $this->toTheBottom($row, $col);
        return $this->isVisibleFrom($row, $col, $gen);
    }

    private function toTheBottom(int $row, int $col)
    {
        for($i = $row + 1; $i < $this->rows; $i++) {
            yield $this->grid[$i][$col];
        }
    }
    
    public function isVisibleFromLeft(int $row, int $col): bool
    {
        $gen = $this->toTheLeft($row, $col);
        return $this->isVisibleFrom($row, $col, $gen);
    }

    private function toTheLeft(int $row, int $col)
    {
        for($i = $col - 1; $i >= 0; $i--) {
            yield $this->grid[$row][$i];
        }
    }
    
    public function isVisibleFromRight(int $row, int $col): bool
    {

        $gen = $this->toTheRight($row, $col);
        return $this->isVisibleFrom($row, $col, $gen);
    }

    private function toTheRight(int $row, int $col)
    {
        for($i = $col + 1; $i < $this->columns; $i++) {
            yield $this->grid[$row][$i];
        }
    }

    public function maxTsr(): int
    {
        $max = 0;
        for ($i=0; $i < $this->rows; $i++) {
            for ($j=0; $j < $this->columns; $j++) {
                $max = max($max, $this->treeSceneryRating($i,$j));
            }
        }
        return $max;
    }

    public function treeSceneryRating(int $row, int $col): int
    {
        return $this->tsrTo($row, $col, $this->toTheTop($row, $col))
            * $this->tsrTo($row, $col, $this->toTheRight($row, $col))
            * $this->tsrTo($row, $col, $this->toTheBottom($row, $col))
            * $this->tsrTo($row, $col, $this->toTheLeft($row, $col));
    }

    private function tsrTo($row, $col, Generator $gen): int
    {
        $count = 0;
        $tree = $this->grid[$row][$col];
        foreach ($gen as $nextTree) {
            $count++;
            if ($nextTree >= $tree) {
                break;
            }
        }
        return $count;
    }
}