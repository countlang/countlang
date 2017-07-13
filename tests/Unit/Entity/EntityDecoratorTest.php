<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Tests\Unit\Entity;

use CountLang\Entity\EntityDecorator;
use CountLang\Entity\EntityMap;
use CountLang\Tests\Unit\UnitTestCase;
use CountLang\Entity\Entity;

/**
 * Unit test for CountLang\Entity\EntityDecorator class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 *
 * @coversDefaultClass CountLang\Entity\EntityDecorator
 * @covers CountLang\Entity\EntityDecorator<extended>
 */
class EntityDecoratorTest extends UnitTestCase
{
    /**
     * @covers ::getEntityMap
     */
    public function testGetEntityMap()
    {
        $this->assertInstanceOf(
            EntityMap::class,
            (new EntityDecorator(Entity\Country::class))->getEntityMap()
        );
    }

    /**
     * @covers ::getProperties
     */
    public function testGetProperties()
    {
        $this->assertInternalType(
            'array',
            (new EntityDecorator(Entity\Country::class))->getProperties()
        );
    }

    /**
     * @covers ::getPropertiesList
     */
    public function testGetPropertiesList()
    {
        $this->assertInternalType(
            'array',
            (new EntityDecorator(Entity\Country::class))->getPropertiesList()
        );
    }

    /**
     * @covers ::getPropertiesNamesList
     */
    public function testGetPropertiesNamesList()
    {
        $this->assertInternalType(
            'array',
            (new EntityDecorator(Entity\Country::class))->getPropertiesNamesList()
        );
    }

    /**
     * @covers ::getPropertiesValuesList
     */
    public function testGetPropertiesValuesList()
    {
        $this->assertInternalType(
            'array',
            (new EntityDecorator(Entity\Country::class))->getPropertiesValuesList($this->getCountLang()->getCountry('Denmark'))
        );
    }

    /**
     * @covers ::getPropertyValue
     */
    public function testGetPropertyValue()
    {
        $this->assertInternalType(
            'int',
            (new EntityDecorator(Entity\Country::class))->getPropertyValue($this->getCountLang()->getCountry('Denmark'), 'population')
        );
    }

    /**
     * @covers ::setPropertyValue
     */
    public function testSetPropertyValue()
    {
        $this->assertNull(
            (new EntityDecorator(Entity\Country::class))->setPropertyValue($this->getCountLang()->getCountry('Denmark'), 'population', 100500)
        );
    }

    /**
     * @covers ::generateEntityFromArray
     */
    public function testGenerateEntityFromArray()
    {
        $this->assertInstanceOf(
            Entity\Region::class,
            (new EntityDecorator(Entity\Region::class))->generateEntityFromArray([
                'name' => 'Atlantida',
                'code' => '000',
            ])
        );

        $this->expectException(\DomainException::class);
        (new EntityDecorator(Entity\Region::class))->generateEntityFromArray([
            'name' => 'Atlantida'
        ]);
    }
}