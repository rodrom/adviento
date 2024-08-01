<?php

declare(strict_types=1);
namespace Rodrom\Advent202213\Test;
use PHPUnit\Framework\TestCase;
use Rodrom\Advent202213\Packet;

class PacketTest extends TestCase
{
    public function testPacket(): void
    {
        $packet = Packet::fromString('[1]', 1);
        $this->assertInstanceOf(Packet::class, $packet);
        $this->assertEquals([1], $packet->payload);
    }
}