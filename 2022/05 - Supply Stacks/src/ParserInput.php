<?php
declare(strict_types=1);

namespace Rodrom\Advent202205;

class ParserInput
{
    public static function initialCargoAndOperations(string $input): array
    {
        [$cargoStr, $operationsStr] = explode("\n\n", $input, 2);
        $cargo = self::cargoReader($cargoStr);
        $operations = self::operationsReader($operationsStr);
        return [$cargo, $operations];
    }

    public static function cargoReader(string $data): array
    {
        $lines = explode("\n", $data);
        // Read last line: stacks ids
        $numberStacks = preg_match_all("/(\d+)/", array_pop($lines), $results);
        echo "Number of Stacks: $numberStacks \n";
        $cargo = [];
        foreach ($results[0] as $identifier) {
            $cargo[intval($identifier)] = [];
        }

        while ($line = array_pop($lines)) {
            $crates = str_split($line, 4);
            for ($i=1; $i <= $numberStacks; $i++) {
                $crate = $crates[$i-1];
                if (preg_match("/\[([A-Z])\]/", $crate, $result)) {
                    array_push($cargo[$i], $result[1]);
                }
            }
        }
        return $cargo;
    }

    public static function operationsReader(string $data): array
    {
        $lines = explode("\n", $data);
        $operations = [];
        foreach($lines as $line) {
            if (preg_match("/^move (\d+) from (\d+) to (\d+)$/", $line, $results, )) {
                $operations[] = [ intval($results[1]), intval($results[2]), intval($results[3]) ];
            }
        }
        return $operations;
    }
}
