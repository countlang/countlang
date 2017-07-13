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

/**
 * Parent class for any Entity object.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Entity
 * @link      http://github.com/countlang/countlang
 */
abstract class Entity
{
    /**
     * Returns array with properties values of Entity objects. List of returning properties could be selected.
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
        $entityDecorator = new EntityDecorator(get_class($this));

        if (is_string($fieldsList)) {
            return $entityDecorator->getPropertyValue($this, $fieldsList);
        }

        $propertiesValuesList = $entityDecorator->getPropertiesValuesList($this);

        if (null === $fieldsList || 0 === count($fieldsList)) {
            return $propertiesValuesList;
        }

        if ($missingList = array_diff($fieldsList, array_keys($propertiesValuesList))) {
            $missing = implode(', ', $missingList);
            $className = (new \ReflectionClass($this))->getShortName();
            $availableFields = implode(', ', array_keys($propertiesValuesList));

            throw new \InvalidArgumentException(
                "There are no field(s) [{$missing}] in {$className} Entity. \n\nAvailable fields are: [{$availableFields}]."
            );
        }

        return array_filter($propertiesValuesList, function($fieldName) use ($fieldsList) {
            return in_array($fieldName, $fieldsList, true);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Returns JSON string of array with properties values of Entity objects.
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