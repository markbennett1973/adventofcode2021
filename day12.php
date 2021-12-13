<?php
declare(strict_types=1);

include('common.php');
$input = getInput();

print "Part 1: " . countPaths($input, false) . "\n";
print "Part 2: " . countPaths($input, true) . "\n";

function countPaths(array $input, bool $allowRepeat): int
{
    $connections = getConnections($input);
    $paths[] = new Path(['start']);

    while (countPossibleSteps($paths, $connections, $allowRepeat) > 0) {
        $paths = buildNewPaths($paths);
    }

    $completedPaths = 0;
    foreach ($paths as $path) {
        if ($path->reachedEnd()) {
            $completedPaths++;
        }
    }

    return $completedPaths;
}

function getConnections($input): array {
    $connections = [];

    foreach ($input as $connection) {
        list($a, $b) = explode('-', $connection);
        if (!array_key_exists($a, $connections)) {
            $connections[$a] = [];
        }

        if (!array_key_exists($b, $connections)) {
            $connections[$b] = [];
        }

        if (!in_array($b, $connections[$a])) {
            $connections[$a][] = $b;
        }

        if (!in_array($a, $connections[$b])) {
            $connections[$b][] = $a;
        }
    }

    return $connections;
}

/**
 * @param array|Path[] $paths
 * @param array $connections
 * @param bool $allowRepeat
 * @return int
 */
function countPossibleSteps(array $paths, array $connections, bool $allowRepeat): int
{
    $steps = 0;
    foreach ($paths as $path) {
        $steps += $path->calculatePossibleSteps($connections, $allowRepeat);
    }

    return $steps;
}

/**
 * @param array|Path[] $paths
 * @return array|Path[]
 */
function buildNewPaths(array $paths): array
{
    $newPaths = [];
    foreach ($paths as $path) {
        $nextSteps = $path->getPossibleSteps();
        if (count($nextSteps) === 0) {
            $newPaths[] = $path;
        } else {
            foreach ($nextSteps as $nextStep) {
                $newPaths[] = $path->getNewPath($nextStep);
            }
        }
    }

    return $newPaths;
}

class Path
{
    private array $steps;
    private bool $isSmallCaveVisited = false;
    private array $possibleSteps;

    public function __construct(array $steps)
    {
        $this->steps = $steps;

        // have we already visited a small cave?
        $smallCaves = array_filter($steps, function ($step) {
            if (strtoupper($step) === $step) {
                return false;
            }

            if ($step === 'start' || $step === 'end') {
                return false;
            }

            return true;
        });

        if (count($smallCaves) !== count(array_unique($smallCaves))) {
            $this->isSmallCaveVisited = true;
        }
    }

    public function calculatePossibleSteps(array $connections, bool $allowRepeat): int
    {
        $this->possibleSteps = [];
        $currentStep = end($this->steps);

        if ($currentStep === 'end') {
            // We've reached the end - no more possible steps
            return 0;
        }

        foreach ($connections[$currentStep] as $possibleStep) {
            if ($this->isAllowedNextStep($possibleStep, $allowRepeat)) {
                $this->possibleSteps[] = $possibleStep;
            }
        }

        return count($this->possibleSteps);
    }

    public function getPossibleSteps(): array
    {
        return $this->possibleSteps;
    }

    private function isAllowedNextStep(string $nextStep, bool $allowRepeats): bool
    {
        // If it's uppercase, it's always a possible step
        if ($nextStep === strtoupper($nextStep)) {
            return true;
        }

        // 'start' is never valid as a next step
        if ($nextStep === 'start') {
            return false;
        }

        // If we don't allow repeats, or we've already visited a small cave, this step is only
        // allowed if we've not already been there
        if ($allowRepeats === false || $this->isSmallCaveVisited) {
            return !in_array($nextStep, $this->steps);
        }

        // We allow repeats, and we haven't used one yet
        return true;
    }

    public function getNewPath(string $nextStep): Path
    {
        $newSteps = array_merge($this->steps, [$nextStep]);
        return new Path($newSteps);
    }

    public function reachedEnd(): bool
    {
        return end($this->steps) === 'end';
    }
}
