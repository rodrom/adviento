<?php
declare(strict_types=1);

namespace Rodrom\Advent202205\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202205\ParserInput;

class ParserInputTest extends TestCase
{
    private string $input = 
                        <<<EOD
                            [D]    
                        [N] [C]    
                        [Z] [M] [P]
                         1   2   3 

                        move 1 from 2 to 1
                        move 3 from 1 to 3
                        move 2 from 2 to 1
                        move 1 from 1 to 2
                        EOD;

    public function test_separete_input_cargo_and_operations(): void
    {
        [$cargo, $operations] = ParserInput::initialCargoAndOperations($this->input);
        
        $expectedCargo = [
            1 => ['Z', 'N'],
            2 => ['M', 'C', 'D'],
            3 => ['P'],
        ];
        
        $expectedOperations = [
            [1, 2, 1],
            [3, 1, 3],
            [2, 2, 1],
            [1, 1, 2]
        ];

        $this->assertEquals($expectedCargo, $cargo);
        $this->assertEquals($expectedOperations, $operations);
    }
}
