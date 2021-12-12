<?php
declare(strict_types=1);

include('common.php');
$map = getInputMap();

print "Part 1: " . getTotalFlashes($map, 100) . "\n";
print "Part 2: " . findBiggestFlash($map) . "\n";

function getTotalFlashes(array $map, int $steps): int
{
    $flashes = 0;
    for ($i = 0; $i < $steps; $i++) {
        incrementEnergy($map);
        while (processFlashes($map)) {}
        $flashes += countFlashes($map);
    }

    return $flashes;
}

function findBiggestFlash(array $map): int
{
    $steps = 1;
    $mapSize = count($map) * count($map[0]);

    while (true) {
        incrementEnergy($map);
        while (processFlashes($map)) {}
        if (countFlashes($map) === $mapSize) {
            return $steps;
        }
        $steps++;
    }
}

function incrementEnergy(array &$map)
{
    foreach ($map as $row => $cells) {
        foreach ($cells as $col => $value) {
            $map[$row][$col]++;
        }
    }
}

function processFlashes(array &$map): bool
{
    $flashed = false;
    // Any elements with a value of 10 or greater will flash
    // - increment all their neighbours and reset to zero
    foreach ($map as $row => $cells) {
        foreach ($cells as $col => $value) {
            if ($value >= 10) {
                incrementNeighbours($map, $row, $col);
                $map[$row][$col] = 0;
                $flashed = true;
            }
        }
    }

    return $flashed;
}

function incrementNeighbours(array &$map, int $sourceRow, int $sourceCol)
{
    for ($row = $sourceRow - 1; $row <= $sourceRow + 1; $row++) {
        for ($col = $sourceCol - 1; $col <= $sourceCol + 1; $col++) {
            if (isset($map[$row][$col])) {
                if ($map[$row][$col] !== 0) {
                    $map[$row][$col]++;
                }
            }
        }
    }
}

function countFlashes(array $map): int
{
    $flashes = 0;
    foreach ($map as $row => $cells) {
        foreach ($cells as $col => $value) {
            if ($value === 0) {
                $flashes++;
            }
        }
    }

    return $flashes;
}
