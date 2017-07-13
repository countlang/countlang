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

use CountLang\Entity\Entity;
use CountLang\Collection\CollectionDecorator;
use CountLang\Collection\Collection\Country as CountryCollection;
use CountLang\Collection\Collection\Currency as CurrencyCollection;
use CountLang\Collection\Collection\Language as LanguageCollection;
use CountLang\CountLang;
use CountLang\Annotation\Map;

/**
 * Country Entity object.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Entity
 * @link      http://github.com/countlang/countlang
 */
class Country extends Entity
{
    /** ********** name block ********** */

    /**
     * @var string Country official (full) name.
     * @map(source="name_official", isIdentifier=true, isRequired=true)
     */
    private $officialName;
    /**
     * @var string Short name of country.
     * @map(source="name_short", isIdentifier=true, isRequired=true)
     */
    private $shortName;
    /**
     * @var array Array with alternative names for country.
     * @map(source="name_alt", isIdentifier=true, isRequired=true)
     */
    private $altNames = [];

    /** ********** status block ********** */

    /**
     * @var boolean Whether country currently exists.
     * @map(source="status_isExist", isIdentifier=false, isRequired=true)
     */
    private $isExist;
    /**
     * @var boolean Whether country is a sovereign state (independent).
     * @map(source="status_isSovereignState", isIdentifier=false, isRequired=false)
     */
    private $isSovereignState;
    /**
     * @var string Type of dependency country from another country.
     * @map(source="status_dependentType", isIdentifier=false, isRequired=false)
     */
    private $dependentType;
    /**
     * @var array Array with alpha 3 codes of countries from which this country is depend on.
     * @map(source="status_dependOn", isIdentifier=false, isRequired=false)
     */
    private $dependOn = [];

    /** ********** iso3166 block ********** */

    /**
     * @var string Alpha 2 code of country according to the ISO 3166.
     * @map(source="iso3166_codes_alpha2", isIdentifier=true, isRequired=true)
     */
    private $alpha2Code;
    /**
     * @var string Alpha 3 code of country according to the ISO 3166.
     * @map(source="iso3166_codes_alpha3", isIdentifier=true, isRequired=true)
     */
    private $alpha3Code;
    /**
     * @var string Numeric code of country according to the ISO 3166.
     * @map(source="iso3166_codes_numeric", isIdentifier=true, isRequired=true)
     */
    private $numericCode;
    /**
     * @var string Year when country was added to ISO 3166.
     * @map(source="iso3166_assignedAt", isIdentifier=false, isRequired=true)
     */
    private $assignmentYear;
    /**
     * @var string Year when country was removed from ISO 3166 (merged in other country, split into parts, etc).
     * @map(source="iso3166_unAssignedAt", isIdentifier=false, isRequired=false)
     */
    private $unAssignmentYear;
    /**
     * @var string Reason of why country not exists anymore (unassigned from ISO 3166).
     * @map(source="iso3166_transformType", isIdentifier=false, isRequired=false)
     */
    private $transformType;
    /**
     * @var array Array with alpha 3 codes of countries that was involved during transformation of this country.
     * @map(source="iso3166_transformTo", isIdentifier=false, isRequired=false)
     */
    private $transformTo = [];

    /** ********** location block ********** */

    /**
     * @var string Code of region country is belong to.
     * @map(source="location_regionCode", isIdentifier=false, isRequired=true)
     */
    private $regionCode;
    /**
     * @var string Code of sub-region country is belong to.
     * @map(source="location_subRegionCode", isIdentifier=false, isRequired=true)
     */
    private $subRegionCode;
    /**
     * @var boolean Whether country has access to global ocean.
     * @map(source="location_isLandLocked", isIdentifier=false, isRequired=true)
     */
    private $isLandLocked;
    /**
     * @var array Array with alpha 3 codes of countries this country has borders with.
     * @map(source="location_borders", isIdentifier=false, isRequired=true)
     */
    private $borders = [];

    /** ********** languages block ********** */

    /**
     * @var array Array with ISO 639 alpha 3T codes of languages used in country and basic info about usage.
     * @map(source="languages", isIdentifier=false, isRequired=true)
     */
    private $languages = [];

    /** ********** currencies block ********** */

    /**
     * @var array Array with ISO 4217 alpha 3 codes of currencies used in country.
     * @map(source="currencies", isIdentifier=false, isRequired=true)
     */
    private $currencies = [];

    /** ********** extra block ********** */

