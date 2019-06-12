<?php

namespace DCorePHP\Utils;

class VarInt
{

    public static function encodeDecToHex(string $decNum): string {
        $result = [];
        do {
            $bits = $decNum & 0x7F;
            $decNum >>= 7;
            $b = $bits + ($decNum !== 0 ? 0x80 : 0);
            $result[] = sprintf('%02x', $b);
        } while($decNum !== 0);

        return implode('', $result);
    }

    private static function size($num) {
        $result = 0;
        do {
            $result++;
            $num >>= 7;
        } while($num !== 0);
        return $result;
    }

    public static function decodeHexToDec(string $hexNum): string {
        $result = 0;
        $shift = 0;
        $offset = 0;
        do {
            $b = substr($hexNum, $offset, $offset + 2);
            $b = gmp_strval(gmp_init($b, 16));
            $result = gmp_or($result, ($b & 0x7F) << $shift);
            $shift += 7;
            $offset += 2;
        } while(($b & 0x80) !== 0);
        return gmp_strval($result);
    }

}