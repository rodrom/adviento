<?php

declare(strict_types=1);
namespace Rodrom\Advent202209;

class Bridge
{
    private Rope $rope;
    private Itinerary $itinerary;

    public function __construct(
        int $sizeOfTheRope = 1,
        int $sx = 0,
        int $sy = 0
    ) {
        $start = new Point($sx, $sy);
        $this->rope = new Rope($sizeOfTheRope, $start);
        $visitedPoints = [ "$start" => 1 ];
        $this->itinerary = new Itinerary($visitedPoints);
    }

    public function moveRope(Move|string $move): int
    {
        $move = is_string($move) ? Move::fromString($move) : $move;
        $pointsVisitedByTheTail = $this->rope->move($move);
        $this->itinerary->addMove($move);
        return $this->itinerary->addPoints($pointsVisitedByTheTail);
    }

    public function visitedPoints(): int
    {
        return $this->itinerary->visitedPoints();
    }
}
