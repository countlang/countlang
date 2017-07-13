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
 * Integration test for Currency.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 */
class CurrencyTest extends IntegrationTestCase
{
    /**
     * @coversNothing
     */
    public function testGetCurrencyProperties()
    {
        $currencyName = 'DKK';
        $currencyData = [
            'officialName' => 'Danish Krone',
            'shortName'    => 'Krone',
            'altNames'     => [],
            'alphaCode'    => 'DKK',
            'numericCode'  => '208',
            'symbol'       => 'kr',
            'magnitude'    => 2,
        ];
        $currencyCountries = [
            'DNK',
            'FRO',
            'GRL',
        ];

        $currency = $this->getCountLang()->getCurrency($currencyName);

        $this->assertEquals($currencyData['officialName'], $currency->getOfficialName());
        $this->assertEquals($currencyData['shortName'],    $currency->getShortName());
        $this->assertEquals($currencyData['altNames'],     $currency->getAltNames());
        $this->assertEquals($currencyData['alphaCode'],    $currency->getAlphaCode());
        $this->assertEquals($currencyData['numericCode'],  $currency->getNumericCode());
        $this->assertEquals($currencyData['symbol'],       $currency->getSymbol());
        $this->assertEquals($currencyData['magnitude'],    $currency->getMagnitude());

        $this->assertEquals($currencyCountries, $currency->getCountriesCollection()->select('alpha3Code'));
    }
}