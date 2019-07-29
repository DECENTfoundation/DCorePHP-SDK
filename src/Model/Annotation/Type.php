<?php

namespace DCorePHP\Model\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Type
{
    /**
     * @Enum({"integer", "string", "boolean"})
     */
    public $value;
}