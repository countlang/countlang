<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Tests\Unit\Collection;

use CountLang\Collection\CollectionDecorator;
use CountLang\Entity\EntityDecorator;
use CountLang\Tests\Unit\UnitTestCase;
use CountLang\Entity\Entity;
use CountLang\Collection\Collection;

/**
 * Unit test for CountLang\Collection\CollectionDecorator class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 *
 * @coversDefaultClass CountLang\Collection\CollectionDecorator
 * @covers CountLang\Collection\CollectionDecorator<extended>
 */
class CollectionDecoratorTest extends UnitTestCase
{
    /**
     * @covers ::getCollection
     */
    public function testGetCollection()
    {
        $this->assertInstanceOf(
            Collection\Country::class,
            (new CollectionDecorator($this->getCountLang()->getCountries()))->getCollection()
        );
    }

    /**
     * @covers ::getEntityDecorator
     */
    public function testGetEntityDecorator()
    {
        $this->assertInstanceOf(
            EntityDecorator::class,
            (new CollectionDecorator($this->getCountLang()->getCountries()))->getEntityDecorator()
        );
    }

    /**
     * @covers ::addEntity
     */
    public function testAddEntity()
    {
        $countriesCollection = $this->getCountLang()->getCountries();
        $countriesEntitiesAmount = count($countriesCollection->getEntities());



        (new CollectionDecorator($countriesCollection))->addEntity($countriesCollection->getEntity());

        $this->assertEquals($countriesEntitiesAmount + 1, count($countriesCollection->getEntities()));
    }

    /**
     * @covers ::addEntities
     */
    public function testAddEntities()
    {
        $countriesCollection = $this->getCountLang()->getCountries();
        $countriesEntitiesAmount = count($countriesCollection->getEntities());



        (new CollectionDecorator($countriesCollection))->addEntities($countriesCollection->getEntities());

        $this->assertEquals($countriesEntitiesAmount * 2, count($countriesCollection->getEntities()));
    }

    /**
     * @covers ::getEntityClassName
     */
    public function testGetEntityClassName()
    {
        $this->assertInternalType(
            'string',
            (new CollectionDecorator($this->getCountLang()->getCountries()))->getEntityClassName()
        );
    }
}