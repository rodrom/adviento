<?php

declare(strict_types=1);
namespace Rodrom\Advent202217;

use SplStack;

final class Board
{
    /**
     * @var \SplStack[] $columns
     */
    public array $columns;
    public array $sequence;
    public int $lastPiece;
    public string $jets;
    private int $totalJets;
    public int $nextJet;
    private array $position;

    public function __construct(
        public int $width = 7,
        public int $originX = 2,
        public int $originY = 3,
        public int $turn = 0,
    )
    {
        for($i = 0; $i < $width; $i++) {
            $q = new SplStack();
            $q->push(0);
            $this->columns[$i] = $q;
        }
    }

    public function setPiecesSequence(array $sequence): void
    {
        $this->sequence = $sequence;
    }

    public function setJetsSequence(string $jets): void
    {
        $this->jets = $jets;
        $this->totalJets = strlen($jets);
        $this->nextJet = 0;
    }

    public function getJet(): int
    {
        $index = $this->nextJet % $this->totalJets;
        $this->nextJet = ($index + 1) % $this->totalJets;
        return $this->jets[$index] === '<' ? - 1 : + 1;
    }

    public function playPieceAtTheTop(): array
    {
        /**
         * @var Piece $currentPiece
         */
        $currentPiece = $this->sequence[$this->turn % count($this->sequence)];
        $this->lastPiece = $this->turn % count($this->sequence);
        $this->position = ['x' => $this->originX, 'y' => $this->getTowerHeight() + $this->originY + 1];
        $position = &$this->position;
        //echo "BEGIN TURN $this->turn: ['x: {$position['x']}, y: {$position['y']}]";
        $resting = false;
        $isJetMove = true;
        while ($resting === false) {
            // Move down
            if ($isJetMove === false) {
                if ($this->isTouchingDown($currentPiece, $position)) {
                    //echo "r";
                    $resting = true;
                    continue;
                }
                //echo "v";
                $position['y']--;
                $isJetMove = true;
                continue;
            }
            // Get jet move
            $jetMove = $this->getJet();
            if ($jetMove < 0) { // Move Left
                //echo "<";
                if ($this->isSpaceLeft($currentPiece, $position)) {
                    //echo "i";
                    $position['x']--;
                }
            } elseif ($jetMove > 0) { // Move Right
                //echo ">";
                if ($this->isSpaceRight($currentPiece, $position)) {
                    //echo "d";
                    $position['x']++;
                }
            }
            $isJetMove = false;
        }
        // Update board
        //echo "END TURN: $this->turn Position: [x: " . $position['x'] . ", y: " . $position['y'] . "]" . "Piece: " . $currentPiece->id . PHP_EOL;
        $this->setPieceDown($currentPiece, $position);
        $this->turn++;
        return $position;
    }

    public function getTowerHeight(): int
    {
        return max(array_map(
            fn (\SplStack $stack) => $stack->top(),
            $this->columns
        ));
    }

    public function isTouchingDown(Piece $piece, array $position): bool
    {
        for ($x = 0; $x < $piece->width; $x++) {
            $col = $x + $position['x'];
            $row = $position['y'] - $piece->bottom($x);
            // What happen when there are filled squares up, but at this point the piece is blocked by other
            // elements in the tower.
            foreach($this->columns[$col] as $nodeIndex => $bottomHeight) {
                if ($bottomHeight  < $row) {
                    break;
                }
            }
            if ($bottomHeight + 1 === $row) {
                return true;
            }
        }
        return false;
    }

    public function isSpaceLeft(Piece $piece, array $position): bool
    {
        if ($position['x'] === 0) {
            return false;
        }

        for ($y = $piece->height - 1; $y >= 0; $y--) {
            $col = $position['x'] - 1 + $piece->left(- $y);
            $row = $position['y'] + $y;
            foreach($this->columns[$col] as $nodeIndex => $leftHeight) {
                if ($leftHeight < $row) {
                    break;
                }
                if ($leftHeight === $row) {
                    return false;
                }
            }
        }
        return true;
    }

    public function isSpaceRight(Piece $piece, array $position): bool
    {
        if ($position['x'] + $piece->width >= $this->width) {
            return false;
        }

        for ($y = $piece->height - 1; $y >= 0; $y--) {
            $col = $position['x'] + 1 + $piece->right(- $y);
            $row = $position['y'] + $y;
            // The problem is that some values could be not ordered.
            foreach($this->columns[$col] as $nodeIndex => $rightHeight) {
                if ($rightHeight < $row) {
                    break;
                }
                if ($rightHeight === $row) {
                    return false;
                }
            }
        }
        return true;
    }

    public function setPieceDown(Piece $piece, array $position): void
    {
        for ($x = 0; $x < $piece->width; $x++) {
            $col = $position['x'] + $x;
            //$row = $this->columns[$col]->top();
            for ($y = 0; $y > - $piece->height; $y--) {
                if ($piece->form[$x][$y] === '#') {
                    $aux = [];
                    while ($this->columns[$col]->top() > $position['y'] - $y) {
                        $aux[] = $this->columns[$col]->pop();
                    }
                    $this->columns[$col]->push($position['y'] - $y);
                    while ($el = array_pop($aux)) {
                        $this->columns[$col]->push($el);
                    }
                }
            }
        }
    }

    public function __toString(): string
    {
        $output = "";
        $lastExtraction = [];
        for ($i = $this->getTowerHeight(); $i >= 0; $i--) {
            $output .= "|";
            foreach ($this->columns as $col => $stack) {
                if ($stack->isEmpty()) {
                    $output .= " ";
                } elseif ($stack->top() === $i) {
                    $output .= "#";
                    $lastExtraction = $stack->pop();
                } else {
                    $output .= ".";
                }
            }
            $output .= "|$i\n";
        }
        return $output;
    }

    public function trim(int $height): int
    {
        $before = array_sum(array_map(fn (SplStack $el) => $el->count(), $this->columns));
        array_walk($this->columns, function (SplStack $el, int $key) use ($height) {
            while ($el->bottom() < $height) {
                $el->shift();
            }
        });
        $after = array_sum(array_map(fn (SplStack $el) => $el->count(), $this->columns));
        return $before - $after;
    }
}