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
use CountLang\Collection\Collection\SubRegion as SubRegionCollection;
use CountLang\Collection\CollectionDecorator;

/**
 * Loads SubRegion Collection object from source file.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Loader
 * @link      http://github.com/countlang/countlang
 */
class SubRegion extends Loader
{
    /** @var string Source file name for SubRegion Collection object. */
    const SUB_REGIONS_SOURCE_FILE_NAME = 'region.json';

    /**
     * Generates SubRegion Collection object from source file.
     *
     * @return SubRegionCollection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    protected function generateCollection()
    {
        $collectionDecorator = new CollectionDecorator(new SubRegionCollection());
        $rawData = $this->getSourceFileData()['regions'];

        array_walk($rawData, function($region) use ($collectionDecorator) {
            $regionCode = $region['code'];

            array_walk($region['subRegions'], function($node) use ($regionCode, $collectionDecorator) {
                $entityArray = array_merge(
                    $this->prepareSourceFileData($node),
                    [
                        'regionCode' => $regionCode
                    ]
                );

                $entity = $collectionDecorator->getEntityDecorator()->generateEntityFromArray($entityArray);
                $collectionDecorator->addEntity($entity);
            });
        });

        return $collectionDecorator->getCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function getSourceFileName()
    {
        return self::SUB_REGIONS_SOURCE_FILE_NAME;
    }
}