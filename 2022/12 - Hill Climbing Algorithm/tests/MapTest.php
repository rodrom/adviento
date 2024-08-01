<?php

declare(strict_types=1);

namespace Rodrom\Advent202212\Tests;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202212\Coordinate;
use Rodrom\Advent202212\CoordinateCollection;
use Rodrom\Advent202212\Map;

class MapTest extends TestCase
{

    public function testCreateSimplestMapWithConstructor(): void
    {
        $map = new Map(
            X: 1,
            Y: 2, 
            start: new Coordinate(0,0,0,'a'),
            end: new Coordinate(0,1,1,'b'),
            coordinates: new CoordinateCollection(2,1),
            levels: 2
        );

        $this->assertInstanceOf(Map::class, $map);
    }

    public function testLCreateSimplestMapFromString(): void
    {
        $map = Map::loadMap("SE", 2);

        $expected = new Map(
            X: 2,
            Y: 1, 
            start: new Coordinate(0,0,0,'a'),
            end: new Coordinate(1,0,1,'b'),
            coordinates: new CoordinateCollection(2, 1),
            levels: 2
        );

        $this->assertInstanceOf(Map::class, $map);
        $this->assertEquals($expected, $map);
    }

    public function testLoadMapContinuosExample(): void
    {
        $map = Map::loadMap("Sbcdefgh\nponmlkji\nqrstuvwx\nzzzzzzEy"); // \naccszExk\nacctuvwj\nabdefghi\n
        $expected = new Map(8, 4, new Coordinate(0, 0, 0, 'a'), new Coordinate(6, 3, 25, 'z'),
            new CoordinateCollection(8 , 4, [
                0, 1, 2, 3, 4, 5, 6, 7,
                15, 14, 13, 12, 11, 10, 9, 8,
                16, 17, 18, 19, 20, 21, 22, 23,
                25, 25, 25, 25, 25, 25, 25, 24
            ]),
        );
        
        $this->assertEquals($expected, $map);
    }
}