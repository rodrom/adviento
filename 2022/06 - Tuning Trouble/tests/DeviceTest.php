<?php

declare(strict_types=1);

namespace Rodrom\Advent202206\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202206\Device;

class DeviceTest extends TestCase
{

    public function test_unique_characters_in_packet_true(): void
    {
        $input = "mjqj";
        
        $this->assertFalse(Device::uniqueCharactersInPacket($input));
    }

    public function test_unique_characters_in_packet_false(): void
    {
        $input = "mjqn";
        
        $this->assertTrue(Device::uniqueCharactersInPacket($input));
    }

    public function test_detect_start_packet(): void
    {
        $input = 'mjqjpqmgbljsphdztnvjfqwrcgsmlb';

        $this->assertEquals(7, Device::detectStartPacket($input));
    }

    public function test_detect_start_packet_more_examples(): void
    {
        $input = 'bvwbjplbgvbhsrlpgdmjqwftvncz';

        $this->assertEquals(5, Device::detectStartPacket($input));

        $input = 'nppdvjthqldpwncqszvftbrmjlhg';

        $this->assertEquals(6, Device::detectStartPacket($input));

        $input = 'nznrnfrfntjfmvfwmzdfjlvtqnbhcprsg';

        $this->assertEquals(10, Device::detectStartPacket($input));

        $input = 'zcfzfwzzqfrljwzlrfnpqdbhtmscgvjw';

        $this->assertEquals(11, Device::detectStartPacket($input));

    }

    public function test_detect_start_of_message_1(): void
    {
        $input = "mjqjpqmgbljsphdztnvjfqwrcgsmlb";

        $this->assertEquals(
            19,
            Device::detectStartPacket($input, 14)
        );
    }

    public function test_detect_start_of_message_2(): void
    {
        $input = "bvwbjplbgvbhsrlpgdmjqwftvncz";

        $this->assertEquals(
            23,
            Device::detectStartPacket($input, 14)
        );
    }

    public function test_detect_start_of_message_3(): void
    {
        $input = "nppdvjthqldpwncqszvftbrmjlhg";

        $this->assertEquals(
            23,
            Device::detectStartPacket($input, 14)
        );
    }

    public function test_detect_start_of_message_4(): void
    {
        $input = "nznrnfrfntjfmvfwmzdfjlvtqnbhcprsg";

        $this->assertEquals(
            29,
            Device::detectStartPacket($input, 14)
        );
    }

    public function test_detect_start_of_message_5(): void
    {
        $input = "zcfzfwzzqfrljwzlrfnpqdbhtmscgvjw";

        $this->assertEquals(
            26,
            Device::detectStartPacket($input, 14)
        );
    }
}
