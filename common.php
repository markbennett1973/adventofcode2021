<?php
declare(strict_types=1);

/**
 * Read the input.txt file and return as an array of all non-empty lines
 * @return array
 */
function getInput(): array
{
    $lines = explode("\n", file_get_contents('input.txt'));
    return array_filter($lines, function ($line) {
        return $line !== '';
    });
}
