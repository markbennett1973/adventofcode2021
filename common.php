<?php
declare(strict_types=1);

/**
 * Read the input.txt file and return as an array of all non-empty lines
 * @param bool $removeBlankLines
 * @return array
 */
function getInput(bool $removeBlankLines = true): array
{
    $lines = explode("\n", file_get_contents('input.txt'));

    if ($removeBlankLines) {
        $lines = array_filter($lines, function ($line) {
            return $line !== '';
        });
    }

    return $lines;
}
