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
 * Unit test for CountLang\Entity\Entity\Country class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 *
 * @coversDefaultClass CountLang\Entity\Entity\Country
 */
class CountryTest extends UnitTestCase
{
    /**
     * @covers ::getOfficialName
     */
    public function testGetOfficialName()
    {
        $this->assertEquals('Kingdom of Denmark', $this->getEntity()->getOfficialName());
    }

    /**
     * @covers ::getShortName
     */
    public function testGetShortName()
    {
        $this->assertEquals('Denmark', $this->getEntity()->getShortName());
    }

    /**
     * @covers ::getAltNames
     */
    public function testGetAltNames()
    {
        $this->assertEquals([], $this->getEntity()->getAltNames());
    }

    /**
     * @covers ::isExist
     */
    public function testIsExist()
    {
        $this->assertTrue($this->getEntity()->isExist());
    }

    /**
     * @covers ::isSovereignState
     */
    public function testIsSovereignState()
    {
        $this->assertTrue($this->getEntity()->isSovereignState());
    }

    /**
     * @covers ::getDependentType
     */
    public function testGetDependentType()
    {
        $this->assertNull($this->getEntity()->getDependentType());
    }

    /**
     * @covers ::getDependOn
     */
    public function testGetDependOn()
    {
        $this->assertEquals([], $this->getEntity()->getDependOn());
    }

    /**
     * @covers ::getAlpha2Code
     */
    public function testGetAlpha2Code()
    {
        $this->assertEquals('DK', $this->getEntity()->getAlpha2Code());
    }

    /**
     * @covers ::getAlpha3Code
     */
    public function testGetAlpha3Code()
    {
        $this->assertEquals('DNK', $this->getEntity()->getAlpha3Code());
    }

    /**
     * @covers ::getNumericCode
     */
    public function testGetNumericCode()
    {
        $this->assertEquals('208', $this->getEntity()->getNumericCode());
    }

    /**
     * @covers ::getAssignmentYear
     */
    public function testGetAssignmentYear()
    {
        $this->assertEquals('1974', $this->getEntity()->getAssignmentYear());
    }

    /**
     * @covers ::getUnAssignmentYear
     */
    public function testGetUnAssignmentYear()
    {
        $this->assertNull($this->getEntity()->getUnAssignmentYear());
    }

    /**
     * @covers ::getTransformType
     */
    public function testGetTransformType()
    {
        $this->assertNull($this->getEntity()->getTransformType());
    }

    /**
     * @covers ::getTransformTo
     */
    public function testGetTransformTo()
    {
        $this->assertEquals([], $this->getEntity()->getTransformTo());
    }

    /**
     * @covers ::getRegionCode
     */
    public function testGetRegionCode()
    {
        $this->assertEquals('150', $this->getEntity()->getRegionCode());
    }

    /**
     * @covers ::getSubRegionCode
     */
    public function testGetSubRegionCode()
    {
        $this->assertEquals('154', $this->getEntity()->getSubRegionCode());
    }

    /**
     * @covers ::isLandLocked
     */
    public function testIsLandLocked()
    {
        $this->assertFalse($this->getEntity()->isLandLocked());
    }

    /**
     * @covers ::getBorders
     */
    public function testGetBorders()
    {
        $this->assertEquals([
            'DEU'
        ], $this->getEntity()->getBorders());
    }

    /**
     * @covers ::getLanguages
     */
    public function testGetLanguages()
    {
        $this->assertEquals([
            [
                'languageCode'    => 'dan',
                'usagePercentage' => 93,
                'isOfficial'      => true,
                'isRegional'      => false,
                'isPopular'       => false,
            ],
            [
                'languageCode'    => 'deu',
                'usagePercentage' => 47,
                'isOfficial'      => false,
                'isRegional'      => true,
                'isPopular'       => false,
            ],
            [
                'languageCode'    => 'kal',
                'usagePercentage' => 0.13,
                'isOfficial'      => false,
                'isRegional'      => true,
                'isPopular'       => false,
            ],
            [
                'languageCode'    => 'eng',
                'usagePercentage' => 86,
                'isOfficial'      => false,
                'isRegional'      => false,
                'isPopular'       => false,
            ],
            [
                'languageCode'    => 'swe',
                'usagePercentage' => 13,
                'isOfficial'      => false,
                'isRegional'      => false,
                'isPopular'       => false,
            ],
            [
                'languageCode'    => 'fao',
                'usagePercentage' => 0.38,
                'isOfficial'      => false,
                'isRegional'      => false,
                'isPopular'       => false,
            ],
            [
                'languageCode'    => 'jut',
                'usagePercentage' => 0,
                'isOfficial'      => false,
                'isRegional'      => false,
                'isPopular'       => false,
            ],
        ], $this->getEntity()->getLanguages());
    }

