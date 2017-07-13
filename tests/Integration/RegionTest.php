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
 * Integration test for Region.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 */
class RegionTest extends IntegrationTestCase
{
    /**
     * @coversNothing
     */
    public function testGetRegionProperties()
    {
        $regionName = 'Europe';
        $regionData = [
            'code' => '150',
            'name' => 'Europe',
        ];
        $regionCountries = [
            'ALA',
            'ALB',
            'AND',
            'AUT',
            'BEL',
            'BGR',
            'BIH',
            'BLR',
            'CHE',
            'CSK',
            'CZE',
            'DEU',
            'DNK',
            'ESP',
            'EST',
            'FIN',
            'FRA',
            'FRO',
            'FXX',
            'GBR',
            'GGY',
            'GIB',
            'GRC',
            'HRV',
            'HUN',
            'IMN',
            'IRL',
            'ISL',
            'ITA',
            'JEY',
            'JTN',
            'LIE',
            'LTU',
            'LUX',
            'LVA',
            'MCO',
            'MDA',
            'MKD',
            'MLT',
            'MNE',
            'NLD',
            'NOR',
            'POL',
            'PRT',
            'ROU',
            'RUS',
            'SCG',
            'SJM',
            'SMR',
            'SRB',
            'SUN',
            'SVK',
            'SVN',
            'SWE',
            'UKR',
            'VAT',
            'YUG',
        ];
        $regionSubRegions = [
            '039',
            '151',
            '154',
            '155',
        ];

        $region = $this->getCountLang()->getRegion($regionName);

        $this->assertEquals($regionData['code'], $region->getCode());
        $this->assertEquals($regionData['name'], $region->getName());

        $this->assertEquals($regionSubRegions, $region->getSubRegionsCollection()->select('code'));
        $this->assertEquals($regionCountries, $region->getCountriesCollection()->select('alpha3Code'));
    }
}