    /**
     * @var double Area of country.
     * @map(source="extra_area", isIdentifier=false, isRequired=false)
     */
    private $area;
    /**
     * @var integer Population of country.
     * @map(source="extra_population", isIdentifier=false, isRequired=true)
     */
    private $population;
    /**
     * @var array Array with calling codes of country.
     * @map(source="extra_callingCodes", isIdentifier=false, isRequired=false)
     */
    private $callingCodes = [];

    /** @var CountryCollection Country Collection object with countries this country is depend on. */
    private $dependOnCollection;

    /** @var CountryCollection Country Collection object of countries involved during transformation of this country. */
    private $transformToCollection;

    /** @var Region Region Entity object of region this country is belong to. */
    private $regionEntity;

    /** @var SubRegion SubRegion Entity object of sub-region this country is belong to. */
    private $subRegionEntity;

    /** @var CountryCollection Country Collection object with countries that share common border with this country. */
    private $bordersCollection;

    /** @var LanguageCollection Language Collection object with languages used in this country. */
    private $languagesCollection;

    /** @var CurrencyCollection Currency Collection object with currencies used in this country. */
    private $currenciesCollection;

    /**
     * Returns official name of country.
     *
     * @return string
     */
    public function getOfficialName()
    {
        return $this->officialName;
    }

    /**
     * Returns short name of country.
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Returns list of alternative names of country.
     *
     * @return array
     */
    public function getAltNames()
    {
        return $this->altNames;
    }

    /**
     * Returns whether country is currently exists.
     *
     * @return bool
     */
    public function isExist()
    {
        return $this->isExist;
    }

    /**
     * Returns whether country is sovereign state (independent).
     *
     * @return bool
     */
    public function isSovereignState()
    {
        return $this->isSovereignState;
    }

    /**
     * Returns type of dependency country from another country.
     *
     * @return string
     */
    public function getDependentType()
    {
        return $this->dependentType;
    }

    /**
     * Returns array with alpha 3 codes of countries this country is depend on.
     *
     * @return array
     */
    public function getDependOn()
    {
        return $this->dependOn;
    }

    /**
     * Returns alpha 2 code of country according to the ISO 3166.
     *
     * @return string
     */
    public function getAlpha2Code()
    {
        return $this->alpha2Code;
    }

    /**
     * Returns alpha 3 code of country according to the ISO 3166.
     *
     * @return string
     */
    public function getAlpha3Code()
    {
        return $this->alpha3Code;
    }

    /**
     * Returns numeric code of country according to the ISO 3166.
     *
     * @return string
     */
    public function getNumericCode()
    {
        return $this->numericCode;
    }

    /**
     * Returns year when country was added to ISO 3166.
     *
     * @return string
     */
    public function getAssignmentYear()
    {
        return $this->assignmentYear;
    }

    /**
     * Returns year when country was removed from ISO 3166.
     *
     * @return string
     */
    public function getUnAssignmentYear()
    {
        return $this->unAssignmentYear;
    }

    /**
     * Returns reason of why country not exists anymore (unassigned from ISO 3166).
     *
     * @return string
     */
    public function getTransformType()
    {
        return $this->transformType;
    }

    /**
     * Returns array with alpha 3 codes of countries that was involved during transformation of this country.
     *
     * @return array
     */
    public function getTransformTo()
    {
        return $this->transformTo;
    }

    /**
     * Returns code of region country is belong to.
     *
     * @return string
     */
    public function getRegionCode()
    {
        return $this->regionCode;
    }

    /**
     * Returns code of sub-region country is belong to.
     *
     * @return string
     */
    public function getSubRegionCode()
    {
        return $this->subRegionCode;
    }

    /**
     * Returns whether country has access to global ocean.
     *
     * @return bool
     */
    public function isLandLocked()
    {
        return $this->isLandLocked;
    }

    /**
     * Returns array with alpha 3 codes of countries this country has borders with.
     *
     * @return array
     */
    public function getBorders()
    {
        return $this->borders;
    }

    /**
     * Returns array with ISO 639 alpha 3T codes of languages used in country and basic info about usage.
     *
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Returns array with ISO 4217 alpha 3 codes of currencies used in country.
     *
     * @return array
     */
    public function getCurrencies()
    {
        return $this->currencies;
    }

    /**
     * Returns area of country (square kilometers).
     *
     * @return int
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Returns country population.
     *
     * @return int
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * Returns array with country calling codes.
     *
     * @return array
     */
    public function getCallingCodes()
    {
        return $this->callingCodes;
    }

