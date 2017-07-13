<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Filter;

use CountLang\Collection\Collection;
use CountLang\Collection\CollectionDecorator;
use CountLang\Entity\Entity;
use CountLang\Entity\EntityMap;

/**
 * Filter class that filters Collection object.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Filter
 * @link      http://github.com/countlang/countlang
 */
class Filter
{
    /* ********** numeric, string, bool and array operators ********** */

    /** @var string Operator equals. */
    const OPERATOR_EQ  = 'eq';
    /** @var string Operator not-equals. */
    const OPERATOR_NEQ = 'neq';

    /* ********** numeric, string and bool operators ********** */

    /** @var string Operator is-null. */
    const OPERATOR_IS_NULL     = 'isNull';
    /** @var string Operator is-not-null. */
    const OPERATOR_IS_NOT_NULL = 'isNotNull';

    /* ********** numeric and string operators ********** */

    /** @var string Operator in. */
    const OPERATOR_IN     = 'in';
    /** @var string Operator not-in. */
    const OPERATOR_NOT_IN = 'notIn';

    /* ********** numeric operators ********** */

    /** @var string Operator greater-than. */
    const OPERATOR_GT  = 'gt';
    /** @var string Operator greater-than-or-equals. */
    const OPERATOR_GTE = 'gte';
    /** @var string Operator less-than. */
    const OPERATOR_LT  = 'lt';
    /** @var string Operator greater-than-or-equals. */
    const OPERATOR_LTE = 'lte';
    /** @var string Operator between. */
    const OPERATOR_BTW = 'btw';

    /* ********** string operators ********** */

    /** @var string Operator like. */
    const OPERATOR_LIKE     = 'like';
    /** @var string Operator not-like. */
    const OPERATOR_NOT_LIKE = 'notLike';

    /* ********** array operators ********** */

    /** @var string Operator has. */
    const OPERATOR_HAS          = 'has';
    /** @var string Operator not-has. */
    const OPERATOR_NOT_HAS      = 'notHas';
    /** @var string Operator is-empty. */
    const OPERATOR_IS_EMPTY     = 'isEmpty';
    /** @var string Operator is-not-empty. */
    const OPERATOR_IS_NOT_EMPTY = 'isNotEmpty';

    /** @var Collection Collection object. */
    private $collection;

