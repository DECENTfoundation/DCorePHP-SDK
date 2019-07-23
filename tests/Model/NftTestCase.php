<?php

namespace DCorePHPTests\Model;

use Doctrine\Common\Annotations\AnnotationRegistry;
use PHPUnit\Framework\TestCase;

abstract class NftTestCase extends TestCase
{
    public function __construct()
    {
        parent::__construct();
        // Loading Custom Annotation classes
        AnnotationRegistry::registerLoader(static function($name){
            return class_exists($name);
        });
    }
}