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

use CountLang\Cache\ObjectCache;

/**
 * Decorator for Entity object.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Entity
 * @link      http://github.com/countlang/countlang
 */
class EntityDecorator
{
    /** @var string Entity object class name. */
    private $entityClassName;

    /** @var \ReflectionClass ReflectionClass object for Entity object. */
    private $reflectionClass;

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
     * Returns EntityMap object.
     *
     * @return EntityMap
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getEntityMap()
    {
        $init = function($entityClassName) {
            // @codeCoverageIgnoreStart
            $entityMap = new EntityMap($entityClassName);
            $entityMap->getMap();

            return $entityMap;
            // @codeCoverageIgnoreEnd
        };
        $initParams = [$this->getEntityClassName()];

        return (new ObjectCache)->fetchObject(EntityMap::class, $init, $initParams);
    }

    /**
     * Returns array of ReflectionProperty objects that marked as private of Entity object.
     *
     * @return \ReflectionProperty[]
     *
     * @throws \UnexpectedValueException
     */
    public function getProperties()
    {
        return $this->getReflectionClass()->getProperties(\ReflectionProperty::IS_PRIVATE);
    }

    /**
     * Return array of ReflectionProperty objects that exists in EntityMap of Entity object.
     *
     * @return \ReflectionProperty[]
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getPropertiesList()
    {
        $mapPropertiesNames = array_keys($this->getEntityMap()->getMap());

        return array_filter($this->getProperties(), function($property) use ($mapPropertiesNames) {
            /** @var \ReflectionProperty $property */
            return in_array($property->getName(), $mapPropertiesNames, true);
        });
    }

    /**
     * Return array of properties names of Entity object.
     *
     * @return array
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getPropertiesNamesList()
    {
        return array_map(function($property) {
            /** @var \ReflectionProperty $property */
            $property->getName();
        }, $this->getPropertiesList());
    }

    /**
     * Returns associative array with properties values of Entity object.
     *
     * @param Entity $entity
     *
     * @return array
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getPropertiesValuesList(Entity $entity)
    {
        $propertiesValuesList = [];
        $propertiesList = $this->getPropertiesList();

        array_walk($propertiesList, function($property) use (&$propertiesValuesList, $entity) {
            /** @var \ReflectionProperty $property */
            $property->setAccessible(true);
            $propertiesValuesList[$property->getName()] = $property->getValue($entity);
            $property->setAccessible(false);
        });

        return $propertiesValuesList;
    }

    /**
     * Returns value of requested property of Entity object
     *
     * @param Entity $entity
     * @param string $propertyName
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function getPropertyValue(Entity $entity, $propertyName)
    {
        if (!$this->getReflectionClass()->hasProperty($propertyName)) {
            // @codeCoverageIgnoreStart
            throw new \InvalidArgumentException(
                "There are no property with name {$propertyName} in {$this->reflectionClass->getShortName()} Entity."
            );
            // @codeCoverageIgnoreEnd
        }

        $property = $this->getReflectionClass()->getProperty($propertyName);

        $property->setAccessible(true);
        $propertyValue = $property->getValue($entity);
        $property->setAccessible(false);

        return $propertyValue;
    }

    /**
     * Sets value of requested property in Entity object
     *
     * @param Entity $entity
     * @param string $propertyName
     * @param mixed  $propertyValue
     *
     * @throws \InvalidArgumentException
     */
    public function setPropertyValue(Entity $entity, $propertyName, $propertyValue)
    {
        if (!$this->getReflectionClass()->hasProperty($propertyName)) {
            // @codeCoverageIgnoreStart
            throw new \InvalidArgumentException(
                "There are no property with name {$propertyName} in {$this->reflectionClass->getShortName()} Entity."
            );
            // @codeCoverageIgnoreEnd
        }

        $property = $this->getReflectionClass()->getProperty($propertyName);

        $property->setAccessible(true);
        $property->setValue($entity, $propertyValue);
        $property->setAccessible(false);
    }

    /**
     * Creates new Entity object from array with key names that corresponded to properties names of Entity object.
     *
     * @param array $entityArray
     *
     * @return Entity
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function generateEntityFromArray(array $entityArray)
    {
        $entityClassName = $this->getEntityClassName();
        $entity = new $entityClassName();

        foreach ($this->getEntityMap()->getMap() as $propertyName => $propertyParams) {
            if (array_key_exists($propertyParams[EntityMap::MAP_PARAM_SOURCE], $entityArray)) {
                // @codeCoverageIgnoreStart
                $this->setPropertyValue(
                    $entity,
                    $propertyName,
                    $entityArray[$propertyParams[EntityMap::MAP_PARAM_SOURCE]]
                );
                // @codeCoverageIgnoreEnd
            } elseif($propertyParams[EntityMap::MAP_PARAM_IS_REQUIRED]) {
                throw new \DomainException(
                    "Unfilled required property {$propertyName} in {$this->getReflectionClass()->getShortName()} Entity."
                );
            }
        }

        return $entity;
    }

    /**
     * Returns Entity object class name.
     *
     * @return string
     */
    private function getEntityClassName()
    {
        return $this->entityClassName;
    }

    /**
     * Returns ReflectionClass object for Entity object.
     *
     * @return \ReflectionClass
     */
    private function getReflectionClass()
    {
        if (null !== $this->reflectionClass) {
            return $this->reflectionClass;
        }

        return $this->reflectionClass = new \ReflectionClass($this->getEntityClassName());
    }
}