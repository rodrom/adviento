<?php

declare(strict_types=1);
namespace Rodrom\Advent202211;

use stdClass;

class MonkeyParser
{
    public static function fromString(string $input): Monkey
    {
        $obj = new stdClass();
        $lines = explode("\n", $input);
        $obj->id = static::getId(trim($lines[0]));
        $obj->items = static::getItems(trim($lines[1]));
        $obj->updater = static::getUpdaterElements(trim($lines[2]));
        $obj->test = static::getTesterElements(trim($lines[3]));
        $obj->truthy = static::getTesterElementsTruthy(trim($lines[4]));
        $obj->falsy = static::getTesterElementsFalsy(trim($lines[5]));
        return static::fromStdObj($obj);
    }

    private static function fromStdObj(stdClass $obj): Monkey
    {
        $wu = new WorrynessUpdate(Operation::from($obj->updater[0]), strval($obj->updater[1]));
        $ds = new DestinationSelector(Operation::Divisible, strval($obj->test), $obj->truthy, $obj->falsy);

        return new Monkey($obj->items, $wu, $ds, $obj->id);
    }

    public static function getId(string $input): int
    {
        return preg_match('/^Monkey (\d+):$/', $input, $results)
            ? intval($results[1]) : -1;

    }

    public static function getItems(string $input): array
    {
        return preg_match_all('/(\d+)/', $input, $results)
         ? $results[1] : [];
    }

    public static function getUpdaterElements(string $input): array
    {
        preg_match('/^\s*Operation: new = old (\*|\+) (old|\d+)$/', $input, $results);
        return $results[2] === 'old' ? [ "**", 2 ] : [$results[1] , intval($results[2])];
         // throw new \Exception ("Error reading updater from MonkeyString");
        
    }

    public static function getTesterElements(string $input): int
    {
        return preg_match('/^\s*Test: divisible by (\d+)$/', $input, $results)
         ? intval($results[1]) : throw new \Exception ("Error reading test from MonkeyString");
        
    }

    public static function getTesterElementsTruthy(string $input): int
    {
        return preg_match('/^\s*If true: throw to monkey (\d+)$/', $input, $results)
         ? intval($results[1]) : throw new \Exception ("Error reading test truthy from MonkeyString");
    }

    public static function getTesterElementsFalsy(string $input): int
    {
        return preg_match('/^\s*If false: throw to monkey (\d+)$/', $input, $results)
         ? intval($results[1]) : throw new \Exception ("Error reading test falsy from MonkeyString");
    }
}