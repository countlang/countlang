<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Collection;

use CountLang\Cache\ObjectCache;
use CountLang\Entity\Entity;
use CountLang\Entity\EntityDecorator;

/**
 * Decorator for Collection object.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Collection
 * @link      http://github.com/countlang/countlang
 */
class CollectionDecorator
{
    /** @var Collection Collection object. */
    private $collection;

    /**
     * Constructor requires Collection object that will be processed.
     *
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Returns Collection object
     *
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Returns EntityDecorator object.
     *
     * @return EntityDecorator
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getEntityDecorator()
    {
        $init = function($entityClassName) {
            // @codeCoverageIgnoreStart
            return new EntityDecorator($entityClassName);
            // @codeCoverageIgnoreEnd
        };
        $initParams = [$this->getEntityClassName()];

        return (new ObjectCache())->fetchObject(EntityDecorator::class, $init, $initParams);
    }

    /**
     * Adds Entity object to the Collection object.
     *
     * @param Entity $entity
     *
     * @throws \DomainException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function addEntity(Entity $entity)
    {
        $reflectionClass = (new \ReflectionClass($this->getCollection()))->getParentClass();
        $reflectionProperty = $reflectionClass->getProperty('entities');

        /** @var array $entities */
        $entities = $this->getCollection()->getEntities();
        $entities[] = $entity;

        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->getCollection(), $entities);
        $reflectionProperty->setAccessible(false);
    }

    /**
     * Adds array of Entity objects to the Collection object.
     *
     * @param Entity[] $entities
     *
     * @throws \DomainException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function addEntities(array $entities)
    {
        array_walk($entities, function($entity) {
            $this->addEntity($entity);
        });
    }

    /**
     * Returns class name of Entity object that could be added to Collection object.
     *
     * @return string
     */
    public function getEntityClassName()
    {
        $reflectionMethod = new \ReflectionMethod($this->getCollection(), 'getEntityClassName');

        $reflectionMethod->setAccessible(true);
        $entityClassName = $reflectionMethod->invoke($this->getCollection());
        $reflectionMethod->setAccessible(false);

        return $entityClassName;
    }
}