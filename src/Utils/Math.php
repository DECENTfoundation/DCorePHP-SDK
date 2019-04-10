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
        $number = gmp_strval(gmp_init($number, 10), 16);
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

    // Return byte Array
    public static function writeUnsignedVarInt($value): array
    {
        $v = $value;
        $byteArrayList = [];
        $i = 0;
        while (($v & -0x80) !== 0) {
            $byteArrayList[$i++] = self::toByte($v & 0x7F | 0x80);
            // Shift right
            $v >>= 7;
        }

        $byteArrayList[$i] = self::toByte($v & 0x7F);
        return $byteArrayList;
    }

    public static function writeUnsignedVarIntHex(int $value): string
    {
        return self::byteArrayToHex(self::writeUnsignedVarInt($value));
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
}
