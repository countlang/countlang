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
 * Integration test for SubRegion.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 */
class SubRegionTest extends IntegrationTestCase
{
    /**
     * @coversNothing
     */
    public function testGetSubRegionProperties()
    {
        $subRegionName = 'Northern Europe';
        $subRegionData = [
            'code'       => '154',
            'name'       => 'Northern Europe',
            'regionCode' => '150',
        ];
        $subRegionCountries = [
            'ALA',
            'DNK',
            'EST',
            'FIN',
            'FRO',
            'GBR',
            'GGY',
            'IMN',
            'IRL',
            'ISL',
            'JEY',
            'JTN',
            'LTU',
            'LVA',
            'NOR',
            'SJM',
            'SWE'
        ];
        $subRegionRegion = 'Europe';

        $subRegion = $this->getCountLang()->getSubRegion($subRegionName);

        $this->assertEquals($subRegionData['code'],       $subRegion->getCode());
        $this->assertEquals($subRegionData['name'],       $subRegion->getName());
        $this->assertEquals($subRegionData['regionCode'], $subRegion->getRegionCode());

        $this->assertEquals($subRegionRegion, $subRegion->getRegionEntity()->getName());
        $this->assertEquals($subRegionCountries, $subRegion->getCountriesCollection()->select('alpha3Code'));
    }
}