<?php

namespace DCorePHP\Model;

use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;
use GMP;
use InvalidArgumentException;

class Variant
{
    public const INT64_TYPE = 1;
    public const UINT64_TYPE = 2;
    public const BOOL_TYPE = 4;
    public const STRING_TYPE = 5;

    public static function toByte($value): string
    {
        if ($value instanceof GMP) {
            $type = gmp_cmp($value, 0) >= 0 ? self::UINT64_TYPE : self::INT64_TYPE;
            return '0' . $type . Math::getInt64(gmp_strval($value));
        } elseif (getType($value) === 'string') {
            return '0' . self::STRING_TYPE .
                VarInt::encodeDecToHex(sizeof(Math::stringToByteArray($value))) .
                Math::byteArrayToHex(Math::stringToByteArray($value));
        } elseif (is_bool($value)) {
            return  '0' . self::BOOL_TYPE . ($value ? '01' : '00');
        } else {
            throw new InvalidArgumentException('Type '. gettype($value) .' is not allowed!');
        }
    }
}