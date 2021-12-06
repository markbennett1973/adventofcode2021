<?php
declare(strict_types=1);

include('common.php');
$input = getInput();
print "Part 1: " . countFish($input, 80) . "\n";
print "Part 2: " . countFish($input, 256) . "\n";

function countFish(array $lines, int $days)
{
    $fish = getFishList($lines[0]);
    for ($day = 1; $day <= $days; $day++) {
        $fish = incrementFish($fish);
    }

    return array_sum($fish);
}

function getFishList(string $list): array
{
    $fish = [];
    foreach (explode(',', $list) as $number) {
        if (array_key_exists($number, $fish)) {
            $fish[$number]++;
        } else {
            $fish[$number] = 1;
        }
    }

    return $fish;
}

function incrementFish(array $fish): array
{
    $newFish = [];
    foreach ($fish as $age => $numberOfFish) {
        if ($age !== 0) {
            $newFish[$age - 1] = $numberOfFish;
        }
    }

    if (array_key_exists(0, $fish)) {
        $newFish[8] = $fish[0];
        if (array_key_exists(6, $newFish)) {
            $newFish[6] += $fish[0];
        } else {
            $newFish[6] = $fish[0];
        }
    }

    return $newFish;
}
