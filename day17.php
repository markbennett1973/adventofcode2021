<?php
declare(strict_types=1);

include('common.php');
ini_set('memory_limit', '2G');
$input = getInput();
$target = new Target($input[0]);

print "Part 1: " . getHighestPointFromAll($target) . "\n";
print "Part 2: " . getPossibleVelocities($target) . "\n";
// 148, based on 100,100 is too low

function getHighestPointFromAll(Target $target): int
{
    $highest = 0;
    for ($xVelocity = 1; $xVelocity <= 100; $xVelocity++) {
        for ($yVelocity = 1; $yVelocity <= 100; $yVelocity++) {
            $highest = max($highest, getHighestPoint($xVelocity, $yVelocity, $target));
        }
    }

    return $highest;
}

function getPossibleVelocities(Target $target): int
{
    $count = 0;
    for ($xVelocity = 1; $xVelocity <= 300; $xVelocity++) {
        for ($yVelocity = -100; $yVelocity <= 300; $yVelocity++) {
            if (isOnTarget($xVelocity, $yVelocity, $target) > 0) {
                $count++;
            }
        }
    }

    return $count;
}

function getHighestPoint(int $xVelocity, int $yVelocity, Target $target): int
{
    $x = $y = $maxY = 0;
    while ($target->getPosition($x, $y) !== Target::OVER) {
        $x += $xVelocity;
        $y += $yVelocity;

        if ($xVelocity > 0) {
            $xVelocity--;
        } elseif ($xVelocity < 0) {
            $xVelocity++;
        }

        $yVelocity--;
        $maxY = max($maxY, $y);

        if ($target->getPosition($x, $y) === Target::IN_TARGET) {
            return $maxY;
        }
    }

    // We went over the target without landing in it
    return 0;
}

function isOnTarget(int $xVelocity, int $yVelocity, Target $target): bool
{
    $x = $y = 0;
    while ($target->getPosition($x, $y) !== Target::OVER) {
        $x += $xVelocity;
        $y += $yVelocity;

        if ($xVelocity > 0) {
            $xVelocity--;
        } elseif ($xVelocity < 0) {
            $xVelocity++;
        }

        $yVelocity--;

        if ($target->getPosition($x, $y) === Target::IN_TARGET) {
            return true;
        }
    }

    // We went over the target without landing in it
    return false;
}

class Target
{
    const OVER = 0;
    const UNDER = 1;
    const IN_TARGET = 2;

    private int $xMin;
    private int $xMax;
    private int $yMin;
    private int $yMax;

    public function __construct(string $targetString)
    {
        // e.g. target area: x=20..30, y=-10..-5
        preg_match('/x=([-\d]+)..([-\d]+), y=([-\d]+)..([-\d]+)/', $targetString, $matches);
        $this->xMin = (int) $matches[1];
        $this->xMax = (int) $matches[2];
        $this->yMin = (int) $matches[3];
        $this->yMax = (int) $matches[4];
    }

    public function getPosition(int $x, int $y): int
    {
        if ($x > $this->xMax || $y < $this->yMin) {
            return Target::OVER;
        }

        if ($x < $this->xMin || $y > $this->yMax) {
            return Target::UNDER;
        }

        return Target::IN_TARGET;
    }
}
