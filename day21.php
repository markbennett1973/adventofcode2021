<?php
declare(strict_types=1);

include('common.php');

$players = getPlayers(getInput());
print "Part 1: " . playGame($players) . "\n";
// print "Part 2: " . countLitPixels($image, $algorithm, 50) . "\n";

/**
 * @param array $input
 * @return array|Player[]
 */
function getPlayers(array $input): array
{
    $players = [];
    foreach ($input as $line) {
        $players[] = new Player($line);
    }
    return $players;
}

/**
 * @param array|Player[] $players
 * @return int
 */
function playGame(array $players): int
{
    $dice = new Dice();
    while (true) {
        foreach ($players as $index => $player) {
            $player->move($dice->rollDice(3));
            if ($player->score >= 1000) {
                $otherPlayer = $index === 0 ? 1 : 0;
                return $players[$otherPlayer]->score * $dice->rolls;
            }
        }
    }
}

class Player
{
    public int $position;
    public int $score;

    public function __construct(string $input)
    {
        preg_match('/[\d]+$/', $input, $matches);
        $this->position = (int) $matches[0];
        $this->score = 0;
    }

    public function move(int $spaces)
    {
        $this->position += $spaces;
        $this->position = $this->position % 10;
        if ($this->position === 0) {
            $this->position = 10;
        }
        $this->score += $this->position;
    }
}

class Dice
{
    private int $next = 1;
    public int $rolls = 0;

    /**
     * @param int $rolls
     *   number of times to roll the dice
     * @return int
     *   score from rolling dice that many times
     */
    public function rollDice(int $rolls): int
    {
        $total = 0;

        for ($i = 0; $i < $rolls; $i++) {
            $total += $this->next;
            $this->next++;
            $this->rolls++;

            if ($this->next > 100) {
                $this->next = 1;
            }
        }

        return $total;
    }
}
