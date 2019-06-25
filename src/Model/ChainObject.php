<?php

namespace DCorePHP\Model;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validation;

class ChainObject
{
    public const NULL_OBJECT = '1.0.0';

    /** @var string */
    private $id;

    /**
     * @param string $id
     * @throws ValidationException
     */
    public function __construct(string $id)
    {
        $this->setId($id);
    }

    /**
     * @param string $nameOrId
     * @return bool
     */
    public static function isValid(string $nameOrId): bool
    {
        return !(Validation::createValidator()->validate($nameOrId, [
            new Regex(['pattern' => '/^(\d+)\.(\d+)\.(\d+)(?:\.(\d+))?$/', 'message' => "ID \"{$nameOrId}\" do not match pattern \"/^(\\d+)\\.(\\d+)\\.(\\d+)(?:\\.(\\d+))?\$/\""]),
        ])->count() > 0);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return ChainObject
     * @throws ValidationException
     */
    public function setId(string $id): ChainObject
    {
        if (($violations = Validation::createValidator()->validate($id, [
            new Regex(['pattern' => '/^(\d+)\.(\d+)\.(\d+)(?:\.(\d+))?$/', 'message' => "ID \"{$id}\" do not match pattern \"/^(\\d+)\\.(\\d+)\\.(\\d+)(?:\\.(\\d+))?\$/\""]),
        ]))->count() > 0) {
            throw new ValidationException($violations);
        }

        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function toBytes(): string
    {
        [$space, $type, $instance] = explode('.', $this->getId());

        return VarInt::encodeDecToHex($instance);
    }

    /**
     * @return string
     */
    public function toObjectTypeIdBytes(): string
    {
        [$space, $type, $instance] = explode('.', $this->getId());

        return Math::gmpDecHex($instance) . str_pad(
            Math::getInt8($type) . Math::getInt8($space),
            16 - strlen(Math::gmpDecHex($instance)),
            '0',
            STR_PAD_LEFT
        );
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getId();
    }
}
