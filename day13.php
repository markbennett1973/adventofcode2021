<?php
declare(strict_types=1);

include('common.php');
$input = getInput();

print "Part 1: " . countPoints($input) . "\n";
print "Part 2:\n";
printCode($input);

function countPoints(array $input): int
{
    $points = getPoints($input);
    $folds = getFolds($input);

    foreach ($points as $point) {
        $point->applyFold($folds[0]);
    }

    return count(getUniquePoints($points));
}

function printCode(array $input)
{
    $points = getPoints($input);
    $folds = getFolds($input);

    foreach ($folds as $fold) {
        foreach ($points as $point) {
            $point->applyFold($fold);
        }
    }

    drawPoints(getUniquePoints($points));
}

/**
 * @param array|string[] $lines
 * @return array|Point[]
 */
function getPoints(array $lines): array
{
    $points = [];
    foreach ($lines as $line) {
        if (strpos($line, ',') !== false) {
            $points[] = new Point($line);
        }
    }

    return $points;
}

/**
 * @param array|string[] $lines
 * @return array|Fold[]
 */
function getFolds(array $lines): array
{
    $points = [];
    foreach ($lines as $line) {
        if (strpos($line, 'fold') !== false) {
            $points[] = new Fold($line);
        }
    }

    return $points;
}

/**
 * @param array|Point[] $points
 * @return array|Point[]
 */
function getUniquePoints(array $points): array
{
    $hashes = array_map(function (Point $point) {
        return $point->getHash();
    }, $points);

    $uniquePoints = [];
    foreach (array_unique($hashes) as $hash) {
        $uniquePoints[] = new Point($hash);
    }

    return $uniquePoints;
}

/**
 * @param array|Point[] $points
 */
function drawPoints(array $points)
{
    $xMax = $yMax = 0;
    $map = [];
    foreach ($points as $point) {
        $map[$point->x][$point->y] = '';
        $xMax = max($xMax, $point->x);
        $yMax = max($yMax, $point->y);
    }

    for ($y = 0; $y <= $yMax; $y++) {
        for ($x = 0; $x <= $xMax; $x++) {
            print isset($map[$x][$y]) ? '*' : ' ';
        }
        print "\n";
    }
}

class Point
{
    public int $x;
    public int $y;

    public function __construct(string $coords)
    {
        $points = explode(',', $coords);
        $this->x = (int) $points[0];
        $this->y = (int) $points[1];
    }

    public function applyFold(Fold $fold)
    {
        if ($fold->axis === 'x') {
            // apply fold left - subtract something from x, leave y unchanged
            // distance to fold = x - fold
            // new x = x - twice distance
            if ($this->x > $fold->value) {
                $this->x = $this->x - (2 * ($this->x - $fold->value));
            }
        } else {
            // apply fold up - subtract something from y, leave x unchanged
            // 0, 14 through 7 goes to 0, 0
            // distance to fold = y - fold
            // new y = y - twice distance
            // new y = y - 2(y - fold)
            if ($this->y > $fold->value) {
                $this->y = $this->y - (2 * ($this->y - $fold->value));
            }
        }
    }

    public function getHash(): string
    {
        return $this->x . ',' . $this->y;
    }
}

class Fold
{
    public string $axis;
    public int $value;

    public function __construct(string $fold)
    {
        $fold = str_replace('fold along ', '', $fold);
        $parts = explode('=', $fold);
        $this->axis = $parts[0];
        $this->value = (int) $parts[1];
    }
}
