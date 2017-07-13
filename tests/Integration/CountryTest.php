<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Tests\Integration;

/**
 * Integration test for Country.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 */
class CountryTest extends IntegrationTestCase
{
    /**
     * @coversNothing
     */
    public function testGetCountryProperties()
    {
        $countryName = 'Denmark';
        $countryData = [
            'officialName'      => 'Kingdom of Denmark',
            'shortName'         => 'Denmark',
            'altNames'          => [],
            'isExist'           => true,
            'isSovereignState'  => true,
            'dependentType'     => null,
            'dependOn'          => [],
            'alpha2Code'        => 'DK',
            'alpha3Code'        => 'DNK',
            'numericCode'       => '208',
            'assignmentYear'    => '1974',
            'unAssignmentYear'  => null,
            'transformType'     => null,
            'transformTo'       => [],
            'regionCode'        => '150',
            'subRegionCode'     => '154',
            'isLandLocked'      => false,
            'borders'           => [
                                    'DEU'
                                ],
            'languages'         => [
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
                                ],
            'currencies'        => [
                                    'DKK'
                                ],
            'area'              => 43094,
            'population'        => 5593790,
            'callingCodes'      => [
                                    '45'
                                ],
        ];

        $country = $this->getCountLang()->getCountry($countryName);

        $this->assertEquals($countryData['officialName'],     $country->getOfficialName());
        $this->assertEquals($countryData['shortName'],        $country->getShortName());
        $this->assertEquals($countryData['altNames'],         $country->getAltNames());
        $this->assertEquals($countryData['isExist'],          $country->isExist());
        $this->assertEquals($countryData['isSovereignState'], $country->isSovereignState());
        $this->assertEquals($countryData['dependentType'],    $country->getDependentType());
        $this->assertEquals($countryData['dependOn'],         $country->getDependOn());
        $this->assertEquals($countryData['alpha2Code'],       $country->getAlpha2Code());
        $this->assertEquals($countryData['alpha3Code'],       $country->getAlpha3Code());
        $this->assertEquals($countryData['numericCode'],      $country->getNumericCode());
        $this->assertEquals($countryData['assignmentYear'],   $country->getAssignmentYear());
        $this->assertEquals($countryData['unAssignmentYear'], $country->getUnAssignmentYear());
        $this->assertEquals($countryData['transformType'],    $country->getTransformType());
        $this->assertEquals($countryData['transformTo'],      $country->getTransformTo());
        $this->assertEquals($countryData['regionCode'],       $country->getRegionCode());
        $this->assertEquals($countryData['subRegionCode'],    $country->getSubRegionCode());
        $this->assertEquals($countryData['isLandLocked'],     $country->isLandLocked());
        $this->assertEquals($countryData['borders'],          $country->getBorders());
        $this->assertEquals($countryData['languages'],        $country->getLanguages());
        $this->assertEquals($countryData['currencies'],       $country->getCurrencies());
        $this->assertEquals($countryData['area'],             $country->getArea());
        $this->assertEquals($countryData['population'],       $country->getPopulation());
        $this->assertEquals($countryData['callingCodes'],     $country->getCallingCodes());

        $this->assertTrue($country->getDependOnCollection()->isEmpty());
        $this->assertTrue($country->getTransformToCollection()->isEmpty());

        $this->assertEquals(array_column($countryData['languages'], 'languageCode'), $country->getLanguagesCollection()->select('alpha3TCode'));
        $this->assertEquals($countryData['borders'], $country->getBordersCollection()->select('alpha3Code'));
        $this->assertEquals($countryData['currencies'], $country->getCurrenciesCollection()->select('alphaCode'));

        $this->assertEquals($countryData['regionCode'], $country->getRegionEntity()->getCode());
        $this->assertEquals($countryData['subRegionCode'], $country->getSubRegionEntity()->getCode());
    }
}