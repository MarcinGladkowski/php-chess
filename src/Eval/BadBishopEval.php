<?php

declare(strict_types=1);

namespace Chess\Eval;

use Chess\Board;
use Chess\PGN\AN\Color;

class BadBishopEval extends AbstractForkEval
{
    const NAME = 'Bad bishop';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Color::W => 0,
            Color::B => 0,
        ];
    }

    public function eval(): array
    {
        $this->result = [
            Color::W => 0,
            Color::B => 1,
        ];

        return $this->result;
    }
}
