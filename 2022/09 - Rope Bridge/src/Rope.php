<?php

declare(strict_types=1);
namespace Rodrom\Advent202209;

use Ds\Pair;

class Rope
{
    const EPSILON = 0.005;
    private Point $head;
    private Point $tail;
    private int $size;
    private FixedPoints $fp;

    public function __construct(int $size = 1, Point $head = null)
    {
        $this->size = $size;
        $this->head = $head ? $head : new Point();
        $this->tail = new Point($this->head->x, $this->head->y);
        $this->fp = new FixedPoints();
    }

    public function setPosition(int $hx = 0, int $hy = 0, int $tx = 0, int $ty = 0, int $size = 1): self
    {
        $this->head->x = $hx;
        $this->head->y = $hy;
        $this->tail->x = $tx;
        $this->tail->y = $ty;
        $this->size = $size;
        
        return $this;
    }

    public function move(Move $move): array
    {
        $this->moveHead($move);
        return $this->moveTail();
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

    public function moveTail(): array
    {

        $visitedPoints = [];
        while ($this->tailShouldMove()) {
            $move = new Move(Direction::fromTwoPoints($this->tail, $this->head), steps: 1);
            $visited = match ($move->direction) {
                Direction::Down => $this->tail = Point::add($this->tail, $this->fp->down),
                Direction::Left => $this->tail = Point::add($this->tail, $this->fp->left),
                Direction::Up => $this->tail = Point::add($this->tail, $this->fp->up),
                Direction::Right => $this->tail = Point::add($this->tail, $this->fp->right),
                Direction::UpLeft => $this->tail = Point::add($this->tail, $this->fp->upLeft),
                Direction::UpRight => $this->tail = Point::add($this->tail, $this->fp->upRight),
                Direction::DownLeft => $this->tail = Point::add($this->tail, $this->fp->downLeft),
                Direction::DownRight => $this->tail = Point::add($this->tail, $this->fp->downRight),
                Direction::None => $this->tail,
            };
            array_push($visitedPoints, $visited);
        }
        return $visitedPoints;
    }

    public function tailShouldMove(): bool
    {
        $distanceToTheHead = Point::distance($this->tail, $this->head);
        $direction = Direction::fromTwoPoints($this->tail, $this->head);
        if ($direction->isStraight()) {
            return $distanceToTheHead > $this->size;
        }
        if ($direction->isDiagonal()) {
            return $distanceToTheHead - static::EPSILON > ($this->size + (M_SQRT2 - 1.0));
        };
        return false;
    }
}