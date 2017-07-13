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
use CountLang\Collection\Collection\Region as RegionCollection;
use CountLang\Collection\CollectionDecorator;

/**
 * Loads Region Collection object from source file.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Loader
 * @link      http://github.com/countlang/countlang
 */
class Region extends Loader
{
    /** @var string Source file name for Region Collection object. */
    const REGIONS_SOURCE_FILE_NAME = 'region.json';

    /**
     * Generates Region Collection object from source file.
     *
     * @return RegionCollection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    protected function generateCollection()
    {
        $collectionDecorator = new CollectionDecorator(new RegionCollection());
        $rawData = $this->getSourceFileData()['regions'];

        array_walk($rawData, function($region) use ($collectionDecorator) {
            $entity = $collectionDecorator->getEntityDecorator()->generateEntityFromArray([
                'code' => $region['code'],
                'name' => $region['name'],
            ]);

            $collectionDecorator->addEntity($entity);
        });

        return $collectionDecorator->getCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function getSourceFileName()
    {
        return self::REGIONS_SOURCE_FILE_NAME;
    }
}