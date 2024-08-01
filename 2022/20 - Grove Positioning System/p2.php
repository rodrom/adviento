<?php

declare(strict_types=1);

class Node {

    public int $v;
    public Node|null $next;
    public Node|null $prev;

    public function __construct(int $v)
    {
        $this->v = $v;
        $this->next = null;
        $this->prev = null;
    }
}

class CircularList implements Countable
{
    const BOTH_DIRECTION = 0;
    const FRONT_DIRECTION = 1;
    const BACK_DIRECTION = 2;

    public int $elements;
    public Node|null $head;
    public Node|null $current;
    public int $index;
    public int $decryption;
    public array $original;

    public function __construct(int $decryption)
    {
        $this->elements = 0;
        $this->head = null;
        $this->current = $this->head;
        $this->index = 0;
        $this->decryption = $decryption;

    }

    public function count(): int
    {
        return $this->elements;
    }

    public function initialize(array &$values): int
    {
        foreach ($values as $k => $v) {
            $this->push($v * $this->decryption);
            $this->original[$k] = $this->head->prev;
            if ($v === 0) {
                $this->index = $k;
            }
        }
        return $this->count();
    }

    public function push(int $v): void
    {
        if ($this->head === null) {
            $this->head = new Node($v);
            $this->head->next = $this->head;
            $this->head->prev = $this->head;
            $this->elements = 1;
            $this->current = $this->head;
        } elseif ($this->elements === 1) {
            $new = new Node($v);
            $this->head->next = $new;
            $this->head->prev = $new;
            $new->next = $this->head;
            $new->prev = $this->head;
            $this->elements = 2;
        } else {
            $last = $this->head->prev;
            $new = new Node($v);
            $new->prev = $last;
            $new->next = $this->head;
            $this->head->prev = $new;
            $last->next = $new;
            $this->elements++;
        }
    }

    public function mix(): void
    {
        if ($this->elements < 3) {
            return;
        }
        // Save 
        $next = $this->current->next;
        $prev = $this->current->prev;
        $current = $this->current;
        $move = $this->current->v;

        if ($move === 0) {
            return;
        }

        if ($this->head === $current) {
            $this->head = $current->next;
        }
        
        $prev->next = $next;
        $next->prev = $prev;

        if ($move < 0) {
            $this->backward($move);
        } else {
            $this->fordward($move);
        }

        $this->current->next->prev = $current;
        $current->next = $this->current->next;
        $current->prev = $this->current;
        $this->current->next = $current;
        
        $this->current = $this->head;
    }

    public function nth(int $n): int
    {
        $n = $n % $this->elements;
        $c = $this->original[$this->index];
        while ($n > 0) {
            $c = $c->next;
            $n--;
        }
        return $c->v;
    }

    private function backward(int $move): void
    {
        $move = $move % ($this->elements - 1);
        while ($move <= 0) {
            $this->current = $this->current->prev;
            $move++;
        }
    }

    private function fordward(int $move): void
    {
        $move = $move % ($this->elements - 1);
        while ($move > 0) {
            $this->current = $this->current->next;
            $move--;
        }
    }

    public function __toString(): string
    {
        if ($this->head === null) {
            return "()\n";
        }
        $output = "";
        $c = $this->head;
        $output .= "(";
        do {
            $output .= "$c->v";
            $c = $c->next;
            if ($c !== $this->head) {
                $output .= " -> ";
            }
        } while ($c !== $this->head);
        $output .= ")\n(";
        do {
            $output .= "$c->v";
            $c = $c->prev;
            if ($c !== $this->head) {
                $output .= " <- ";
            }
        } while ($c !== $this->head);
        $output .= ")\n";
        return $output;
    }

    public function check(int $direction = self::BOTH_DIRECTION): int
    {
        $n = $this->head;
        $p = $this->head;
        $i = 0;
        $condition = match ($direction) {
            self::BOTH_DIRECTION => fn ($n, $p) : bool => $n->next !== $this->head && $p->prev !== $this->head,
            self::FRONT_DIRECTION => fn ($n, $p) : bool => $n->next !== $this->head,
            self::BACK_DIRECTION => fn ($n, $p) : bool => $p->prev !== $this->head,
        };
        while ($condition($n,$p)) {
            $i++;
            $n = $n->next;
            $p = $p->prev;
        }
        return $i;
    }
}

$decryption = 811589153;
$e = array_map(intval(...), file($argv[1],FILE_IGNORE_NEW_LINES));
$c = new CircularList($decryption);
$n = $c->initialize($e);
$d = $c::FRONT_DIRECTION;
$M = 10;

for ($i = 0; $i < $M; $i++) {
// echo "BEGIN: {$c->check($d)}\n\n";
    foreach ($c->original as $k => $node) {
        //echo "MIX NODE $node->v \n";
        $c->current = $node;
        $c->mix();
        // echo "key: $k # ITEMS: {$c->check($d)} # VALUE: $node->v\n";
    }
    // echo "ROUND $i\n$c";
}
$r = 0;

// echo "END: Circular list in both directions {$c->check($d)}.\n\n";
foreach ([1000, 2000, 3000] as $n) {
    $x = $c->nth($n);
    // echo "$x ";
    $r += $x;
}
echo "\nSum Grove coordinates: $r\n";