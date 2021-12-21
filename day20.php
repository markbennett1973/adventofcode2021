<?php
declare(strict_types=1);

include('common.php');
$algorithm = getAlgorithm();
$image = getImage();

print "Part 1: " . countLitPixels($image, $algorithm, 2) . "\n";
print "Part 2: " . countLitPixels($image, $algorithm, 50) . "\n";

function getAlgorithm(): string
{
    $input = getInput();
    $algorithm = $input[0];
    return str_replace(['.', '#'], ['0', '1'], $algorithm);
}

function getImage(): array
{
    $input = getInput();
    unset($input[0]);
    unset($input[1]);

    $map = [];
    foreach ($input as $line) {
        $map[] = array_map(function ($cell) {
            return $cell === '.' ? 0 : 1;
        }, str_split($line));
    }

    return $map;
}

function countLitPixels(array $image, string $algorithm, int $steps): int
{
    $image = extendImage($image, $steps * 2);
    for ($i = 0; $i < $steps; $i++) {
        $image = applyAlgorithm($image, $algorithm, $i);
    }

    $image = reduceImage($image, $steps);
    //printImage($image);

    $lit = 0;
    foreach ($image as $row) {
        $lit += array_sum($row);
    }
    return $lit;
}

/**
 * Extend image by $steps pixels in each direction
 * @param array $image
 * @param int $steps
 * @return array
 */
function extendImage(array $image, int $steps): array
{
    $newImage = [];
    $newWidth = count($image[0]) + ($steps * 2);
    for ($i = 0; $i < $steps; $i++) {
        $newImage[] = array_fill(0, $newWidth, 0);
    }
    foreach ($image as $row) {
        $newImage[] = array_merge(array_fill(0, $steps, 0), $row, array_fill(0, $steps, 0));
    }
    for ($i = 0; $i < $steps; $i++) {
        $newImage[] = array_fill(0, $newWidth, 0);
    }

    return $newImage;
}

function applyAlgorithm(array $image, string $algorithm, int $step): array
{
    $newImage = [];
    $rows = count($image);
    $cols = count($image[0]);
    for ($row = 0; $row < $rows; $row++) {
        for ($col = 0; $col < $cols; $col++) {
            $newImage[$row][$col] = applyAlgorithmToPoint($image, $algorithm, $row, $col, $step);
        }
    }

    return $newImage;
}

function applyAlgorithmToPoint(array &$image, string $algorithm, int $targetRow, int $targetCol, int $step): int
{
    $padChar = $step % 2 === 0 ? '1' : '0';
    $pointString = '';
    for ($row = $targetRow - 1; $row <= $targetRow + 1; $row++) {
        for ($col = $targetCol - 1; $col <= $targetCol + 1; $col++) {
            if (array_key_exists($row, $image)) {
                if (array_key_exists($col, $image[$row])) {
                    $pointString .= $image[$row][$col];
                } else {
                    $pointString .= $padChar;
                }
            } else {
                $pointString .= $padChar;
            }
        }
    }

    $index = bindec($pointString);
    return (int) $algorithm[$index];
}

function printImage(array &$image)
{
    foreach ($image as $row) {
        foreach ($row as $cell) {
            print $cell ? '#' : '.';
        }
        print "\n";
    }
    print "\n\n";
}

function reduceImage(array $image, int $amount): array
{
    $newHeight = count($image) - (2 * $amount);
    $newWidth = count($image[0]) - (2 * $amount);
    $newImage = array_slice($image, $amount, $newHeight);
    return array_map(function (array $row) use ($amount, $newWidth) {
        return array_slice($row, $amount, $newWidth);
    }, $newImage);
}
