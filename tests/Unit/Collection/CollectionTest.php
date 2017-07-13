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

use CountLang\Tests\Unit\UnitTestCase;
use CountLang\Entity\Entity;
use CountLang\Collection\Collection;

/**
 * Unit test for CountLang\Collection\Collection class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 *
 * @coversDefaultClass CountLang\Collection\Collection
 */
class CollectionTest extends UnitTestCase
{
    /**
     * @covers ::getEntity
     */
    public function testGetEntity()
    {
        $countryCollection = $this->getCountLang()->getCountries();

        $this->assertInstanceOf(Entity\Country::class, $countryCollection->getEntity());
        $this->assertNull($countryCollection->getEntity(-1));
    }

    /**
     * @covers ::getEntities
     */
    public function testGetEntities()
    {
        $this->assertInternalType('array', $this->getCountLang()->getCountries()->getEntities());
    }

    /**
     * @covers ::count
     */
    public function testCount()
    {
        $this->assertTrue($this->getCountLang()->getCountries()->count() > 0);
    }

    /**
     * @covers ::isEmpty
     */
    public function testIsEmpty()
    {
        $this->assertFalse($this->getCountLang()->getCountries()->isEmpty());
    }

    /**
     * @covers ::filter
     */
    public function testFilter()
    {
        $this->assertInstanceOf(
            Collection\Country::class,
            $this->getCountLang()->getCountries()->filter('officialName', 'Kingdom of Denmark')
        );
    }

    /**
     * @covers ::multiFilter
     */
    public function testMultiFilter()
    {
        $this->assertInstanceOf(
            Collection\Country::class,
            $this->getCountLang()->getCountries()->multiFilter([
                ['officialName', 'Kingdom of Denmark'],
                ['regionCode', '150']
            ])
        );
    }

    /**
     * @covers ::findEntity
     */
    public function testFindEntity()
    {
        $this->assertInstanceOf(
            Entity\Country::class,
            $this->getCountLang()->getCountries()->findEntity('Kingdom of Denmark')
        );
    }

    /**
     * @covers ::select
     */
    public function testSelect()
    {
        $this->assertInternalType('array', $this->getCountLang()->getCountries()->select());

        $this->expectException(\InvalidArgumentException::class);
        $this->assertInternalType('array', $this->getCountLang()->getCountries()->select('name'));
    }

    /**
     * @covers ::__toString
     */
    public function testToString()
    {
        $this->assertInternalType('string', (string)$this->getCountLang()->getCountries());
    }
}