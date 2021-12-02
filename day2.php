<?php
declare(strict_types=1);

include('common.php');
$input = getInput();
print "Part 1: " . getDistance($input) . "\n";
print "Part 2: " . getDistanceWithAim($input) . "\n";

function getDistance(array $moves): int
{
    $distance = $depth = 0;
    foreach ($moves as $move) {
        list($instruction, $amount) = explode(' ', $move);
        switch ($instruction) {
            case 'forward':
                $distance += $amount;
                break;

            case 'up':
                $depth -= $amount;
                break;

            case 'down':
                $depth += $amount;
                break;
        }
    }

    return $distance * $depth;
}

function getDistanceWithAim(array $moves): int
{
    $distance = $depth = $aim = 0;
    foreach ($moves as $move) {
        list($instruction, $amount) = explode(' ', $move);
        switch ($instruction) {
            case 'forward':
                $distance += $amount;
                $depth += ($aim * $amount);
                break;

            case 'up':
                $aim -= $amount;
                break;

            case 'down':
                $aim += $amount;
                break;
        }
    }

    return $distance * $depth;
}
