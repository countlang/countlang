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
 * Unit test for CountLang\Entity\Entity\Language class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 *
 * @coversDefaultClass CountLang\Entity\Entity\Language
 */
class LanguageTest extends UnitTestCase
{
    /**
     * @covers ::getOfficialName
     */
    public function testGetOfficialName()
    {
        $this->assertEquals('Danish', $this->getEntity()->getOfficialName());
    }

    /**
     * @covers ::getShortName
     */
    public function testGetShortName()
    {
        $this->assertEquals('Danish', $this->getEntity()->getShortName());
    }

    /**
     * @covers ::getAltNames
     */
    public function testGetAltNames()
    {
        $this->assertEquals([], $this->getEntity()->getAltNames());
    }

    /**
     * @covers ::getAlpha2Code
     */
    public function testGetAlpha2Code()
    {
        $this->assertEquals('da', $this->getEntity()->getAlpha2Code());
    }

    /**
     * @covers ::getAlpha3BCode
     */
    public function testGetAlpha3BCode()
    {
        $this->assertNull($this->getEntity()->getAlpha3BCode());
    }

    /**
     * @covers ::getAlpha3TCode
     */
    public function testGetAlpha3TCode()
    {
        $this->assertEquals('dan', $this->getEntity()->getAlpha3TCode());
    }

    /**
     * @covers ::isIndividualLanguage
     */
    public function testIsIndividualLanguage()
    {
        $this->assertTrue($this->getEntity()->isIndividualLanguage());
    }

    /**
     * @covers ::isMacroLanguage
     */
    public function testIsMacroLanguage()
    {
        $this->assertFalse($this->getEntity()->isMacroLanguage());
    }

    /**
     * @covers ::isSpecialCode
     */
    public function testIsSpecialCode()
    {
        $this->assertFalse($this->getEntity()->isSpecialCode());
    }

    /**
     * @covers ::isLiving
     */
    public function testIsLiving()
    {
        $this->assertTrue($this->getEntity()->isLiving());
    }

    /**
     * @covers ::isConstructed
     */
    public function testIsConstructed()
    {
        $this->assertFalse($this->getEntity()->isConstructed());
    }

    /**
     * @covers ::isAncient
     */
    public function testIsAncient()
    {
        $this->assertFalse($this->getEntity()->isAncient());
    }

    /**
     * @covers ::isExtinct
     */
    public function testIsExtinct()
    {
        $this->assertFalse($this->getEntity()->isExtinct());
    }

    /**
     * @covers ::isHistorical
     */
    public function testIsHistorical()
    {
        $this->assertFalse($this->getEntity()->isHistorical());
    }

    /**
     * @covers ::getUsagePercentage
     */
    public function testGetUsagePercentage()
    {
        $this->assertInternalType('int', $this->getCountryFirstLanguageEntity()->getUsagePercentage());
    }

    /**
     * @covers ::isOfficial
     */
    public function testIsOfficial()
    {
        $this->assertTrue($this->getCountryFirstLanguageEntity()->isOfficial());
    }

    /**
     * @covers ::isRegional
     */
    public function testIsRegional()
    {
        $this->assertFalse($this->getCountryFirstLanguageEntity()->isRegional());
    }

    /**
     * @covers ::isPopular
     */
    public function testIsPopular()
    {
        $this->assertFalse($this->getCountryFirstLanguageEntity()->isPopular());
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
     * Returns Language Entity object.
     *
     * @return Entity\Language
     */
    private function getEntity()
    {
        return $this->getCountLang()->getLanguage('Danish');
    }

    /**
     * Returns Country Entity object.
     *
     * @return Entity\Language
     */
    private function getCountryFirstLanguageEntity()
    {
        return $this->getCountLang()->getCountry('FIN')->getLanguagesCollection()->getEntity();
    }
}