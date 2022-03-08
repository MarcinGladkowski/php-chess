<?php

namespace Chess;

use Chess\Evaluation\InverseEvaluationInterface;
use Chess\PGN\Symbol;

class HeuristicPicture extends Player
{
    use HeuristicPictureTrait;

    /**
     * Returns the current evaluation of $this->board.
     *
     * The result obtained suggests which player is probably better.
     *
     * @return array
     */
    public function evaluate(): array
    {
        $result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];

        $weights = array_values($this->getDimensions());

        $pic = $this->take()->getPicture();

        for ($i = 0; $i < count($this->getDimensions()); $i++) {
            $result[Symbol::WHITE] += $weights[$i] * end($pic[Symbol::WHITE])[$i];
            $result[Symbol::BLACK] += $weights[$i] * end($pic[Symbol::BLACK])[$i];
        }

        $result[Symbol::WHITE] = round($result[Symbol::WHITE], 2);
        $result[Symbol::BLACK] = round($result[Symbol::BLACK], 2);

        return $result;
    }

    /**
     * Takes a normalized, balanced heuristic picture.
     *
     * @return \Chess\HeuristicPicture
     */
    public function take(): HeuristicPicture
    {
        foreach ($this->moves as $move) {
            $this->board->play(Symbol::WHITE, $move[0]);
            if (isset($move[1])) {
                $this->board->play(Symbol::BLACK, $move[1]);
            }
            $item = [];
            foreach ($this->dimensions as $className => $weight) {
                $dimension = new $className($this->board);
                $evald = $dimension->evaluate();
                if (is_array($evald[Symbol::WHITE])) {
                    if ($dimension instanceof InverseEvaluationInterface) {
                        $item[] = [
                            Symbol::WHITE => count($evald[Symbol::BLACK]),
                            Symbol::BLACK => count($evald[Symbol::WHITE]),
                        ];
                    } else {
                        $item[] = [
                            Symbol::WHITE => count($evald[Symbol::WHITE]),
                            Symbol::BLACK => count($evald[Symbol::BLACK]),
                        ];
                    }
                } else {
                    if ($dimension instanceof InverseEvaluationInterface) {
                        $item[] = [
                            Symbol::WHITE => $evald[Symbol::BLACK],
                            Symbol::BLACK => $evald[Symbol::WHITE],
                        ];
                    } else {
                        $item[] = $evald;
                    }
                }
            }
            $this->picture[Symbol::WHITE][] = array_column($item, Symbol::WHITE);
            $this->picture[Symbol::BLACK][] = array_column($item, Symbol::BLACK);
        }

        $this->normalize()->balance();

        return $this;
    }

    /**
     * Normalizes the heuristic picture of $this->board.
     *
     * The dimensions are normalized meaning that the chess features (Material,
     * Center, Connectivity, Space, Pressure, King safety, Tactics, and so on)
     * are evaluated and scaled to have values between 0 and 1.
     *
     * It is worth noting that a normalized heuristic picture changes with every
     * chess move that is made because it is recalculated or zoomed out, if you like,
     * to fit within a 0–1 range.
     *
     * @return \Chess\HeuristicPicture
     */
    protected function normalize(): HeuristicPicture
    {
        $normalization = [];

        if (count($this->board->getHistory()) >= 2) {
            for ($i = 0; $i < count($this->dimensions); $i++) {
                $values = array_merge(
                    array_column($this->picture[Symbol::WHITE], $i),
                    array_column($this->picture[Symbol::BLACK], $i)
                );
                $min = round(min($values), 2);
                $max = round(max($values), 2);
                for ($j = 0; $j < count($this->picture[Symbol::WHITE]); $j++) {
                    if ($max - $min > 0) {
                        $normalization[Symbol::WHITE][$j][$i] =
                            round(($this->picture[Symbol::WHITE][$j][$i] - $min) / ($max - $min), 2);
                        $normalization[Symbol::BLACK][$j][$i] =
                            round(($this->picture[Symbol::BLACK][$j][$i] - $min) / ($max - $min), 2);
                    } elseif ($max == $min) {
                        $normalization[Symbol::WHITE][$j][$i] = 0;
                        $normalization[Symbol::BLACK][$j][$i] = 0;
                    }
                }
            }
        } else {
            $normalization[Symbol::WHITE][] =
                $normalization[Symbol::BLACK][] = array_fill(0, count($this->dimensions), 0);
        }

        $this->picture = $normalization;

        return $this;
    }

    /**
     * Balances the heuristic picture of $this->board.
     *
     * A chess game can be plotted in terms of balance. +1 is the best possible
     * evaluation for White and -1 the best possible evaluation for Black. Both
     * forces being set to 0 means they're actually offset and, therefore, balanced.
     *
     * @return \Chess\HeuristicPicture
     */
    protected function balance(): HeuristicPicture
    {
        foreach ($this->picture[Symbol::WHITE] as $i => $color) {
            foreach ($color as $j => $val) {
                $this->balance[$i][$j] =
                    $this->picture[Symbol::WHITE][$i][$j] - $this->picture[Symbol::BLACK][$i][$j];
            }
        }

        return $this;
    }

    /**
     * Returns the last element of the heuristic picture.
     *
     * @return array
     */
    public function end(): array
    {
        return [
            Symbol::WHITE => end($this->picture[Symbol::WHITE]),
            Symbol::BLACK => end($this->picture[Symbol::BLACK]),
        ];
    }
}
