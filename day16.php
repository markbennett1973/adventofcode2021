<?php
declare(strict_types=1);

include('common.php');
$input = getInput();

print "Part 1: " . getVersionNumbersTotal($input[0]) . "\n";
print "Part 2: " . getOuterPacketValue($input[0]) . "\n";

function getVersionNumbersTotal(string $hexPacket): int
{
    $packet = getBinaryPacket($hexPacket);
    $pos = 0;
    $versionTotal = 0;
    getPacketVersionCumulative($packet, $pos, $versionTotal);
    return $versionTotal;
}

function getOuterPacketValue(string $hexPacket): int
{
    $packet = getBinaryPacket($hexPacket);
    $pos = 0;

    return getPacketValue($packet, $pos);
}

function getBinaryPacket(string $hexPacket): array
{
    // Packet is too long to base_convert in one go
    $bin = '';
    $length = strlen($hexPacket);
    for ($i = 0; $i < $length; $i++) {
        $chunkBin = base_convert($hexPacket[$i], 16, 2);
        $paddedChunkBin = substr('0000' . $chunkBin, -4);
        $bin .= $paddedChunkBin;
    }
    return str_split($bin);
}

function getPacketVersionCumulative(array &$packet, int &$pos, int &$versionTotal)
{
    $versionTotal += getVersion($packet, $pos);
    if (getPacketType($packet, $pos) === 4) {
        getLiteralPacketValue($packet, $pos);
    } else {
        $lengthType = $packet[$pos + 6];
        if ($lengthType === '0') {
            $subPacketsLength = bindec(implode(array_slice($packet, $pos + 7, 15)));
            $pos = $pos + 7 + 15;
            $subPacketsEndPos = $pos + $subPacketsLength;
            while ($pos < $subPacketsEndPos) {
                getPacketVersionCumulative($packet, $pos, $versionTotal);
            }
        } else {
            $subPacketsCount = bindec(implode(array_slice($packet, $pos + 7, 11)));
            $pos = $pos + 7 + 11;
            for ($i = 0; $i < $subPacketsCount; $i++) {
                getPacketVersionCumulative($packet, $pos, $versionTotal);
            }
        }
    }
}

function getPacketValue(array &$packet, int &$pos)
{
    $packetType = getPacketType($packet, $pos);

    if ($packetType === 4) {
        return getLiteralPacketValue($packet, $pos);
    }

    $values = [];
    $lengthType = $packet[$pos + 6];
    if ($lengthType === '0') {
        $subPacketsLength = bindec(implode(array_slice($packet, $pos + 7, 15)));
        $pos = $pos + 7 + 15;
        $subPacketsEndPos = $pos + $subPacketsLength;
        while ($pos < $subPacketsEndPos) {
            $values[] = getPacketValue($packet, $pos);
        }
    } else {
        $subPacketsCount = bindec(implode(array_slice($packet, $pos + 7, 11)));
        $pos = $pos + 7 + 11;
        for ($i = 0; $i < $subPacketsCount; $i++) {
            $values[] = getPacketValue($packet, $pos);
        }
    }

    switch ($packetType) {
        case 0:
            return array_sum($values);

        case 1:
            return array_product($values);

        case 2:
            return min($values);

        case 3:
            return max($values);

        case 5:
            return $values[0] > $values[1] ? 1 : 0;

        case 6:
            return $values[0] < $values[1] ? 1 : 0;

        case 7:
            return $values[0] === $values[1] ? 1 : 0;
    }

}


function getVersion(array $packet, int $pos): int
{
    $versionBin = implode('', array_slice($packet, $pos, 3));
    return (int) base_convert($versionBin, 2, 10);
}

function getPacketType(array $packet, int $pos): int
{
    $versionBin = implode('', array_slice($packet, $pos + 3, 3));
    return (int) base_convert($versionBin, 2, 10);
}

function getLiteralPacketValue(array $packet, int &$pos): int
{
    // Move past the header
    $pos += 6;
    $valueBinString = '';
    $indicator = '1';
    while ($indicator === '1') {
        $chunk = array_slice($packet, $pos, 5);
        $indicator = array_shift($chunk);
        $valueBinString .= implode('', $chunk);
        $pos += 5;
    }

    return bindec($valueBinString);
}
