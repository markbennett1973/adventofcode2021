<?php
declare(strict_types=1);

include('common.php');
$map = getInputMap();

print "Part 1: " . getLowPoints($map) . "\n";
print "Part 2: " . getBasins($map) . "\n";

function getLowPoints($map): int
{
    $score = 0;
    $rows = count($map);
    $cols = count($map[0]);
    for ($row = 0; $row < $rows; $row++) {
        for ($col = 0; $col < $cols; $col++) {
            if (isLowPoint($map, $row, $col)) {
                $score = $score + 1 + $map[$row][$col];
            }
        }
    }

    return $score;
}

function isLowPoint(array $map, int $row, int $col): bool
{
    $target = $map[$row][$col];
    $isLowerThanAll = true;

    // Check for a smaller value in the row above
    if (isset($map[$row - 1][$col])) {
        if ($map[$row - 1][$col] <= $target) {
            $isLowerThanAll = false;
        }
    }

    // and the row below
    if (isset($map[$row + 1][$col])) {
        if ($map[$row + 1][$col] <= $target) {
            $isLowerThanAll = false;
        }
    }

    // and the column to the left
    if (isset($map[$row][$col - 1])) {
        if ($map[$row][$col - 1] <= $target) {
            $isLowerThanAll = false;
        }
    }

    // and the column to the right
    if (isset($map[$row][$col + 1])) {
        if ($map[$row][$col + 1] <= $target) {
            $isLowerThanAll = false;
        }
    }

    return $isLowerThanAll;
}

function getBasins(array $map): int
{
    $basinSizes = [];
    while ($basinSize = getNextBasinSize($map)) {
        $basinSizes[] = $basinSize;
    }

    rsort($basinSizes);
    return $basinSizes[0] * $basinSizes[1] * $basinSizes[2];
}

function getNextBasinSize(array &$map): int
{
    // find a basin, remove it from the array, and return the size
    $rows = count($map);
    $cols = count($map[0]);

    // Find the first point in a basin
    $found = false;
    for ($row = 0; $row < $rows; $row++) {
        for ($col = 0; $col < $cols; $col++) {
            if ($map[$row][$col] != 9) {
                $found = true;
                break 2;
            }
        }
    }

    if (!$found) return 0;

    // Find all adjacent non-edge points
    $size = 0;
    countAdjacentBasinPoints($map, $row, $col, $size);

    return $size;
}

function countAdjacentBasinPoints(array &$map, int $row, int $col, int &$size)
{
    $map[$row][$col] = 9;
    $size++;

    if (isset($map[$row - 1][$col]) && $map[$row - 1][$col] != 9) {
        countAdjacentBasinPoints($map, $row - 1, $col, $size);
    }

    if (isset($map[$row + 1][$col]) && $map[$row + 1][$col] != 9) {
        countAdjacentBasinPoints($map, $row + 1, $col, $size);
    }

    if (isset($map[$row][$col - 1]) && $map[$row][$col - 1] != 9) {
        countAdjacentBasinPoints($map, $row, $col - 1, $size);
    }

    if (isset($map[$row][$col + 1]) && $map[$row][$col + 1] != 9) {
        countAdjacentBasinPoints($map, $row, $col + 1, $size);
    }
}
