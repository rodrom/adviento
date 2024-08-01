# Day 10: Cathode-Ray Tube

The device video needs a replace system! There is a cathode-ray tube screen and simple CPU that are both drive by a precise **clock circuit**.
The clock circuit ticks at a constant rate; each tick is called a cycle.

The CPU has a single register, `X`, which starts with the value `1`.
It supports only two instructions:

- `addx V` takes **two cycles** to complete. **After** two cycles, the
`X` register is increased by the value `V`. (`V` can be negative.)

- `noop` takes **one cycle**. It has no effect.

The CPU uses these instructions in a program (input) to, somehow, tell
the screen what to draw.

```text
noop
addx 3
addx -5
```

The internals of the CPU are as follows
```text
CYCLE INSTRUCTION X START : X END
  1      noop      1:1
  2      addx 3    1:1
  3      addx 3    1:4
  4      addx -5   4:4
  5      addx -5   3:-1
```

At the moment, consider looking at the value of the `X`.
The 
`signal strength = cycle * X` when `cycle = 20` and `cycle = 20+40n`
with `n` from `0 to 5`. The result is the sum of these figures.

### Solution

The initial idea could be create an class CPU that receives operations.
Each operation has number of cycles and affect the register according
to the instruction and the parameter.
When the CPU runs, it update the cycle and the register and takes instructions from a stack, for example.

```php
class CPU
{
    public function __construct(
        public int $x = 1,
        public int $cycle = 0,
        public array $stack = [],
        public string $op = "",
        public string $strengthAcc = 0;
    ) {}

    public function run(): void
    {
        while (true) {
            if ($this->isSignalCycle()) {
                $this->strengthAcc += $this->cycle * $this->x;
                if ($this->cycle === 220) {
                    break;
                }
            }
            $this->cycle++;
            
            if ($this->isCPUFree()) {
                if ($this->loadOperation() === false) {
                    break;
                }
            }
            $this->excute();
        }
        return $this->strengthAcc;
    }
}
```

## Part 2

The `X` register controls the horizontal position of a **sprite**.
Each *sprite* has 3 pixels wide, and the `X` register set the horizontal position of the middle of the sprite.
(There is not VERTICAL POSITION: If the sprite's horizontal position puts its pixels where the CRT is currently drawing, then those pixels will be drawn.=

You count the pixels of the CRT: 40 wide and 6 high. This CRT screen draws the top row of pixels left-to-right,
then the row below that, and so on.
The left-most pixel in each row is in position 0, and right-most, 39.

Like the CPU, the CRT is tied closely to the clock circuit: The CRT draws
**a single pixel during each cycle**. Representing each pixel of the screen as a `#`,
here are the cycles during which the first and last pixel in each row are drawn:
```text
Cycle   1 -> ######################################## <- Cycle  40
Cycle  41 -> ######################################## <- Cycle  80
Cycle  81 -> ######################################## <- Cycle 120
Cycle 121 -> ######################################## <- Cycle 160
Cycle 161 -> ######################################## <- Cycle 200
Cycle 201 -> ######################################## <- Cycle 240
```
So, by carefully timing the CPU instructions and the CRT drawing operations,
you should be able to determine whether the sprite is visible the instant each s pixel is drawn.
If the sprite is positioned such that one of its three pixels is the pixel currently being drawn, the screen
pruduces a **lit** pixel (`#`): otherwise, the screen leaves the pixel **dark** (`.`).

### Solution

So basically, each cycle the CRT draws the pixel in that position. If the Register (which represents the sprite) has a value between (-1 cycle + 1) , it draws `#`,
if not `.`.

The first idea is create a CRT class with a CPU property. And use the same cycle to draw. In this example, we don't need nothing related with the signal strength.
So we can create another CPU model or directly use the CRT class that take cares of everything. In some way is a total different program, but the signal stuff, could be useful to insert `\n` in the output. That will be every 40 cycles.
