<?php

declare(strict_types=1);
namespace Rodrom\Advent202204;

use Exception;

final class SectionPairing
{
    public int $firstRangeMin;
    public int $firstRangeMax;
    public int $secondRangeMin;
    public int $secondRangeMax;

    private function __construct(array $matches)
    {
        $this->firstRangeMin = intval($matches[1]);
        $this->firstRangeMax = intval($matches[2]);
        $this->secondRangeMin = intval($matches[3]);
        $this->secondRangeMax = intval($matches[4]);
    }

    public static function getPairingFromString(string $line): self
    {
        if (preg_match("/^(\d+)-(\d+),(\d+)-(\d+)$/", $line, $matches)) {
            return new self($matches);
        }
        throw new Exception("Wrong input line");
    }

    public function isRangesOverlapping(): bool
    {
        // This program assumes that the input gives the ranges always correctly, giving first the min and second the max of each range.
        return match (true) {
            // Not overlapping: first range max < second range min || first range min > second range max
            $this->firstRangeMax < $this->secondRangeMin => false,
            $this->secondRangeMax < $this->firstRangeMin => false,
            // Everything else overlaps something
            default => true
        };
    }

    public function isRangesFullyOverlapping(): bool
    {
        // There is not overlapping
        if (!$this->isRangesOverlapping()) {
            return false;
        }

        // There is overlapping
        return match (true) {
            // First range fully overlaps second
            $this->firstRangeMax >= $this->secondRangeMax 
            && $this->firstRangeMin <= $this->secondRangeMin 
                => true,
            // Second range fully overlaps first
            $this->secondRangeMin <= $this->firstRangeMin 
            && $this->secondRangeMax >= $this->firstRangeMax
                => true,
            default => false
        };
    }
}
