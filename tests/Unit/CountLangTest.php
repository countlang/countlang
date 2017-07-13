<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Tests\Unit;

use CountLang\Entity\Entity;
use CountLang\Collection\Collection;
use CountLang\Filter\Filter;

/**
 * Unit test for CountLang\CountLang class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 *
 * @coversDefaultClass CountLang\CountLang
 */
class CountLangTest extends UnitTestCase
{
    /**
     * @covers ::getRegion
     * @covers ::getRegionCollection
     */
    public function testGetRegion()
    {
        $this->assertInstanceOf(Entity\Region::class, $this->getCountLang()->getRegion('Europe'));
        $this->assertNull($this->getCountLang()->getRegion(null));
    }

    /**
     * @covers ::getRegions
     * @covers ::getRegionCollection
     */
    public function testGetRegions()
    {
        $this->assertInstanceOf(Collection\Region::class, $this->getCountLang()->getRegions());
    }

    /**
     * @covers ::getSubRegion
     * @covers ::getSubRegionCollection
     */
    public function testGetSubRegion()
    {
        $this->assertInstanceOf(Entity\SubRegion::class, $this->getCountLang()->getSubRegion('Northern Europe'));
        $this->assertNull($this->getCountLang()->getSubRegion(null));
    }

    /**
     * @covers ::getSubRegions
     * @covers ::getSubRegionCollection
     */
    public function testGetSubRegions()
    {
        $this->assertInstanceOf(Collection\SubRegion::class, $this->getCountLang()->getSubRegions());
    }

    /**
     * @covers ::getCountry
     * @covers ::getCountryCollection
     */
    public function testGetCountry()
    {
        $this->assertInstanceOf(Entity\Country::class, $this->getCountLang()->getCountry('Denmark'));
        $this->assertNull($this->getCountLang()->getCountry(null));
    }

    /**
     * @covers ::getCountries
     * @covers ::getCountryCollection
     */
    public function testGetCountries()
    {
        $this->assertInstanceOf(Collection\Country::class, $this->getCountLang()->getCountries());
    }

    /**
     * @covers ::getCurrency
     * @covers ::getCurrencyCollection
     */
    public function testGetCurrency()
    {
        $this->assertInstanceOf(Entity\Currency::class, $this->getCountLang()->getCurrency('Danish Krone'));
        $this->assertNull($this->getCountLang()->getCurrency(null));
    }

    /**
     * @covers ::getCurrencies
     * @covers ::getCurrencyCollection
     */
    public function testGetCurrencies()
    {
        $this->assertInstanceOf(Collection\Currency::class, $this->getCountLang()->getCurrencies());
    }

    /**
     * @covers ::getLanguage
     * @covers ::getLanguageCollection
     */
    public function testGetLanguage()
    {
        $this->assertInstanceOf(Entity\Language::class, $this->getCountLang()->getLanguage('Danish'));
        $this->assertNull($this->getCountLang()->getLanguage(null));
    }

    /**
     * @covers ::getLanguages
     * @covers ::getLanguageCollection
     */
    public function testGetLanguages()
    {
        $this->assertInstanceOf(Collection\Language::class, $this->getCountLang()->getLanguages());
    }

    /**
     * @covers CountLang\CountLang<extended>
     */
    public function testCountLang()
    {
        $this->assertInstanceOf(Collection\Country::class, $this->getCountLang()->getCountries());
        $this->assertInstanceOf(
            Collection\Country::class,
            $this->getCountLang()->getCountries(['population', 50, Filter::OPERATOR_GT])
        );
        $this->assertInstanceOf(
            Collection\Country::class,
            $this->getCountLang()->getCountries([
                ['population', 50, Filter::OPERATOR_LT],
                ['population', 0, Filter::OPERATOR_GT],
            ])
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->assertInstanceOf(
            Collection\Country::class,
            $this->getCountLang()->getCountries([
                null
            ])
        );
    }
}