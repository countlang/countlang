<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Tests\Unit\Entity\Entity;

use CountLang\Tests\Unit\UnitTestCase;
use CountLang\Entity\Entity;
use CountLang\Collection\Collection;

/**
 * Unit test for CountLang\Entity\Entity\Region class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 *
 * @coversDefaultClass CountLang\Entity\Entity\Region
 */
class RegionTest extends UnitTestCase
{
    /**
     * @covers ::getCode
     */
    public function testGetCode()
    {
        $this->assertEquals('150', $this->getEntity()->getCode());
    }

    /**
     * @covers ::getName
     */
    public function testGetName()
    {
        $this->assertEquals('Europe', $this->getEntity()->getName());
    }

    /**
     * @covers ::getSubRegionsCollection
     */
    public function testGetSubRegionsCollection()
    {
        $this->assertInstanceOf(Collection\SubRegion::class, $this->getEntity()->getSubRegionsCollection());
        $this->assertInstanceOf(Collection\SubRegion::class, $this->getEntity()->getSubRegionsCollection());
    }

    /**
     * @covers ::getCountriesCollection
     */
    public function testGetCountriesCollection()
    {
        $this->assertInstanceOf(Collection\Country::class, $this->getEntity()->getCountriesCollection());
        $this->assertInstanceOf(Collection\Country::class, $this->getEntity()->getCountriesCollection());
    }

    /**
     * Returns Region Entity object.
     *
     * @return Entity\Region
     */
    private function getEntity()
    {
        return $this->getCountLang()->getRegion('Europe');
    }
}