<?php

namespace Chess\Eval;

use Chess\Board;
use Chess\PGN\AN\Color;

class AbsoluteForkEval extends AbstractForkEval
{
    const NAME = 'Absolute fork';

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
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->isAttackingKing()) {
                $this->result[$piece->getColor()] =
                    $this->sumValues($piece, $piece->attackedPieces());
            }
        }

        return $this->result;
    }
}
