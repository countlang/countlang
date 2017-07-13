<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Entity\Entity;

use CountLang\Collection\CollectionDecorator;
use CountLang\Entity\Entity;
use CountLang\Collection\Collection\Country as CountryCollection;
use CountLang\CountLang;
use CountLang\Annotation\Map;

/**
 * Language Entity object.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Entity
 * @link      http://github.com/countlang/countlang
 */
class Language extends Entity
{
    /** ********** name block ********** */

    /**
     * @var string Language official (full) name.
     * @map(source="name_official", isIdentifier=true, isRequired=true)
     */
    private $officialName;
    /**
     * @var string Short name of language.
     * @map(source="name_short", isIdentifier=true, isRequired=true)
     */
    private $shortName;
    /**
     * @var array Array with alternative names for language.
     * @map(source="name_alt", isIdentifier=true, isRequired=true)
     */
    private $altNames = [];

    /** ********** isoCodes block ********** */

    /**
     * @var string Alpha 2 code of language according to the ISO 639.
     * @map(source="isoCodes_alpha2", isIdentifier=true, isRequired=true)
     */
    private $alpha2Code;
    /**
     * @var string Alpha 3B code of language according to the ISO 639.
     * @map(source="isoCodes_alpha3B", isIdentifier=true, isRequired=true)
     */
    private $alpha3BCode;
    /**
     * @var string Alpha 3T code of language according to the ISO 639.
     * @map(source="isoCodes_alpha3T", isIdentifier=true, isRequired=false)
     */
    private $alpha3TCode;

    /** ********** scope block ********** */

    /**
     * @var boolean Whether language belong to scope 'individual'.
     * @map(source="scope_isIndividualLanguage", isIdentifier=false, isRequired=true)
     */
    private $isIndividualLanguage;
    /**
     * @var boolean Whether language belong to scope 'macrolanguage'.
     * @map(source="scope_isMacroLanguage", isIdentifier=false, isRequired=true)
     */
    private $isMacroLanguage;
    /**
     * @var boolean Whether language belong to scope 'special code'.
     * @map(source="scope_isSpecialCode", isIdentifier=false, isRequired=true)
     */
    private $isSpecialCode;

    /** ********** type block ********** */

    /**
     * @var boolean Whether language belong to type 'living'.
     * @map(source="type_isLiving", isIdentifier=false, isRequired=true)
     */
    private $isLiving;
    /**
     * @var boolean Whether language belong to type 'constructed'.
     * @map(source="type_isConstructed", isIdentifier=false, isRequired=true)
     */
    private $isConstructed;
    /**
     * @var boolean Whether language belong to type 'ancient'.
     * @map(source="type_isAncient", isIdentifier=false, isRequired=true)
     */
    private $isAncient;
    /**
     * @var boolean Whether language belong to type 'extinct'.
     * @map(source="type_isExtinct", isIdentifier=false, isRequired=true)
     */
    private $isExtinct;
    /**
     * @var boolean Whether language belong to type 'historical'.
     * @map(source="type_isHistorical", isIdentifier=false, isRequired=true)
     */
    private $isHistorical;

    /** ********** extra block ********** */

    /**
     * @var float Usage of this language in country (%).
     * @map(source="", isIdentifier=false, isRequired=false)
     */
    private $usagePercentage;

    /**
     * @var boolean Whether this language has official status in country.
     * @map(source="", isIdentifier=false, isRequired=false)
     */
    private $isOfficial;
    /**
     * @var boolean Whether this language has regional status in country.
     * @map(source="", isIdentifier=false, isRequired=false)
     */
    private $isRegional;
    /**
     * @var boolean Whether this language is popular (de-facto official) in country.
     * @map(source="", isIdentifier=false, isRequired=false)
     */
    private $isPopular;

    /** @var CountryCollection Country Collection oblect with countries that use this language. */
    private $countriesCollection;

    /**
     * Returns language official (full) name.
     *
     * @return string
     */
    public function getOfficialName()
    {
        return $this->officialName;
    }

    /**
     * Returns short name of language.
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Returns array with alternative names for language.
     *
     * @return array
     */
    public function getAltNames()
    {
        return $this->altNames;
    }

    /**
     * Returns alpha 2 code of language according to the ISO 639.
     *
     * @return string
     */
    public function getAlpha2Code()
    {
        return $this->alpha2Code;
    }

    /**
     * Returns alpha 3B code of language according to the ISO 639.
     *
     * @return string
     */
    public function getAlpha3BCode()
    {
        return $this->alpha3BCode;
    }

    /**
     * Returns alpha 3T code of language according to the ISO 639.
     *
     * @return string
     */
    public function getAlpha3TCode()
    {
        return $this->alpha3TCode;
    }

    /**
     * Returns whether language belong to scope 'individual'.
     *
     * @return bool
     */
    public function isIndividualLanguage()
    {
        return $this->isIndividualLanguage;
    }

    /**
     * Returns whether language belong to scope 'macro language'.
     *
     * @return bool
     */
    public function isMacroLanguage()
    {
        return $this->isMacroLanguage;
    }

    /**
     * Returns whether language belong to scope 'special code'.
     *
     * @return bool
     */
    public function isSpecialCode()
    {
        return $this->isSpecialCode;
    }

    /**
     * Returns whether language belong to type 'living'.
     *
     * @return bool
     */
    public function isLiving()
    {
        return $this->isLiving;
    }

    /**
     * Returns whether language belong to type 'constructed'.
     *
     * @return bool
     */
    public function isConstructed()
    {
        return $this->isConstructed;
    }

    /**
     * Returns whether language belong to type 'ancient'.
     *
     * @return bool
     */
    public function isAncient()
    {
        return $this->isAncient;
    }

    /**
     * Returns whether language belong to type 'extinct'.
     *
     * @return bool
     */
    public function isExtinct()
    {
        return $this->isExtinct;
    }

    /**
     * Returns whether language belong to type 'historical'.
     *
     * @return bool
     */
    public function isHistorical()
    {
        return $this->isHistorical;
    }

    /**
     * Returns usage of this language in country (%).
     *
     * @return float
     */
    public function getUsagePercentage()
    {
        return $this->usagePercentage;
    }

    /**
     * Returns whether this language has official status in country.
     *
     * @return boolean
     */
    public function isOfficial()
    {
        return $this->isOfficial;
    }

    /**
     * Returns whether this language has regional status in country.
     *
     * @return boolean
     */
    public function isRegional()
    {
        return $this->isRegional;
    }

    /**
     * Returns whether this language is popular (de-facto official) in country.
     *
     * @return boolean
     */
    public function isPopular()
    {
        return $this->isPopular;
    }

    /**
     * Returns Country Collection oblect with countries that use this language.
     *
     * @return CountryCollection
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getCountriesCollection()
    {
        if (null !== $this->countriesCollection) {
            return $this->countriesCollection;
        }

        $countriesEntitiesArray = array_filter((new CountLang())->getCountries()->getEntities(), function($country) {
            /** @var Country $country */
            return in_array(
                $this->getAlpha3TCode(),
                array_column($country->getLanguages(), 'languageCode'),
                true
            );
        });

        $countriesCollection = new CollectionDecorator(new CountryCollection());
        $countriesCollection->addEntities($countriesEntitiesArray);

        return $this->countriesCollection = $countriesCollection->getCollection();
    }
}