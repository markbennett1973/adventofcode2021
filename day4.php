<?php
declare(strict_types=1);

include('common.php');
$input = getInput(false);
print "Part 1: " . playBingo($input) . "\n";
print "Part 2: " . loseBingo($input) . "\n";

function playBingo(array $input): int
{
    $numbers = explode(',', $input[0]);
    $boards = getBoards($input);

    foreach($numbers as $number) {
        foreach ($boards as $boardIndex => $board) {
            $board->applyNumber($number);
            if ($board->hasFinished()) {
                return $board->getScore() * $number;
            }
        }
    }

    return 0;
}

function loseBingo(array $input): int
{
    $numbers = explode(',', $input[0]);
    $boards = getBoards($input);

    foreach($numbers as $number) {
        foreach ($boards as $boardIndex => $board) {
            $board->applyNumber($number);

            if ($board->hasFinished()) {
                if (count($boards) === 1) {
                    return $board->getScore() * $number;
                }
                unset($boards[$boardIndex]);
            }
        }
    }

    return 0;
}

/**
 * @param $input
 * @return array|Board[]
 */
function getBoards($input): array
{
    unset($input[0]);
    unset($input[1]);

    $boards = $boardLines = [];
    foreach ($input as $line) {
        if (trim($line) === '') {
            $boards[] = new Board($boardLines);
            $boardLines = [];
        } else {
            // Strip any blank numbers from the exploded array
            $boardLines[] = array_values(array_filter(explode(' ', $line), function ($number) {
                return $number !== '';
            }));
        }
    }

    return $boards;
}

class Board
{
    private array $boardNumbers;

    public function __construct(array $numbers)
    {
        $this->boardNumbers = $numbers;
    }

    public function applyNumber($number)
    {
        foreach ($this->boardNumbers as $row => $rowNumbers) {
            foreach ($rowNumbers as $col => $boardNumber) {
                if ($boardNumber == $number) {
                    $this->boardNumbers[$row][$col] = '';
                }
            }
        }
    }

    public function hasFinished(): bool
    {
        if ($this->hasFinishedRow()) {
            return true;
        }

        if ($this->hasFinishedColumn()) {
            return true;
        }

        return false;
    }

    private function hasFinishedRow(): bool
    {
        foreach ($this->boardNumbers as $row => $rowNumbers) {
            foreach ($rowNumbers as $col => $number) {
                if ($number !== '') {
                    continue 2;
                }
            }
            return true;
        }

        return false;
    }

    private function hasFinishedColumn(): bool
    {
        for ($col = 0; $col < count($this->boardNumbers[0]); $col++) {
            foreach ($this->boardNumbers as $row => $rowNumbers) {
                if ($rowNumbers[$col] !== '') {
                    continue 2;
                }
            }
            return true;
        }

        return false;
    }

    public function getScore(): int
    {
        $score = 0;
        foreach ($this->boardNumbers as $row => $rowNumbers) {
            foreach ($rowNumbers as $number) {
                if (is_numeric($number)) {
                    $score += $number;
                }
            }
        }

        return $score;
    }

    public function printBoard()
    {
        foreach ($this->boardNumbers as $row => $rowNumbers) {
            foreach ($rowNumbers as $number) {
                if (strlen($number) === 1) echo ' ';
                if (strlen($number) === 0) echo '  ';
                echo $number . ' ';
            }
            echo "\n";
        }
        echo "\n";
    }
}
