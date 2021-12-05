<?php
declare(strict_types=1);

include('common.php');
$input = getInput();
print "Part 1: " . findOverlaps($input, false) . "\n";
print "Part 2: " . findOverlaps($input, true) . "\n";

function findOverlaps($lines, bool $includeDiagonals): int
{
    $map = [];
    foreach ($lines as $line) {
        if ($includeDiagonals) {
            addLineToMap($line, $map);
        } else {
            addStraightLineToMap($line, $map);
        }
    }

    // printMap($map);

    $overlaps = 0;
    foreach ($map as $row) {
        foreach ($row as $cell) {
            if ($cell > 1) {
                $overlaps++;
            }
        }
    }

    return $overlaps;
}

function addStraightLineToMap(string $line, array &$map)
{
    preg_match('/([\d]+),([\d]+) -> ([\d]+),([\d]+)/', $line, $matches);
    list($dummy, $x1, $y1, $x2, $y2) = $matches;

    // ensure value 1 is less than value 2
    if ($x1 > $x2) {
        $temp = $x2;
        $x2 = $x1;
        $x1 = $temp;
    }
    if ($y1 > $y2) {
        $temp = $y2;
        $y2 = $y1;
        $y1 = $temp;
    }

    if ($x1 === $x2) {
        // vertical line
        for ($y = $y1; $y <= $y2; $y++) {
            if (isset($map[$y][$x1])) {
                $map[$y][$x1]++;
            } else {
                $map[$y][$x1] = 1;
            }
        }
    }

    if ($y1 === $y2) {
        // horizontal line
        for ($x = $x1; $x <= $x2; $x++) {
            if (isset($map[$y1][$x])) {
                $map[$y1][$x]++;
            } else {
                $map[$y1][$x] = 1;
            }
        }
    }
}

function addLineToMap(string $line, array &$map)
{
    preg_match('/([\d]+),([\d]+) -> ([\d]+),([\d]+)/', $line, $matches);
    list($dummy, $x1, $y1, $x2, $y2) = $matches;

    $row = $y1;
    $col = $x1;
    $steps = max(abs($x1-$x2), abs($y1-$y2));
    for ($i = 0; $i <= $steps; $i++) {
        if (isset($map[$row][$col])) {
            $map[$row][$col]++;
        } else {
            $map[$row][$col] = 1;
        }

        if ($x1 > $x2) {
            $col--;
        } elseif ($x1 < $x2) {
            $col++;
        }
        if ($y1 > $y2) {
            $row--;
        } elseif ($y1 < $y2) {
            $row++;
        }
    }
}

function printMap(array $map)
{
    $rowMax = $colMax = 0;
    foreach ($map as $row => $rowData) {
        if ($row > $rowMax) {
            $rowMax = $row;
        }

        foreach ($rowData as $col => $cell) {
            if ($col > $colMax) {
                $colMax = $col;
            }
        }
    }

    for ($row = 0; $row <= $rowMax; $row++) {
        for ($col = 0; $col <= $colMax; $col++) {
            if (isset($map[$row][$col])) {
                echo $map[$row][$col];
            } else {
                echo '.';
            }
        }
        echo "\n";
    }
}
