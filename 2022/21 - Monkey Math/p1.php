<?php

declare(strict_types=1);

class Node
{
    public string $name;
    public Node|null $l;
    public Node|null $r;
    public string|int $v;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function InternalNode(string $name, string $op, Node $l, Node $r): static
    {
        $node = new static($name);
        $node->v = $op;
        $node->l = $l;
        $node->r = $r;
        
        return $node;
    }

    public static function Leaf(string $name, int $v): static
    {
        $node = new static($name);
        $node->v = $v;
        $node->l = null;
        $node->r = null;

        return $node;
    }
}

$input = array_map(
    function ($line) {
        if (preg_match('/^([a-z]{4}): ((([a-z]{4}) ([+\-*\/]) ([a-z]{4}))|(\d+))$/', $line, $matches)) {
            return strlen($matches[0]) === 17 ?
                [
                    'label' => $matches[1],
                    'left' => $matches[4],
                    'op' =>$matches[5],
                    'right' => $matches[6]
                ] 
                :
                [ 'label' => $matches[1], 'value' => intval($matches[2])];
        }
    },
    file($argv[1], FILE_IGNORE_NEW_LINES)
);

$monkeys = array_combine(array_column($input, 'label'), $input);
$rootMonkey = $monkeys['root'];

function buildTree(array $monkey, array &$monkeys): Node
{
    if (isset($monkey['value'])) {
        return Node::Leaf($monkey['label'], $monkey['value']);
    }
    $left = buildTree($monkeys[$monkey['left']], $monkeys);
    $right = buildTree($monkeys[$monkey['right']], $monkeys);
    return Node::InternalNode($monkey['label'], $monkey['op'], $left, $right);
}

$tree = buildTree($rootMonkey, $monkeys);

function getShout(Node $root): int
{
    return match($root->v) {
        '+' => getShout($root->l) + getShout($root->r),
        '-' => getShout($root->l) - getShout($root->r),
        '*' => getShout($root->l) * getShout($root->r),
        '/' => intdiv(getShout($root->l), getShout($root->r)),
        default => $root->v,
    };
}

echo getShout($tree);