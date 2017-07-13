<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Loader\Loader;

use CountLang\Loader\Loader;
use CountLang\Collection\Collection\Country as CountryCollection;
use CountLang\Collection\CollectionDecorator;

/**
 * Loads Country Collection object from source file.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Loader
 * @link      http://github.com/countlang/countlang
 */
class Country extends Loader
{
    /** @var string Source file name for Country Collection object. */
    const COUNTRIES_SOURCE_FILE_NAME = 'country.json';

    /**
     * Generates Country Collection object from source file.
     *
     * @return CountryCollection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    protected function generateCollection()
    {
        $collectionDecorator = new CollectionDecorator(new CountryCollection());
        $rawData = $this->getSourceFileData();

        array_walk($rawData, function($country) use ($collectionDecorator) {
            $entityArray = $this->prepareSourceFileData($country);

            $entity = $collectionDecorator->getEntityDecorator()->generateEntityFromArray($entityArray);
            $collectionDecorator->addEntity($entity);
        });

        return $collectionDecorator->getCollection();
    }

    /**
     * {@inheritDoc}
     */
    protected function getSourceFileName()
    {
        return self::COUNTRIES_SOURCE_FILE_NAME;
    }
}