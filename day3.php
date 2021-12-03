<?php
declare(strict_types=1);

include('common.php');
$input = getInput();
print "Part 1: " . getPower($input) . "\n";
print "Part 2: " . getLifeSupport($input) . "\n";

function getPower($lines): int
{
    $gamma = $epsilon = [];
    $length = strlen($lines[0]);
    for ($i = 0; $i < $length; $i++) {
        $mostCommon = findMostCommon($lines, $i);
        $gamma[$i] = $mostCommon;
        $epsilon[$i] = $mostCommon === 0 ? 1 : 0;
    }

    $gammaValue = bindec(implode('', $gamma));
    $epsilonValue = bindec(implode('', $epsilon));
    return $gammaValue * $epsilonValue;
}

function findMostCommon(array $lines, int $position): int
{
    $zeros = $ones = 0;
    foreach ($lines as $line) {
        if ($line[$position] === "0") {
            $zeros++;
        } else {
            $ones++;
        }
    }

    return $zeros > $ones ? 0 : 1;
}

function getLifeSupport($lines): int
{
    $oxygen = getTarget($lines, true);
    $scrubber = getTarget($lines, false);
    return $oxygen * $scrubber;
}

function getTarget($lines, bool $mostCommon): int
{
    $length = strlen($lines[0]);
    for ($i = 0; $i < $length; $i++) {
        $target = findMostCommon($lines, $i);
        if ($mostCommon === false) {
            $target = $target === 0 ? 1 : 0;
        }

        // Remove lines which don't have $target in position $i
        foreach ($lines as $index => $line) {
            if ($line[$i] != $target) {
                unset($lines[$index]);
            }
        }

        // If we only have one line left, return it
        if (count($lines) === 1) {
            return bindec(reset($lines));
        }
    }

    return 0;
}
