<?php

declare(strict_types=1);
namespace Rodrom\Advent202216;

class Utils
{
    public static function permutations(array $elements)
    {
        if (count($elements) <= 1) {
            yield $elements;
        } else {
            foreach (self::permutations(array_slice($elements, 1)) as $permutation) {
                foreach (range(0, count($elements) - 1) as $i) {
                    yield array_merge(
                        array_slice($permutation, 0, $i),
                        [$elements[0]],
                        array_slice($permutation, $i)
                    );
                }
            }
        }
    }   
}