<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Entity;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlock\Tags\Var_ as VarTag;
use CountLang\Annotation\Map;

/**
 * Maps properties of Entity object.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Entity
 * @link      http://github.com/countlang/countlang
 */
class EntityMap
{
    /** @var string Var annotation name. */
    const TAG_NAME_VAR = 'var';

    /** @var string Name section name in map annotation. */
    const MAP_PARAM_NAME          = 'name';
    /** @var string Type section name in map annotation. */
    const MAP_PARAM_TYPE          = 'type';
    /** @var string Source section name in map annotation. */
    const MAP_PARAM_SOURCE        = 'source';
    /** @var string IsIdentifier section name in map annotation. */
    const MAP_PARAM_IS_IDENTIFIER = 'isIdentifier';
    /** @var string IsRequired section name in map annotation. */
    const MAP_PARAM_IS_REQUIRED   = 'isRequired';

    /** @var string Boolean type value in map annotation. */
    const TYPE_BOOLEAN = 'boolean';
    /** @var string Integer type value in map annotation. */
    const TYPE_INTEGER = 'integer';
    /** @var string Double type value in map annotation. */
    const TYPE_DOUBLE  = 'double';
    /** @var string String type value in map annotation. */
    const TYPE_STRING  = 'string';
    /** @var string Array type value in map annotation. */
    const TYPE_ARRAY   = 'array';

    /** @var string Boolean type alias in map annotation. */
    const TYPE_BOOLEAN_ALIAS = 'bool';
    /** @var string Integer type alias in map annotation. */
    const TYPE_INTEGER_ALIAS = 'int';
    /** @var string Double type alias in map annotation. */
    const TYPE_DOUBLE_ALIAS  = 'float';

    /** @var string Entity object class name. */
    private $entityClassName;

    /** @var array Array of map values. */
    private $map;

    /** @var AnnotationReader AnnotationReader object. */
    private $annotationReader;

    /**
     * Constructor requires Entity object class name.
     *
     * @param string $entityClassName
     */
    public function __construct($entityClassName)
    {
        $this->entityClassName = $entityClassName;
    }

    /**
     * Returns associative array of map params.
     *
     * @return array
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function getMap()
    {
        if (null !== $this->map) {
            return $this->map;
        }

        $map = [];
        /** @var \ReflectionProperty $property */
        foreach ((new EntityDecorator($this->entityClassName))->getProperties() as $property) {
            $parsedPhpDoc = $this->parseDocComment($property);

            if (0 === count($parsedPhpDoc)) {
                continue;
            }

            $propertyName = $property->getName();
            $map[$propertyName] = array_merge($parsedPhpDoc, [self::MAP_PARAM_NAME => $propertyName]);
        }

        return $this->map = $map;
    }

    /**
     * Returns associative array of property params of requested property.
     *
     * @param string $propertyName
     *
     * @return array
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function getPropertyParams($propertyName)
    {
        if (!array_key_exists($propertyName, $this->getMap())) {
            $className = (new \ReflectionClass($this->entityClassName))->getShortName();
            $availableProperties = implode(', ', array_keys($this->getMap()));

            throw new \UnexpectedValueException(
                "Entity {$className} does not have property with name {$propertyName}. Available properties are: [{$availableProperties}]"
            );
        }

        return $this->getMap()[$propertyName];
    }

    /**
     * Returns associative array with parsed phpDoc comments for Entity object for requested property.
     *
     * @param \ReflectionProperty $property
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     */
    private function parseDocComment(\ReflectionProperty $property)
    {
        $docBlock = DocBlockFactory::createInstance()->create($property);
        $tagsByName = $docBlock->getTagsByName(self::TAG_NAME_VAR);

        $tag = array_pop($tagsByName);
        /** @var VarTag $tag */
        $type = str_replace(
            array_keys($this->getTypesAliasMap()),
            $this->getTypesAliasMap(),
            (string)$tag->getType()
        );
        AnnotationRegistry::registerLoader(function($className) {
            // @codeCoverageIgnoreStart
            return class_exists($className);
            // @codeCoverageIgnoreEnd
        });
        /** @var Map|null $mapAnnotation */
        $mapAnnotation = $this->getAnnotationReader()->getPropertyAnnotation($property, Map::class);
        if (null === $mapAnnotation) {
            return [];
        }

        $mapArray = $mapAnnotation->getAsArray($type);
        $this->checkDocCommentParsingErrors($property->getName(), $mapArray);

        return $mapArray;
    }

    /**
     * Checks if phpDoc comments for Entity object were parsed successfully.
     *
     * @param string $propertyName
     * @param array  $mapArray
     *
     * @return bool
     *
     * @throws \UnexpectedValueException
     *
     * @codeCoverageIgnore
     */
    private function checkDocCommentParsingErrors($propertyName, array $mapArray)
    {
        $className = (new \ReflectionClass($this))->getShortName();

        if ($missing = array_diff(array_keys($mapArray), $this->getAllowedMapParams())) {
            $missing = implode(', ', $missing);

            throw new \UnexpectedValueException(
                "Map block for property {$propertyName} in {$className} Entity has not valid param [{$missing}]."
            );
        }

        if ($missing = array_diff($this->getAllowedMapParams(), array_keys($mapArray))) {
            $missing = implode(', ', $missing);

            throw new \UnexpectedValueException(
                "Map block for property {$propertyName} in {$className} Entity missing required param [{$missing}]."
            );
        }

        if (!in_array($mapArray[self::MAP_PARAM_TYPE], $this->getAllowedPropertyTypes(), true)) {
            $type = $mapArray[self::MAP_PARAM_TYPE];

            throw new \UnexpectedValueException(
                "Map block for property {$propertyName} in {$className} Entity has not valid value for param with type {$type}."
            );
        }

        return true;
    }

    /**
     * Returns AnnotationReader object.
     *
     * @return AnnotationReader
     */
    private function getAnnotationReader()
    {
        if (null !== $this->annotationReader) {
            return $this->annotationReader;
        }

        return $this->annotationReader = new AnnotationReader();
    }

    /**
     * Returns allowed map params.
     *
     * @return array
     */
    private function getAllowedMapParams()
    {
        return [
            self::MAP_PARAM_SOURCE,
            self::MAP_PARAM_TYPE,
            self::MAP_PARAM_IS_IDENTIFIER,
            self::MAP_PARAM_IS_REQUIRED,
        ];
    }

    /**
     * Return allowed values for Entity object property types.
     *
     * @return array
     */
    private function getAllowedPropertyTypes()
    {
        return [
            self::TYPE_BOOLEAN,
            self::TYPE_INTEGER,
            self::TYPE_DOUBLE,
            self::TYPE_STRING,
            self::TYPE_ARRAY,
        ];
    }

    /**
     * Return aliases map for Entity object property types.
     *
     * @return array
     */
    private function getTypesAliasMap()
    {
        return [
            self::TYPE_BOOLEAN_ALIAS => self::TYPE_BOOLEAN,
            self::TYPE_INTEGER_ALIAS => self::TYPE_INTEGER,
            self::TYPE_DOUBLE_ALIAS  => self::TYPE_DOUBLE,
        ];
    }
}