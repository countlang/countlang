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
 * Integration test for Language.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 */
class LanguageTest extends IntegrationTestCase
{
    /**
     * @coversNothing
     */
    public function testGetLanguageProperties()
    {
        $languageName = 'dan';
        $languageData = [
            'officialName'         => 'Danish',
            'shortName'            => 'Danish',
            'altNames'             => [],
            'alpha2Code'           => 'da',
            'alpha3BCode'          => '',
            'alpha3TCode'          => 'dan',
            'isIndividualLanguage' => true,
            'isMacroLanguage'      => false,
            'isSpecialCode'        => false,
            'isLiving'             => true,
            'isConstructed'        => false,
            'isAncient'            => false,
            'isExtinct'            => false,
            'isHistorical'         => false,
        ];
        $languageCountries = [
            'DEU',
            'DNK',
            'GRL',
            'ISL',
        ];

        $language = $this->getCountLang()->getLanguage($languageName);

        $this->assertEquals($languageData['officialName'],         $language->getOfficialName());
        $this->assertEquals($languageData['shortName'],            $language->getShortName());
        $this->assertEquals($languageData['altNames'],             $language->getAltNames());
        $this->assertEquals($languageData['alpha2Code'],           $language->getAlpha2Code());
        $this->assertEquals($languageData['alpha3BCode'],          $language->getAlpha3BCode());
        $this->assertEquals($languageData['alpha3TCode'],          $language->getAlpha3TCode());
        $this->assertEquals($languageData['isIndividualLanguage'], $language->isIndividualLanguage());
        $this->assertEquals($languageData['isMacroLanguage'],      $language->isMacroLanguage());
        $this->assertEquals($languageData['isSpecialCode'],        $language->isSpecialCode());
        $this->assertEquals($languageData['isLiving'],             $language->isLiving());
        $this->assertEquals($languageData['isConstructed'],        $language->isConstructed());
        $this->assertEquals($languageData['isAncient'],            $language->isAncient());
        $this->assertEquals($languageData['isExtinct'],            $language->isExtinct());
        $this->assertEquals($languageData['isHistorical'],         $language->isHistorical());

        $this->assertEquals($languageCountries, $language->getCountriesCollection()->select('alpha3Code'));
    }
}