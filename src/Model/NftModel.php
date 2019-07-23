<?php

namespace DCorePHP\Model;

use DCorePHP\Model\Annotation\Modifiable;
use DCorePHP\Model\Annotation\Type;
use DCorePHP\Model\Annotation\Unique;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class NftModel
{

    /**
     * @param $model
     *
     * @return array
     *
     * @throws ReflectionException
     * @throws AnnotationException
     */
    public static function createDefinitions($model): array {
        $reflection = new ReflectionClass($model);
        $reader = new AnnotationReader();
        $definitions = [];

        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $annotations = $reader->getPropertyAnnotations($property);
            $name = $property->getName();
            $type = null;
            $unique = false;
            $modifiable = NftDataType::NOBODY;
            foreach ($annotations as $annotation) {
                if ($annotation instanceof Modifiable) {
                    $modifiable = $annotation->modifiable;
                } elseif ($annotation instanceof Unique) {
                    $unique = true;
                } elseif ($annotation instanceof Type) {
                    $type = $annotation->value;
                }
            }
            $definitions[] = NftDataType::withValues($type, $unique, $modifiable, $name);
        }
        return $definitions;
    }

    /**
     * @throws ReflectionException
     */
    public function values(): array {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties();
        $values = [];

        foreach ($properties as $property) {
            $values[] = $this->getPropertyValue($property);
        }
        return $values;
    }

    /**
     * @param $data
     * @param $class
     *
     * @return object
     * @throws ReflectionException
     */
    public static function make($data, $class) {
        $reflection = new ReflectionClass($class);
        return $reflection->newInstanceArgs($data);
    }

    /**
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function createUpdate(): array {
        $reflection = new ReflectionClass($this);
        $reader = new AnnotationReader();
        $filtered = [];

        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $annotations = $reader->getPropertyAnnotations($property);
            $name = $property->getName();
            foreach ($annotations as $annotation) {
                if ($annotation instanceof Modifiable) {
                    $value = $this->getPropertyValue($property);
                    $filtered[] = $annotation->modifiable !== 'nobody' ? [$name, $value] : null;
                }
            }
        }
        return $filtered;
    }

    public static function createUpdateRaw(Nft $nft, array $values): array {
        $result = [];
        foreach ($nft->getDefinitions() as $definition) {
            if ($definition->getModifiable() !== NftDataType::NOBODY && ($definition->getName() !== '' || $definition->getName() !== null)) {
                $value = self::getValue($values, $definition->getName());
                if ($value !== null) {
                    $result[] = [$definition->getName(), $value];
                }
            }
        }
        return $result;
    }

    /**
     * @param array $values
     * @param string $name
     *
     * @return mixed|null
     */
    private static function getValue(array $values, string $name) {
        foreach ($values as $value) {
            if ($value[0] === $name) {
                return $value[1];
            }
        }
        return null;
    }

    public function getPropertyValue(ReflectionProperty $property) {
        if ($property->isPrivate()) {
            $property->setAccessible(true);
        }
        return $property->getValue($this);
    }
}