<?php

declare(strict_types=1);
namespace Rodrom\Advent202206;

class Device
{
    public static function detectStartPacket(string $data, int $size = 4): int
    {
        for ($c = $size; $c <= strlen($data); $c++) {
            $slice = substr($data, $c - $size, $size);
            if (self::uniqueCharactersInPacket($slice)) {
                return $c;
            }
        }
        throw new \Exception("There is not start packet in data stream");
    }

    public static function uniqueCharactersInPacket(string $packet): bool
    {
        $i = 0;
        for ($i = 0; $i < strlen($packet); $i++) {
            if (substr_count($packet, $packet[$i], $i) > 1) {
                return false;
            }
        }
        return true;
    }
}
