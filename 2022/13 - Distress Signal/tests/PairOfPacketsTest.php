<?php

declare(strict_types=1);
namespace Rodrom\Advent202213\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202213\PairOfPackets;

class PairOfPacketsTest extends TestCase
{
    public function testPairOfPacketsCreation(): void
    {
        $pair = PairOfPackets::fromString("[1]\n[1]", index: 1);

        $this->assertObjectHasAttribute('left', $pair);
        $this->assertObjectHasAttribute('right', $pair);
        $this->isInstanceOf(Pair::class, $pair->left);
        $this->isInstanceOf(Pair::class, $pair->right);
        $this->assertTrue($pair->checkOrder());
    }

    public function testPairOfPacketsInOrderWithListsOnlyWithNumbers(): void
    {
        $pair = PairOfPackets::fromString("[1,1,3,1,1]\n[1,1,5,1,1]", 1);
    
        $this->assertTrue($pair->checkOrder());
    }
}