<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang;

use CountLang\Cache\ObjectCache;
use CountLang\Loader\Loader;
use CountLang\Loader\Loader\Country   as CountryLoader;
use CountLang\Loader\Loader\Language  as LanguageLoader;
use CountLang\Loader\Loader\Region    as RegionLoader;
use CountLang\Loader\Loader\SubRegion as SubRegionLoader;
use CountLang\Loader\Loader\Currency  as CurrencyLoader;
use CountLang\Collection\Collection;
use CountLang\Collection\Collection\Country   as CountryCollection;
use CountLang\Collection\Collection\Language  as LanguageCollection;
use CountLang\Collection\Collection\Region    as RegionCollection;
use CountLang\Collection\Collection\SubRegion as SubRegionCollection;
use CountLang\Collection\Collection\Currency  as CurrencyCollection;
use CountLang\Entity\Entity\Region    as RegionEntity;
use CountLang\Entity\Entity\SubRegion as SubRegionEntity;
use CountLang\Entity\Entity\Country   as CountryEntity;
use CountLang\Entity\Entity\Currency  as CurrencyEntity;
use CountLang\Entity\Entity\Language  as LanguageEntity;

/**
 * Basic package interface. Should be used to get any Collection or Entity.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang
 * @link      http://github.com/countlang/countlang
 */
