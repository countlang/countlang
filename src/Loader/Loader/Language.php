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
use CountLang\Collection\Collection\Language as LanguageCollection;
use CountLang\Collection\CollectionDecorator;

/**
 * Loads Language Collection object from source file.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Loader
 * @link      http://github.com/countlang/countlang
 */
class Language extends Loader
{
    /** @var string Source file name for Language Collection object. */
    const LANGUAGE_SOURCE_FILE_NAME = 'language.json';

    /**
     * Generates Language Collection object from source file.
     *
     * @return LanguageCollection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    protected function generateCollection()
    {
        $collectionDecorator = new CollectionDecorator(new LanguageCollection());
        $rawData = $this->getSourceFileData();

        array_walk($rawData, function($country) use ($collectionDecorator) {
            $entityArray = $this->prepareSourceFileData($country);

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
        return self::LANGUAGE_SOURCE_FILE_NAME;
    }
}