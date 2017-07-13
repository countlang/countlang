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

use CountLang\Entity\Entity;
use CountLang\Filter\Filter;

/**
 * Parent class for any Collection object.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Collection
 * @link      http://github.com/countlang/countlang
 */
abstract class Collection
{
    /** @var array Array of Entities objects. */
    private $entities = [];

    /**
     * Returns N-th element of array if it exists.
     *
     * @param int $index
     *
     * @return Entity|null
     */
    public function getEntity($index = 0)
    {
        if (array_key_exists($index, $this->entities)) {
            return $this->entities[$index];
        }

        return null;
    }

    /**
     * Returns array of Entities objects.
     *
     * @return Entity[]
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * Returns amount of Entities objects in Collection object.
     *
     * @return int
     */
    public function count()
    {
        return count($this->entities);
    }

    /**
     * Returns whether Collection object contains some Entities objects.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return 0 === $this->count();
    }

    /**
     * Returns Collection object after applying filter to it.
     *
     * @param string $field
     * @param mixed  $value
     * @param string $operator
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function filter($field, $value = null, $operator = Filter::OPERATOR_EQ)
    {
        return (new Filter($this))->applyFilter($field, $value, $operator);
    }

    /**
     * Returns Collection object after applying array of filters to it.
     *
     * @param array $filters
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function multiFilter(array $filters = [])
    {
        return (new Filter($this))->applyFilters($filters);
    }

    /**
     * Returns Entity object from Collection object after filtering by identifier.
     *
     * @param string $identifier
     *
     * @return Entity|null
     *
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function findEntity($identifier)
    {
        return (new Filter($this))->findEntityByIdentifier($identifier);
    }

    /**
     * Return class name of Entity object that could be stored in Collection object.
     *
     * @return string
     */
    abstract protected function getEntityClassName();

    /**
     * Returns multidimensional array with properties values of Entity objects in Collection object.
     * List of returning properties could be selected.
     *
     * @param array|string $fieldsList
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    public function select($fieldsList = null)
    {
        return array_map(function($entity) use ($fieldsList) {
            /** @var Entity $entity */
            return $entity->select($fieldsList);
        }, $this->getEntities());
    }

    /**
     * Returns JSON string of multidimensional array with properties values of Entity objects in Collection object.
     *
     * @return string
     */
    public function __toString()
    {
        // @codeCoverageIgnoreStart
        try {
            $fieldsArray = $this->select();
        } catch (\Exception $e) {
            $fieldsArray = [];
        }
        // @codeCoverageIgnoreEnd

        return json_encode($fieldsArray, JSON_PRETTY_PRINT|JSON_PRESERVE_ZERO_FRACTION);
    }
}