    /**
     * Collection object that will be processed is required in constructor.
     *
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
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
    public function applyFilter($field, $value = null, $operator = self::OPERATOR_EQ)
    {
        $fieldType = $this->getFieldType($field);

        if (!array_key_exists($fieldType, $this->getAllowedOperators())) {
            // @codeCoverageIgnoreStart
            throw new \UnexpectedValueException(
                "There are no available filters for field {$field} with type {$fieldType}."
            );
            // @codeCoverageIgnoreEnd
        }

        if (!in_array($operator, $this->getAllowedOperators()[$fieldType], true)) {
            $allowedOperators = implode(',', $this->getAllowedOperators()[$fieldType]);

            throw new \InvalidArgumentException(
                "Operator {$operator} is not allowed for field {$field}. Allowed operators: [{$allowedOperators}]"
            );
        }

        return $this->$operator($field, $value, $fieldType);
    }

    /**
     * Returns Collection object after applying list of filters to it.
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
    public function applyFilters(array $filters = [])
    {
        $collection = $this->collection;

        foreach ($filters as $filter) {
            $collection = call_user_func_array([$this, 'applyFilter'], $filter);
        }

        return $collection;
    }

    /**
     * Returns Entity object from Collection object after applying filter that identify Entity.
     *
     * @param string $identifier
     *
     * @return Entity|null
     *
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function findEntityByIdentifier($identifier)
    {
        $identifiersNames = $this->getIdentifiersNames();
        $entityDecorator = (new CollectionDecorator($this->collection))->getEntityDecorator();

        foreach ($this->collection->getEntities() as $entity) {
            $identifiersValues = [];

            array_walk($identifiersNames, function($identifierName) use ($entityDecorator, $entity, &$identifiersValues) {
                $propertyValue = (array)$entityDecorator->getPropertyValue($entity, $identifierName);

                $identifiersValues = array_merge($identifiersValues, array_filter($propertyValue));
            });

            $identifiersValues = array_map('strtolower', $identifiersValues);

            if (in_array(strtolower($identifier), $identifiersValues, true)) {
                return $entity;
            }
        }

        return null;
    }

    /**
     * Returns Collection object after applying equals filter.
     *
     * @param string $field
     * @param mixed  $value
     * @param string $type
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function eq($field, $value, $type)
    {
        $this->checkType(self::OPERATOR_EQ, $field, $value, $type);

        $filterFunction = function($propertyValue, $searchableValue) {
            return $propertyValue === $searchableValue;
        };

        return $this->executeFilter($field, $value, $filterFunction);
    }

    /**
     * Returns Collection object after applying not-equals filter.
     *
     * @param string $field
     * @param mixed  $value
     * @param string $type
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function neq($field, $value, $type)
    {
        $this->checkType(self::OPERATOR_NEQ, $field, $value, $type);

        $filterFunction = function($propertyValue, $searchableValue) {
            return $propertyValue !== $searchableValue;
        };

        return $this->executeFilter($field, $value, $filterFunction);
    }

    /**
     * Returns Collection object after applying is-null filter.
     *
     * @param string $field
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function isNull($field)
    {
        $filterFunction = function($propertyValue, $searchableValue) {
            return $propertyValue === $searchableValue;
        };

        return $this->executeFilter($field, null, $filterFunction);
    }

    /**
     * Returns Collection object after applying is-not-null filter.
     *
     * @param string $field
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function isNotNull($field)
    {
        $filterFunction = function($propertyValue, $searchableValue) {
            return $propertyValue !== $searchableValue;
        };

        return $this->executeFilter($field, null, $filterFunction);
    }

    /**
     * Returns Collection object after applying in-array filter.
     *
     * @param string $field
     * @param mixed  $value
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function in($field, $value)
    {
        $this->checkType(self::OPERATOR_IN, $field, $value, EntityMap::TYPE_ARRAY);

        $filterFunction = function($propertyValue, $searchableValues) {
            return in_array($propertyValue, $searchableValues, true);
        };

        return $this->executeFilter($field, $value, $filterFunction);
    }

    /**
     * Returns Collection object after applying not-in-array filter.
     *
     * @param string $field
     * @param mixed  $value
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function notIn($field, $value)
    {
        $this->checkType(self::OPERATOR_NOT_IN, $field, $value, EntityMap::TYPE_ARRAY);

        $filterFunction = function($propertyValue, $searchableValues) {
            return !in_array($propertyValue, $searchableValues, true);
        };

        return $this->executeFilter($field, $value, $filterFunction);
    }

    /**
     * Returns Collection object after applying greater-than filter.
     *
     * @param string $field
     * @param mixed  $value
     * @param string $type
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function gt($field, $value, $type)
    {
        $this->checkType(self::OPERATOR_GT, $field, $value, $type);

        $filterFunction = function($propertyValue, $searchableValues) {
            return $propertyValue > $searchableValues;
        };

        return $this->executeFilter($field, $value, $filterFunction);
    }

    /**
     * Returns Collection object after applying greater-than-or-equals filter.
     *
     * @param string $field
     * @param mixed  $value
     * @param string $type
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function gte($field, $value, $type)
    {
        $this->checkType(self::OPERATOR_GTE, $field, $value, $type);

        $filterFunction = function($propertyValue, $searchableValues) {
            return $propertyValue >= $searchableValues;
        };

        return $this->executeFilter($field, $value, $filterFunction);
    }

    /**
     * Returns Collection object after applying less-than filter.
     *
     * @param string $field
     * @param mixed  $value
     * @param string $type
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function lt($field, $value, $type)
    {
        $this->checkType(self::OPERATOR_LT, $field, $value, $type);

        $filterFunction = function($propertyValue, $searchableValues) {
            return $propertyValue < $searchableValues;
        };

        return $this->executeFilter($field, $value, $filterFunction);
    }

    /**
     * Returns Collection object after applying less-than-or-equals filter.
     *
     * @param string $field
     * @param mixed  $value
     * @param string $type
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function lte($field, $value, $type)
    {
        $this->checkType(self::OPERATOR_LTE, $field, $value, $type);

        $filterFunction = function($propertyValue, $searchableValues) {
            return $propertyValue <= $searchableValues;
        };

        return $this->executeFilter($field, $value, $filterFunction);
    }

    /**
     * Returns Collection object after applying between filter.
     *
     * @param string $field
     * @param mixed  $value
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function btw($field, $value)
    {
        $this->checkType(self::OPERATOR_BTW, $field, $value, EntityMap::TYPE_ARRAY);

        if (count($value) !== 2) {
            throw new \InvalidArgumentException(
                'Value for filter btw should have lower border in 0 key and upper in 1.'
            );
        }

        $filterFunction = function($propertyValue, $searchableValues) {
            return $propertyValue <= $searchableValues[0] && $propertyValue >= $searchableValues[1];
        };

        return $this->executeFilter($field, $value, $filterFunction);
    }

    /**
     * Returns Collection object after applying like filter.
     *
     * @param string $field
     * @param string $value
     * @param string $type
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function like($field, $value, $type)
    {
        $this->checkType(self::OPERATOR_LIKE, $field, $value, $type);

        $filterFunction = function($propertyValue, $searchableValue) {
            return strpos($propertyValue, $searchableValue) !== false;
        };

        return $this->executeFilter($field, $value, $filterFunction);
    }

    /**
     * Returns Collection object after applying not-like filter.
     *
     * @param string $field
     * @param string $value
     * @param string $type
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function notLike($field, $value, $type)
    {
        $this->checkType(self::OPERATOR_NOT_LIKE, $field, $value, $type);

        $filterFunction = function($propertyValue, $searchableValue) {
            return strpos($propertyValue, $searchableValue) === false;
        };

        return $this->executeFilter($field, $value, $filterFunction);
    }

    /**
     * Returns Collection object after applying array-has-value filter.
     *
     * @param string $field
     * @param string $value
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function has($field, $value)
    {
        $filterFunction = function($propertyValue, $searchableValue) {
            return in_array($searchableValue, $propertyValue, true);
        };

        return $this->executeFilter($field, $value, $filterFunction);
    }

    /**
     * Returns Collection object after applying array-not-has-value filter.
     *
     * @param string $field
     * @param string $value
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function notHas($field, $value)
    {
        $filterFunction = function($propertyValue, $searchableValue) {
            return !in_array($searchableValue, $propertyValue, true);
        };

        return $this->executeFilter($field, $value, $filterFunction);
    }

    /**
     * Returns Collection object after applying array-is-empty filter.
     *
     * @param string $field
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function isEmpty($field)
    {
        $filterFunction = function($propertyValue) {
            return is_array($propertyValue) && 0 === count($propertyValue);
        };

        return $this->executeFilter($field, null, $filterFunction);
    }

    /**
     * Returns Collection object after applying array-is-not-empty filter.
     *
     * @param string $field
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function isNotEmpty($field)
    {
        $filterFunction = function($propertyValue) {
            return is_array($propertyValue) && 0 !== count($propertyValue);
        };

        return $this->executeFilter($field, null, $filterFunction);
    }

    /**
     * Returns type of Entity object field by field name.
     *
     * @param string $fieldName
     *
     * @return string
     *
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function getFieldType($fieldName)
    {
        return (new CollectionDecorator($this->collection))->getEntityDecorator()
                                                           ->getEntityMap()
                                                           ->getPropertyParams($fieldName)[EntityMap::MAP_PARAM_TYPE];
    }

    /**
     * Returns array of allowed operators grouped by field type.
     *
     * @return array
     */
    private function getAllowedOperators()
    {
        return [
            EntityMap::TYPE_STRING => [
                self::OPERATOR_EQ,
                self::OPERATOR_NEQ,
                self::OPERATOR_IS_NULL,
                self::OPERATOR_IS_NOT_NULL,
                self::OPERATOR_IN,
                self::OPERATOR_NOT_IN,
                self::OPERATOR_LIKE,
                self::OPERATOR_NOT_LIKE,
            ],
            EntityMap::TYPE_INTEGER => [
                self::OPERATOR_EQ,
                self::OPERATOR_NEQ,
                self::OPERATOR_IS_NULL,
                self::OPERATOR_IS_NOT_NULL,
                self::OPERATOR_IN,
                self::OPERATOR_NOT_IN,
                self::OPERATOR_GT,
                self::OPERATOR_GTE,
                self::OPERATOR_LT,
                self::OPERATOR_LTE,
                self::OPERATOR_BTW,
            ],
            EntityMap::TYPE_DOUBLE => [
                self::OPERATOR_EQ,
                self::OPERATOR_NEQ,
                self::OPERATOR_IS_NULL,
                self::OPERATOR_IS_NOT_NULL,
                self::OPERATOR_IN,
                self::OPERATOR_NOT_IN,
                self::OPERATOR_GT,
                self::OPERATOR_GTE,
                self::OPERATOR_LT,
                self::OPERATOR_LTE,
                self::OPERATOR_BTW,
            ],
            EntityMap::TYPE_BOOLEAN => [
                self::OPERATOR_EQ,
                self::OPERATOR_NEQ,
                self::OPERATOR_IS_NULL,
                self::OPERATOR_IS_NOT_NULL,
            ],
            EntityMap::TYPE_ARRAY => [
                self::OPERATOR_EQ,
                self::OPERATOR_NEQ,
                self::OPERATOR_IS_EMPTY,
                self::OPERATOR_IS_NOT_EMPTY,
                self::OPERATOR_HAS,
                self::OPERATOR_NOT_HAS,
            ],
        ];
    }

