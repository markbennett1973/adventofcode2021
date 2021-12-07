<?php
declare(strict_types=1);

include('common.php');
$input = getInput();
print "Part 1: " . getMinimumFuel($input[0], true) . "\n";
print "Part 2: " . getMinimumFuel($input[0], false) . "\n";

function getMinimumFuel(string $input, bool $useSimpleCalc)
{
    $positions = explode(',', $input);
    $minFuel = PHP_INT_MAX;
    $minPos = min($positions);
    $maxPos = max($positions);

    $fuelUsages[0] = 0;
    for ($distance = 1; $distance <= $maxPos; $distance++) {
        $fuelUsages[$distance] = $fuelUsages[$distance - 1] + $distance;
    }

    for ($newPos = $minPos; $newPos <= $maxPos; $newPos++) {
        $fuelUsed = 0;
        foreach($positions as $position) {
            $distance = abs($position - $newPos);
            if ($useSimpleCalc) {
                $fuelUsed += $distance;
            } else {
                $fuelUsed += $fuelUsages[$distance];
            }
        }

        if ($fuelUsed < $minFuel) {
            $minFuel = $fuelUsed;
        }
    }

    return $minFuel;
}
