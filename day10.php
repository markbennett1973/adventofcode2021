<?php
declare(strict_types=1);

include('common.php');

const CHUNK_MARKERS = [
    ')' => '(',
    ']' => '[',
    '}' => '{',
    '>' => '<',
];

$input = getInput();

print "Part 1: " . getSyntaxErrorScores($input) . "\n";
print "Part 2: " . getAutocompleteScores($input) . "\n";

function getSyntaxErrorScores(array $lines): int
{
    $score = 0;
    foreach ($lines as $line) {
        $score += getSyntaxErrorScore($line);
    }

    return $score;
}

function getSyntaxErrorScore(string $line): int
{
    $scores = [
        ')' => 3,
        ']' => 57,
        '}' => 1197,
        '>' => 25137,
    ];

    try {
        analyseLine($line);
    } catch (Exception $ex) {
        return $scores[$ex->getMessage()];
    }

    return 0;
}

/**
 * @param string $line
 * @return array
 *   opening tags
 * @throws Exception
 *   contains first expected closing tag if mismatched with opening tags
 */
function analyseLine(string $line): array
{


    $chars = [];
    for ($i = 0; $i < strlen($line); $i++) {
        $char = $line[$i];
        // Is this an open marker?
        if (in_array($char, CHUNK_MARKERS)) {
            array_push($chars, $line[$i]);
        }

        // Is this a close marker?
        if (in_array($char, array_keys(CHUNK_MARKERS))) {
            // Get the expected opener for our current close marker
            $openChar = CHUNK_MARKERS[$char];
            $lastOpen = array_pop($chars);
            if ($lastOpen !== $openChar) {
                // The last opener was not what this closer needs
                throw new Exception($char);
            }
        }
    }

    // We successfully closed all chunks
    return $chars;
}

function getAutocompleteScores(array $lines): int
{
    $scores = [];
    foreach ($lines as $line) {
        $score = getAutocompleteScore($line);
        if ($score > 0) {
            $scores[] = $score;
        }
    }

    sort($scores);
    $mid = floor(count($scores) / 2);
    return $scores[$mid];
}

function getAutocompleteScore(string $line): int
{
    $scores = [
        ')' => 1,
        ']' => 2,
        '}' => 3,
        '>' => 4,
    ];

    try {
        $chars = analyseLine($line);
        $score = 0;
        while ($openChar = array_pop($chars)) {
            $closeChar = array_flip(CHUNK_MARKERS)[$openChar];
            $score = ($score * 5) + $scores[$closeChar];
        }

        return $score;
    } catch (Exception $ex) {
        return 0;
    }
}