    /**
     * @covers ::getCurrencies
     */
    public function testGetCurrencies()
    {
        $this->assertEquals([
            'DKK'
        ], $this->getEntity()->getCurrencies());
    }

    /**
     * @covers ::getArea
     */
    public function testGetArea()
    {
        $this->assertEquals(43094, $this->getEntity()->getArea());
    }

    /**
     * @covers ::getPopulation
     */
    public function testGetPopulation()
    {
        $this->assertEquals(5593790, $this->getEntity()->getPopulation());
    }

    /**
     * @covers ::getCallingCodes
     */
    public function testGetCallingCodes()
    {
        $this->assertEquals([
            '45'
        ], $this->getEntity()->getCallingCodes());
    }

    /**
     * @covers ::getDependOnCollection
     */
    public function testGetDependOnCollection()
    {
        $this->assertInstanceOf(Collection\Country::class, $this->getEntity()->getDependOnCollection());

        /** @var Entity\Country $countryEntity */
        $countryEntity = $this->getCountLang()->getCountries()->findEntity('Greenland');

        $this->assertInstanceOf(Collection\Country::class, $countryEntity->getDependOnCollection());
        $this->assertInstanceOf(Collection\Country::class, $countryEntity->getDependOnCollection());
    }

    /**
     * @covers ::getTransformToCollection
     */
    public function testGetTransformToCollection()
    {
        $this->assertInstanceOf(Collection\Country::class, $this->getEntity()->getTransformToCollection());

        /** @var Entity\Country $countryEntity */
        $countryEntity = $this->getCountLang()->getCountries()->findEntity('Afars and Issas');

        $this->assertInstanceOf(Collection\Country::class, $countryEntity->getTransformToCollection());
        $this->assertInstanceOf(Collection\Country::class, $countryEntity->getTransformToCollection());
    }

    /**
     * @covers ::getBordersCollection
     */
    public function testGetBordersCollection()
    {
        $this->assertInstanceOf(Collection\Country::class, $this->getEntity()->getBordersCollection());
        $this->assertInstanceOf(Collection\Country::class, $this->getEntity()->getBordersCollection());

        /** @var Entity\Country $countryEntity */
        $countryEntity = $this->getCountLang()->getCountries()->findEntity('Antarctica');

        $this->assertInstanceOf(Collection\Country::class, $countryEntity->getBordersCollection());
    }

    /**
     * @covers ::getRegionEntity
     */
    public function testGetRegionEntity()
    {
        $this->assertInstanceOf(Entity\Region::class, $this->getEntity()->getRegionEntity());
        $this->assertInstanceOf(Entity\Region::class, $this->getEntity()->getRegionEntity());
    }

    /**
     * @covers ::getSubRegionEntity
     */
    public function testGetSubRegionEntity()
    {
        $this->assertInstanceOf(Entity\SubRegion::class, $this->getEntity()->getSubRegionEntity());
        $this->assertInstanceOf(Entity\SubRegion::class, $this->getEntity()->getSubRegionEntity());
    }

    /**
     * @covers ::getLanguagesCollection
     */
    public function testGetLanguagesCollection()
    {
        $this->assertInstanceOf(Collection\Language::class, $this->getEntity()->getLanguagesCollection());
        $this->assertInstanceOf(Collection\Language::class, $this->getEntity()->getLanguagesCollection());

        /** @var Entity\Country $countryEntity */
        $countryEntity = $this->getCountLang()->getCountries()->findEntity('Antarctica');

        $this->assertInstanceOf(Collection\Language::class, $countryEntity->getLanguagesCollection());
    }

    /**
     * @covers ::getCurrenciesCollection
     */
    public function testGetCurrenciesCollection()
    {
        $this->assertInstanceOf(Collection\Currency::class, $this->getEntity()->getCurrenciesCollection());
        $this->assertInstanceOf(Collection\Currency::class, $this->getEntity()->getCurrenciesCollection());

        /** @var Entity\Country $countryEntity */
        $countryEntity = $this->getCountLang()->getCountries()->findEntity('Antarctica');

        $this->assertInstanceOf(Collection\Currency::class, $countryEntity->getCurrenciesCollection());
    }

    /**
     * Returns Country Entity object.
     *
     * @return Entity\Country
     */
    private function getEntity()
    {
        return $this->getCountLang()->getCountry('Denmark');
    }
}