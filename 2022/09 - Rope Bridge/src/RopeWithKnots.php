<?php

declare(strict_types=1);
namespace Rodrom\Advent202209;

use Ds\Pair;

class RopeWithKnots
{
    const EPSILON = 0.005;

    private array $knots;
    private int $numberOfKnots;
    private int $size;
    private Point $head;
    private Point $tail;
    private FixedPoints $fp;
    private array $visited;

    public function __construct(int $size = 1, Point $start = null, int $numberOfKnots = 10)
    {
        $this->size = $size;
        $this->numberOfKnots = $numberOfKnots;
        $start = $start ?? new Point();
        for ($i =0; $i < $numberOfKnots; $i++) {
            $this->knots[] = clone $start;
        }
        $this->head = $this->knots[0];
        $this->tail = $this->knots[array_key_last($this->knots)];

        $this->fp = new FixedPoints();
        $stringStartPointKey = $start->__toString();
        $this->visited = [$stringStartPointKey => 1];
    }

    public function move(Move|string $move): array
    {
        $before = count($this->visited);
        $move = is_string($move) ? Move::fromString($move) : $move;
        // Break move in moves of one step
        foreach ($move->breakMove() as $oneStepMove) {
            $head = $this->moveHead($oneStepMove);
            for ($i = 1; $i < $this->numberOfKnots; $i++) {
                $knot = $this->moveKnot($i);
            }
            // Add the tail to visited list of points
            $this->tail = $knot;
            $stringPointTailKey = $this->tail->__toString();
            if (array_key_exists($stringPointTailKey, $this->visited)) {
                $this->visited[$stringPointTailKey] += 1;
            } else {
                $this->visited[$stringPointTailKey] = 1;
            }
        }
        return [ $this->head, $this->tail, count($this->visited) - $before];
    }

    public function moveHead(Move $move): Point
    {
        match ($move->direction) {
            
            Direction::Down => $this->head->y -= $move->steps,
            Direction::Left => $this->head->x -= $move->steps,
            Direction::Up => $this->head->y += $move->steps,
            Direction::Right => $this->head->x += $move->steps,
            default => throw new \Exception("Wrong direction in move for the Rope")
        };

        return $this->head;
    }

    public function moveKnot(int $i): Point
    {

        while ($this->knotShouldMove($i)) {
            $move = new Move(Direction::fromTwoPoints($this->knots[$i], $this->knots[$i - 1]), steps: 1);
            $visited = match ($move->direction) {
                Direction::Down => $this->knots[$i] = Point::add($this->knots[$i], $this->fp->down),
                Direction::Left => $this->knots[$i] = Point::add($this->knots[$i], $this->fp->left),
                Direction::Up => $this->knots[$i] = Point::add($this->knots[$i], $this->fp->up),
                Direction::Right => $this->knots[$i] = Point::add($this->knots[$i], $this->fp->right),
                Direction::UpLeft => $this->knots[$i] = Point::add($this->knots[$i], $this->fp->upLeft),
                Direction::UpRight => $this->knots[$i] = Point::add($this->knots[$i], $this->fp->upRight),
                Direction::DownLeft => $this->knots[$i] = Point::add($this->knots[$i], $this->fp->downLeft),
                Direction::DownRight => $this->knots[$i] = Point::add($this->knots[$i], $this->fp->downRight),
                Direction::None => $this->knots[$i],
            };
        }
        return $this->knots[$i];
    }

    public function knotShouldMove(int $i): bool
    {
        $distanceToTheHead = Point::distance($this->knots[$i], $this->knots[$i-1]);
        $direction = Direction::fromTwoPoints($this->knots[$i], $this->knots[$i-1]);
        if ($direction->isStraight()) {
            return $distanceToTheHead > $this->size;
        }
        if ($direction->isDiagonal()) {
            return $distanceToTheHead - static::EPSILON > ($this->size + (M_SQRT2 - 1.0));
        };
        return false;
    }

    public function getVisitedByTheTail(): array
    {
        return $this->visited;
    }

    public function __toString()
    {
        $rope = "";
        foreach ($this->knots as $k => $knot) {
            $rope .= "$k:$knot";
        }
        return $rope;
    }
}