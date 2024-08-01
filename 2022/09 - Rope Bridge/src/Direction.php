<?php

declare(strict_types=1);
namespace Rodrom\Advent202209;

enum Direction: int
{
    case DownLeft = 1;
    case Down = 2;
    case DownRight = 3;
    case Right = 6;
    case UpRight = 9;
    case Up = 8;
    case UpLeft = 7;
    case Left = 4;
    case None = 5;

    public static function fromTwoPoints(Point $from, Point $to): static
    {
        
        return match (true) {
            // Same point
            $from->x === $to->x && $from->y === $to->y => static::None,
            // Different x-axis : RIGHT
            $from->x < $to->x && $from->y === $to->y => static::Right,
            // Different x-axis : LEFT
            $from->x > $to->x && $from->y === $to->y => static::Left,
            // Different y-axis : UP
            $from->x === $to->x && $from->y < $to->y => static::Up,
            // Different y-axis : DOWN
            $from->x === $to->x && $from->y > $to->y => static::Down,
            // Different x-axis and x-axis : UpRight
            $from->x < $to->x && $from->y < $to->y => static::UpRight,
            // UpLeft
            $from->x > $to->x && $from->y < $to->y => static::UpLeft,
            // DownRight
            $from->x < $to->x && $from->y > $to->y => static::DownRight,
            // DownLeft
            $from->x > $to->x && $from->y > $to->y => static::DownLeft
        };
    }

    public function isStraight(): bool
    {
        return match($this) {
            Direction::Up, Direction::Down, Direction::Left, Direction::Right => true,
            default => false,
        };
    }

    public function isDiagonal(): bool
    {
        return match($this) {
            Direction::UpLeft, Direction::UpRight, Direction::DownLeft, Direction::DownRight => true,
            default => false
        };
    }

    public static function fromString($direction): static
    {
        return match ($direction) {
            'L' => static::Left,
            'R' => static::Right,
            'U' => static::Up,
            'D' => static::Down,
            'UL' => static::UpLeft,
            'UR' => static::UpRight,
            'DL' => static::DownLeft,
            'DR' => static::DownRight,
            'N' => static::None
        };
    }

    public function toString()
    {
        return match($this) {
            static::Left, => 'L', 
            static::Right, => 'R',
            static::Up, => 'U',
            static::Down, => 'D',
            static::UpLeft => 'UL',
            static::UpRight => 'UR',
            static::DownLeft => 'DL',
            static::DownRight => 'DR',
            static::None => 'N'
        };
    }
}
