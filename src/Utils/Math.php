<?php

namespace DCorePHP\Utils;

class Math
{
    /**
     * php doesn't not support unsigned integers
     * so we need to do the unsigned right shift manually
     *
     * @param int $number
     * @param int $noOfBytes
     * @return int
     */
    public static function unsignedRightShift(int $number, $noOfBytes = 0): int
    {
        if ($noOfBytes === 0) {
            return $number;
        }

        return ($number >> $noOfBytes) & ~(1 << (8 * PHP_INT_SIZE - 1) >> ($noOfBytes - 1));
    }

    /**
     * @param string $number
     * @return string
     */
    public static function reverseBytesLong(string $number): string
    {
        $number = self::gmpDecHex($number);
        if (strlen($number) % 2 !== 0) {
            $number = '0' . $number;
        }

        $number = implode('', array_reverse(str_split($number, 2)));
        $number = str_pad($number, 16, '0', STR_PAD_RIGHT);
        $number = gmp_strval(gmp_init($number, 16));

        return gmp_strval($number);
    }

    public static function reverseBytesShort(int $number)
    {
        return (($number & 0xff00) >> 8) | ($number << 8);
    }

    public static function reverseBytesInt(int $number)
    {
        return ($number << 24) | (($number & 0xff00) << 8)  | (self::unsignedRightShift($number,8) & 0xff00) | self::unsignedRightShift($number, 24);
    }

    // Cast a number to a byte
    public static function toByte($number): int
    {
        return (($number+128) % 256) - 128;
    }

    public static function byteArrayToHex(array $byteArray): string
    {
        $chars = array_map('chr', $byteArray);
        $bin = implode($chars);
        return bin2hex($bin);
    }

    public static function stringToByteArray(string $string): array
    {
        return unpack('C*', $string);
    }


    public static function byteArrayToString(array $byteArray): string
    {
        return implode(array_map('chr', $byteArray));
    }

    public static function hexToByteArray(string $string): array
    {
        $bin = hex2bin($string);
        return unpack('C*', $bin);
    }

    public static function gmpDecHex($number): string
    {
        return gmp_strval(gmp_init($number, 10), 16);
    }

    public static function getInt8($number): string
    {
        return str_pad(self::gmpDecHex($number), 2, '0', STR_PAD_LEFT);
    }

    public static function getInt16($number): string
    {
        return str_pad(self::gmpDecHex(self::reverseBytesShort($number)), 4, '0', STR_PAD_LEFT);
    }

    public static function getInt16Reversed($number): string
    {
        return implode('', array_reverse(str_split(str_pad(self::gmpDecHex($number), 4, '0', STR_PAD_LEFT), 2)));
    }

    public static function getInt32($number): string
    {
        return str_pad(self::gmpDecHex(self::reverseBytesInt($number)), 8, '0', STR_PAD_LEFT);
    }

    public static function getInt32Reversed($number): string
    {
        return implode('', array_reverse(str_split(str_pad(self::gmpDecHex($number), 8, '0', STR_PAD_LEFT), 2)));
    }

    public static function getInt64($number): string
    {
        return str_pad(self::gmpDecHex(self::reverseBytesLong($number)), 16, '0', STR_PAD_LEFT);
    }

    public static function gmpShiftLeft($number, $shiftBy): string
    {
        return gmp_strval(gmp_mul($number, gmp_pow(2, $shiftBy)));
    }

    public static function gmpShiftRight($number, $shiftBy): string
    {
        return gmp_strval(gmp_div($number, gmp_pow(2, $shiftBy)));
    }

    public static function gmpOrOnArray(array $numbers): string
    {
        $res = 0;
        foreach ($numbers as $number) {
            $res = gmp_or($res, $number);
        }
        return gmp_strval($res);
    }
}
