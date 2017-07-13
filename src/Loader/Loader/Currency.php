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
use CountLang\Collection\Collection\Currency as CurrencyCollection;
use CountLang\Collection\CollectionDecorator;

/**
 * Loads Currency Collection object from source file.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Loader
 * @link      http://github.com/countlang/countlang
 */
class Currency extends Loader
{
    /** @var string Source file name for Currency Collection object. */
    const CURRENCIES_SOURCE_FILE_NAME = 'currency.json';

    /**
     * Generates Currency Collection object from source file.
     *
     * @return CurrencyCollection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    protected function generateCollection()
    {
        $collectionDecorator = new CollectionDecorator(new CurrencyCollection());
        $rawData = $this->getSourceFileData();

        array_walk($rawData, function($currency) use ($collectionDecorator) {
            $entityArray = $this->prepareSourceFileData($currency);

            $entity = $collectionDecorator->getEntityDecorator()->generateEntityFromArray($entityArray);
            $collectionDecorator->addEntity($entity);
        });

        return $collectionDecorator->getCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function getSourceFileName()
    {
        return self::CURRENCIES_SOURCE_FILE_NAME;
    }
}