<?php
declare(strict_types=1);

include('common.php');
$input = getInput();

print "Part 1: " . countElements($input, 10) . "\n";
print "Part 2: " . countElements($input, 40) . "\n";

function countElements(array $input, $steps): int
{
    $polymer = $input[0];
    unset($input[0]);

    $replacements = getReplacements($input);
    for ($i = 0; $i < $steps; $i++) {
        $polymer = polymerise($polymer, $replacements);
        print "Length after step " . ($i + 1) . ' = ' . strlen($polymer) . "\n";
    }

    return getPolymerScore($polymer);
}

function getReplacements(array $lines): array
{
    $replacements = [];
    foreach ($lines as $rule) {
        $parts = explode(' -> ', $rule);
        $search = $parts[0];
        $replace = $parts[0][0] . $parts[1];
        $replacements[$search] = $replace;
    }
    return $replacements;
}

function polymerise(string $polymer, array $replacements): string
{
    $newPolymer = '';
    $max = strlen($polymer) - 1;
    for ($i = 0; $i < $max; $i++) {
        $source = $polymer[$i] . $polymer[$i + 1];
        $newPolymer .= $replacements[$source];
    }
    $newPolymer .= $polymer[$i];

    return $newPolymer;
}

function getPolymerScore(string $polymer): int
{
    $counts = [];
    for ($i = 0; $i < strlen($polymer); $i++) {
        $char = $polymer[$i];
        if (array_key_exists($char, $counts)) {
            $counts[$char]++;
        } else {
            $counts[$char] = 1;
        }
    }

    sort($counts);
    return end($counts) - reset($counts);
}
