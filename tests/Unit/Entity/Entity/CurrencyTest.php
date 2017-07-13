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
 * Unit test for CountLang\Entity\Entity\Currency class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 *
 * @coversDefaultClass CountLang\Entity\Entity\Currency
 */
class CurrencyTest extends UnitTestCase
{
    /**
     * @covers ::getOfficialName
     */
    public function testGetOfficialName()
    {
        $this->assertEquals('Danish Krone', $this->getEntity()->getOfficialName());
    }

    /**
     * @covers ::getShortName
     */
    public function testGetShortName()
    {
        $this->assertEquals('Krone', $this->getEntity()->getShortName());
    }

    /**
     * @covers ::getAltNames
     */
    public function testGetAltNames()
    {
        $this->assertEquals([], $this->getEntity()->getAltNames());
    }

    /**
     * @covers ::getAlphaCode
     */
    public function testGetAlphaCode()
    {
        $this->assertEquals('DKK', $this->getEntity()->getAlphaCode());
    }

    /**
     * @covers ::getNumericCode
     */
    public function testGetNumericCode()
    {
        $this->assertEquals('208', $this->getEntity()->getNumericCode());
    }

    /**
     * @covers ::getSymbol
     */
    public function testGetSymbol()
    {
        $this->assertEquals('kr', $this->getEntity()->getSymbol());
    }

    /**
     * @covers ::getMagnitude
     */
    public function testGetMagnitude()
    {
        $this->assertEquals(2, $this->getEntity()->getMagnitude());
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
     * Returns Currency Entity object.
     *
     * @return Entity\Currency
     */
    private function getEntity()
    {
        return $this->getCountLang()->getCurrency('Danish Krone');
    }
}