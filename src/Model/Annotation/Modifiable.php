<?php

namespace DCorePHP\Model\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Modifiable
{
    /** @Enum({"nobody", "issuer", "owner", "both"}) */
    public $modifiable;
}