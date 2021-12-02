<?php
declare(strict_types=1);

include('common.php');
$input = getInput();
print "Part 1: " . countIncreases($input) . "\n";
print "Part 2: " . countWindowIncreases($input) . "\n";

function countIncreases(array $points): int
{
    $increases = 0;
    $prev = null;
    foreach ($points as $point) {
        if ($prev !== null && $point > $prev) {
            $increases++;
        }

        $prev = $point;
    }

    return $increases;
}

function countWindowIncreases(array $points): int
{
    $increases = 0;
    for ($i = 3; $i < count($points); $i++) {
        $current = $points[$i - 2] + $points[$i - 1] + $points[$i];
        $previous = $points[$i - 3] + $points[$i - 2] + $points[$i - 1];
        if ($current > $previous) {
            $increases++;
        }
    }

    return $increases;
}