class CountLang
{
    /**
     * Returns Country Entity object by identifier [officialName, shortName, altName,
     *                                              alpha2Code, alpha3Code, numericCode].
     *
     * ********************
     *
     * Examples:
     *
     * $country = (new CountLang\CountLang())->getCountry('Denmark');
     * $population = $country->getPopulation()
     *
     * $languages = (new CountLang\CountLang())->getCountry('DK')
     *                                         ->getLanguagesCollection()
     *                                         ->select('officialName');
     *
     * $neighborCountries = (new CountLang\CountLang())->getCountry('DNK')->getBordersCollection();
     *
     * ********************
     *
     * @param string $identifier
     *
     * @return CountryEntity
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getCountry($identifier)
    {
        return $this->getCountryCollection()->findEntity($identifier);
    }

    /**
     * Returns Country Collection object and might applies filters to it.
     *
     * ********************
     *
     * Examples:
     *
     * $countries = (new CountLang\CountLang())->getCountries();
     * $europeanCountries = $countries->filter('regionCode', '150');
     *
     * $bigCountries = (new CountLang\CountLang())->getCountries(['population', 10000000, 'gt']);
     *
     * $bigEuropeanCountries = (new CountLang\CountLang())->getCountries([
     *      ['population', 10000000, 'gt'],
     *      ['regionCode', '150'],
     *      ['isExist', true],
     * ]);
     *
     * ********************
     *
     * @param array $filters
     *
     * @return CountryCollection
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getCountries(array $filters = [])
    {
        return $this->filterCollection($this->getCountryCollection(), $filters);
    }

    /**
     * Returns Language Entity object by identifier [officialName, shortName, altNames,
     *                                               alpha2Code, alpha3BCode, alpha3TCode].
     *
     * ********************
     *
     * Examples:
     *
     * $language = (new CountLang\CountLang())->getLanguage('da');
     * $officialName = $country->getOfficialName()
     *
     * $countries = (new CountLang\CountLang())->getLanguage('dan')
     *                                         ->getCountriesCollection()
     *                                         ->select('officialName');
     *
     * $languagesPopularity = (new CountLang\CountLang())->getCountry('DK')
     *                                                   ->getLanguagesCollection()
     *                                                   ->select(['officialName', 'usagePercentage']);
     *
     * ********************
     *
     * @param string $identifier
     *
     * @return LanguageEntity|null
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getLanguage($identifier)
    {
        return $this->getLanguageCollection()->findEntity($identifier);
    }

    /**
     * Returns Language Collection object and might applies filters to it.
     *
     * ********************
     *
     * Examples:
     *
     * $languages = (new CountLang\CountLang())->getLanguages();
     * $ancientLanguages = $languages->filter('isAncient', true);
     *
     * $officialCountryLanguages = (new CountLang\CountLang())->getCountry('Denmark')
     *                                                        ->getLanguagesCollection()
     *                                                        ->filter(['isOfficial', true]);
     *
     * ********************
     *
     * @param array $filters
     *
     * @return LanguageCollection
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getLanguages(array $filters = [])
    {
        return $this->filterCollection($this->getLanguageCollection(), $filters);
    }

    /**
     * Returns Region Entity object by identifier [name, code].
     *
     * ********************
     *
     * Examples:
     *
     * $region = (new CountLang\CountLang())->getRegion('Europe');
     * $code = $region->getCode()
     *
     * $countries = (new CountLang\CountLang())->getRegion('Europe')
     *                                         ->getCountriesCollection()
     *                                         ->select('officialName');
     *
     * $subRegions = (new CountLang\CountLang())->getRegion('Europe')
     *                                          ->getSubRegionsCollection()
     *                                          ->select('name');
     *
     * ********************
     *
     * @param string $identifier
     *
     * @return RegionEntity
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getRegion($identifier)
    {
        return $this->getRegionCollection()->findEntity($identifier);
    }

    /**
     * Returns Region Collection object and might applies filters to it.
     *
     * ********************
     *
     * Examples:
     *
     * $regions = (new CountLang\CountLang())->getRegions();
     *
     * ********************
     *
     * @param array $filters
     *
     * @return RegionCollection
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getRegions(array $filters = [])
    {
        return $this->filterCollection($this->getRegionCollection(), $filters);
    }

    /**
     * Returns SubRegion Entity object by identifier [name, code].
     *
     * ********************
     *
     * Examples:
     *
     * $subRegion = (new CountLang\CountLang())->getSubRegion('Northern Europe');
     * $regionCode = $subRegion->getRegionCode()
     *
     * $countries = (new CountLang\CountLang())->getSubRegion('Northern Europe')
     *                                         ->getCountriesCollection()
     *                                         ->select('officialName');
     *
     * $subRegion = (new CountLang\CountLang())->getSubRegion('Northern Europe')
     *                                         ->getRegionEntity();
     *
     * ********************
     *
     * @param string $identifier
     *
     * @return SubRegionEntity
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getSubRegion($identifier)
    {
        return $this->getSubRegionCollection()->findEntity($identifier);
    }

    /**
     * Returns SubRegion Collection object and might applies filters to it.
     *
     * ********************
     *
     * Examples:
     *
     * $subRegions = (new CountLang\CountLang())->getSubRegions();
     *
     * ********************
     *
     * @param array $filters
     *
     * @return SubRegionCollection
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getSubRegions(array $filters = [])
    {
        return $this->filterCollection($this->getSubRegionCollection(), $filters);
    }

    /**
     * Returns Currency Entity object by identifier [officialName, altName, alphaCode, numericCode].
     *
     * ********************
     *
     * Examples:
     *
     * $currency = (new CountLang\CountLang())->getCurrency('DKK');
     * $symbol = $country->getSymbol()
     *
     * $countries = (new CountLang\CountLang())->getCurrency('208')
     *                                         ->getCountriesCollection()
     *                                         ->select('officialName');
     *
     * ********************
     *
     * @param string $identifier
     *
     * @return CurrencyEntity
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getCurrency($identifier)
    {
        return $this->getCurrencyCollection()->findEntity($identifier);
    }

    /**
     * Returns Currency Collection object and might applies filters to it.
     *
     * ********************
     *
     * Examples:
     *
     * $currencies = (new CountLang\CountLang())->getCurrencies();
     * $currenciesWithoutCoins = $currencies->filter('magnitude', 0);
     *
     * echo (new CountLang\CountLang())->getCountry('Zimbabwe')
     *                                 ->getCurrenciesCollection();
     *
     * ********************
     *
     * @param array $filters
     *
     * @return CurrencyCollection
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getCurrencies(array $filters = [])
    {
        return $this->filterCollection($this->getCurrencyCollection(), $filters);
    }

    /**
     * Loads Country Collection object.
     *
     * @return CountryCollection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    private function getCountryCollection()
    {
        return $this->getCollection(CountryCollection::class, new CountryLoader());
    }

    /**
     * Loads Language Collection object.
     *
     * @return LanguageCollection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    private function getLanguageCollection()
    {
        return $this->getCollection(LanguageCollection::class, new LanguageLoader());
    }

    /**
     * Loads Region Collection object.
     *
     * @return RegionCollection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    private function getRegionCollection()
    {
        return $this->getCollection(RegionCollection::class, new RegionLoader());
    }

    /**
     * Loads SubRegion Collection object.
     *
     * @return SubRegionCollection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    private function getSubRegionCollection()
    {
        return $this->getCollection(SubRegionCollection::class, new SubRegionLoader());
    }

    /**
     * Loads Currency Collection object.
     *
     * @return CurrencyCollection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    private function getCurrencyCollection()
    {
        return $this->getCollection(CurrencyCollection::class, new CurrencyLoader());
    }

    /**
     * Loads Collection object from source or cache.
     *
     * @param string $className
     * @param Loader $loader
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    private function getCollection($className, Loader $loader)
    {
        $init = function(Loader $loader) {
            // @codeCoverageIgnoreStart
            return $loader->getCollection();
            // @codeCoverageIgnoreEnd
        };
        $initParams = [new $loader()];

        return (new ObjectCache())->fetchObject($className, $init, $initParams);
    }

    /**
     *  Applies filter(s) to a Collection object and returns result.
     *
     * @param Collection $collection
     * @param array      $filters
     *
     * @return Collection
     *
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    private function filterCollection(Collection $collection, array $filters = [])
    {
        if (0 === count($filters)) {
            return $collection;
        }

        if (empty($filters[0])) {
            throw new \InvalidArgumentException('Invalid filter structure.');
        }

        if (!is_array($filters[0])) {
            $filters = [$filters];
        }

        return $collection->multiFilter($filters);
    }
}