    /**
     * Throws exception if filter value not the same as field type.
     *
     * @param string $filterName
     * @param string $field
     * @param mixed  $value
     * @param string $type
     *
     * @throws \InvalidArgumentException
     */
    private function checkType($filterName, $field, $value, $type)
    {
        if (gettype($value) !== $type) {
            throw new \InvalidArgumentException("Filter {$filterName} for field {$field} allowed only values of type {$type}.");
        }
    }

    /**
     * Returns Collection object after applying filter to Entities objects fields in current Collection.
     *
     * @param string   $field
     * @param mixed    $value
     * @param \Closure $filterFunction
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function executeFilter($field, $value,\Closure $filterFunction)
    {
        $collectionClass = get_class($this->collection);
        $collectionDecorator = new CollectionDecorator(new $collectionClass());
        $entityDecorator = $collectionDecorator->getEntityDecorator();

        $results = array_filter(
            $this->collection->getEntities(),
            function($element) use ($entityDecorator, $field, $value, $filterFunction) {
                return $filterFunction($entityDecorator->getPropertyValue($element, $field), $value);
            }
        );
        $collectionDecorator->addEntities($results);

        return $this->collection = $collectionDecorator->getCollection();
    }

    /**
     * Returns list of identifiers for Entity object from current Collection.
     *
     * @return array
     *
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    private function getIdentifiersNames()
    {
        $entityMap = (new CollectionDecorator($this->collection))->getEntityDecorator()->getEntityMap();

        return array_column(array_filter($entityMap->getMap(), function($propertyParams) {
            return $propertyParams[EntityMap::MAP_PARAM_IS_IDENTIFIER];
        }), EntityMap::MAP_PARAM_NAME);
    }
}