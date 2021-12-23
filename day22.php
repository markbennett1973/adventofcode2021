<?php
declare(strict_types=1);

include('common.php');

$input = getInput();
print "Part 1: " . getCubesOn($input) . "\n";
// print "Part 2: " . countLitPixels($image, $algorithm, 50) . "\n";

function getCubesOn(array $lines): int
{
    $space = [];
    foreach ($lines as $line) {
        applyInstruction(new Instruction($line), $space);
    }

    return countCubesOn($space);
}

function applyInstruction(Instruction $instruction, array &$space)
{
    if ($instruction->isValid === false) {
        return;
    }

    for ($x = $instruction->xMin; $x <= $instruction->xMax; $x++) {
        for ($y = $instruction->yMin; $y <= $instruction->yMax; $y++) {
            for ($z = $instruction->zMin; $z <= $instruction->zMax; $z++) {
                $space[$x][$y][$z] = $instruction->isOn;
            }
        }
    }
}

function countCubesOn(array $space): int
{
    $on = 0;
    foreach ($space as $x) {
        foreach ($x as $y) {
            foreach ($y as $z) {
                if ($z) {
                    $on++;
                }
            }
        }
    }

    return $on;
}

class Instruction
{
    public int $xMin;
    public int $xMax;
    public int $yMin;
    public int $yMax;
    public int $zMin;
    public int $zMax;
    public bool $isOn;
    public bool $isValid = true;

    public function __construct(string $instruction)
    {
        preg_match('/([onf]+) x=([\-\d]+)..([\-\d]+),y=([\-\d]+)..([\-\d]+),z=([\-\d]+)..([\-\d]+)/', $instruction, $matches);
        $this->isOn = $matches[1] === 'on';
        $this->xMin = min((int) $matches[2], (int) $matches[3]);
        $this->xMax = max((int) $matches[2], (int) $matches[3]);
        $this->yMin = min((int) $matches[4], (int) $matches[5]);
        $this->yMax = max((int) $matches[4], (int) $matches[5]);
        $this->zMin = min((int) $matches[6], (int) $matches[7]);
        $this->zMax = max((int) $matches[6], (int) $matches[7]);

        if ($this->xMax < -50 || $this->xMin > 50
            || $this->yMax < -50 || $this->yMin > 50
            || $this->zMax < -50 || $this->zMin > 50) {
            $this->isValid = false;
        }
    }
}