    /**
     * Returns Country Country Collection object with countries this one is depend on.
     *
     * @return CountryCollection
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getDependOnCollection()
    {
        if (0 === count($this->getDependOn())) {
            return new CountryCollection();
        }

        if (null !== $this->dependOnCollection) {
            return $this->dependOnCollection;
        }

        $dependOnEntitiesArray = array_map(function($dependOn) {
            return (new CountLang())->getCountry($dependOn);
        }, $this->getDependOn());

        $dependOnCollection = new CollectionDecorator(new CountryCollection());
        $dependOnCollection->addEntities($dependOnEntitiesArray);

        return $this->dependOnCollection = $dependOnCollection->getCollection();
    }

    /**
     * Returns Country Collection object with countries involved during transformation of this one.
     *
     * @return CountryCollection
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getTransformToCollection()
    {
        if (0 === count($this->getTransformTo())) {
            return new CountryCollection();
        }

        if (null !== $this->transformToCollection) {
            return $this->transformToCollection;
        }

        $transformToEntitiesArray = array_map(function($transformTo) {
            return (new CountLang())->getCountry($transformTo);
        }, $this->getTransformTo());

        $transformToCollection = new CollectionDecorator(new CountryCollection());
        $transformToCollection->addEntities($transformToEntitiesArray);

        return $this->transformToCollection = $transformToCollection->getCollection();
    }

    /**
     * Returns Country Collection object with countries that share common border with this one.
     *
     * @return CountryCollection
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getBordersCollection()
    {
        if (0 === count($this->getBorders())) {
            return new CountryCollection();
        }

        if (null !== $this->bordersCollection) {
            return $this->bordersCollection;
        }

        $bordersEntitiesArray = array_map(function($border) {
            return (new CountLang())->getCountry($border);
        }, $this->getBorders());

        $bordersCollection = new CollectionDecorator(new CountryCollection());
        $bordersCollection->addEntities($bordersEntitiesArray);

        return $this->bordersCollection = $bordersCollection->getCollection();
    }

    /**
     * Returns Region Entity object of region this country is belong to.
     *
     * @return Region
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getRegionEntity()
    {
        if (null !== $this->regionEntity) {
            return $this->regionEntity;
        }

        return $this->regionEntity = (new CountLang())->getRegion($this->getRegionCode());
    }

    /**
     * Returns SubRegion Entity object of region this country is belong to.
     *
     * @return SubRegion
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getSubRegionEntity()
    {
        if (null !== $this->subRegionEntity) {
            return $this->subRegionEntity;
        }

        return $this->subRegionEntity = (new CountLang())->getSubRegion($this->getSubRegionCode());
    }

    /**
     * Returns Language Collection object with languages used in this country.
     *
     * @return LanguageCollection
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getLanguagesCollection()
    {
        $languages = $this->getLanguages();

        if (0 === count($languages)) {
            return new LanguageCollection();
        }

        if (null !== $this->languagesCollection) {
            return $this->languagesCollection;
        }

        $languagesCollectionDecorator = new CollectionDecorator(new LanguageCollection());

        $languagesEntitiesArray = array_map(function($language) use ($languagesCollectionDecorator) {
            /** @var array $language */
            $entity = (new CountLang())->getLanguage($language['languageCode']);

            foreach ($language as $propertyName => $propertyValue) {
                if ($propertyName === 'languageCode') {
                    continue;
                }

                $languagesCollectionDecorator->getEntityDecorator()->setPropertyValue($entity, $propertyName, $propertyValue);
            }

            return $entity;
        }, $languages);
        $languagesCollectionDecorator->addEntities($languagesEntitiesArray);

        return $this->languagesCollection = $languagesCollectionDecorator->getCollection();
    }

    /**
     * Returns Currency Collection object with currencies used in this country.
     *
     * @return CurrencyCollection
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getCurrenciesCollection()
    {
        if (0 === count($this->currencies)) {
            return new CurrencyCollection();
        }

        if (null !== $this->currenciesCollection) {
            return $this->currenciesCollection;
        }

        $currenciesEntitiesArray = array_map(function($currencies) {
            return (new CountLang())->getCurrency($currencies);
        }, $this->currencies);

        $currenciesCollection = new CollectionDecorator(new CurrencyCollection());
        $currenciesCollection->addEntities($currenciesEntitiesArray);

        return $this->currenciesCollection = $currenciesCollection->getCollection();
    }
}