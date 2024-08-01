<?php

declare(strict_types=1);
namespace Rodrom\Advent202209;

class Itinerary
{
    public function __construct(
        private array $visitedPoints = [],
        private array $historyMoves = []
    ) {}

    public function addMove(Move $move): void
    {
        array_push($this->historyMoves, $move);
    }

    public function addPoints(array $pointsVisitedByTheTail): int
    {
        $countBefore = count($this->visitedPoints);
        foreach ($pointsVisitedByTheTail as $point) {
            $stringPointKey = $point->__toString();
            if (array_key_exists($stringPointKey, $this->visitedPoints)) {
                $this->visitedPoints[$stringPointKey] += 1;
            } else {
                $this->visitedPoints[$stringPointKey] = 1;
            }
        }
        $countAfter = count($this->visitedPoints);
        return  $countAfter - $countBefore;
    }

    public function visitedPoints(): int
    {
        return count($this->visitedPoints);
    }
}
