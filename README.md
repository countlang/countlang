CountLang
==============================

[![Release](https://img.shields.io/packagist/v/countlang/countlang.svg)](https://packagist.org/packages/countlang/countlang)
[![Build Status](https://img.shields.io/travis/countlang/countlang/master.svg)](http://travis-ci.org/countlang/countlang)
[![Code Coverage](https://img.shields.io/coveralls/countlang/countlang/master.svg)](https://coveralls.io/r/countlang/countlang)
[![License](https://img.shields.io/packagist/l/countlang/countlang.svg)](https://raw.githubusercontent.com/countlang/countlang/master/LICENSE)

CountLang is a PHP library for the [Unicode CLDR](http://cldr.unicode.org) project.

## Summary

The library provides data for any country, language, currency, region, sub-region and relationship between them.

Country is the base entity of CountLang. Language, currency, region and sub-region are independent entities, but they are all connected through the country.

Connection between entities is provided by a map of relations that binds the country with other entities.

Data is available for all countries that existed since 1974 (year of first issue of ISO 3166).

Doctrine cache is used to improve data parsing and mapping.

## Installing

The easiest way to install this package is with [Composer](https://getcomposer.org) using the following command:

    $ composer require countlang/countlang

## Examples

Include composer loader and use library interface:

```php
// include composer autoloader if you haven't done it yet
require __DIR__ . '/vendor/autoload.php';

// include library interface
use CountLang\Countlang;

// initialize CountLand interface
$countLang = new Countlang();
```

#### Country

```php
// get a collection with all countries 
$allCountries = $countlang->getCountries();
// get a list of all official country names 
$allCountryNames = $allCountries->select('officialName');

// get the country entity of Denmark
$denmark = $countlang->getCountry('Denmark');

// get a collection of countries that share common border with Denmark
$denmarkNeigbors = $denmark->getBordersCollection();

// get a collection with all languages of Denmark
$denmarkLanguages = $denmark->getLanguagesCollection();
// get usage percentage of Danish language in Denmark
$usagePercentageOfDanishLanguageInDenmark = $denmarkLanguages->findEntity('Danish')->getUsagePercentage();

// get first currency entity from collection of Denmark's currencies
$denmarkCurrency = $denmark->getCurrenciesCollection()->getEntity();

// get name of Denmark's sub-region
$denmarkSubRegion = $denmark->getSubRegionEntity()->getName();

// get code of region Denmark is belong to
$denmarkRegion = $denmark->getRegionEntity()->getCode();
```

#### Language

```php
// get a collection of all languages 
$allLanguages = $countlang->getLanguages();
// get a list of alpha 3T codes (according to ISO 639) for all languages 
$allLanguagesCodes = $allLanguages->select('alpha3TCode');

// get the language entity of Danish
$danishLanguage = $countlang->getLanguage('Danish');

// get a collection with all Danish-speaking countries entities
$danishLanguageCountries = $danishLanguage->getCountriesCollection();
// get a collection with all Danish-speaking countries 
// that have a population more than half a million people
$mostPopulatedDanishLanguageCountries = $danishLanguageCountries->filter('population', 500000, 'gt');
// get a collection with all Danish-speaking countries 
// that have a population less than 100 thousand people
// and have access to the world's ocean
$filteredDanishLanguageCountries = $danishLanguageCountries->multiFilter([
    ['population', 100000, CountLang\Filter\Filter::OPERATOR_LT],
    ['isLandLocked', false],
]);
```

#### Currency

```php
// get a collection of all currencies 
$allCurrencies = $countlang->getCurrencies();
// get an associative array with official names and symbols of all countries
$allCurrenciesNamesAndSymbols = $allCurrencies->select(['officialName', 'symbol']);

// get the currency entity of Danish Krone
$danishKrona = $countlang->getCurrency('Danish Krone');

// get a collection of all countries that use Danish Krone
$danishKronaCountries = $danishKrona->getCountriesCollection();
```

#### Region

```php
// get a collection of all regions 
$allRegions = $countlang->getRegions();
// print the collection (in JSON format)
// Note: when used as a string, collection or entity will be converted to JSON
echo $allRegions;

// get the region entity of Europe
$europe = $countlang->getRegion('Europe');

// get a collection of European countries
$europeanCountries = $europe->getCountriesCollection();

// get a collection of Europe sub-regions
$subRegions = $europe->getSubRegionsCollection();
```

#### SubRegion

```php
// get a collection of all sub-regions 
$allSubRegions = $countlang->getSubRegions();
// get an associative array with complete data for all sub-regions 
$allSubRegionsData = $allSubRegions->select();

// get the region entity of Northern Europe
$northernEurope = $countlang->getSubRegion('Northern Europe');

// get the region entity of Europe
$europe = $northernEurope->getRegionEntity();

// get the number of countries in the Northern Europe sub-region
$northernEuropeanCountriesAmount = $northernEurope->getCountriesCollection()->count();
```

## Sources
The main source of data is the most recent release of [Unicode CLDR](http://cldr.unicode.org). Additionally used [ISO 3166](https://www.iso.org/iso-3166-country-codes.html), [ISO 639](https://www.iso.org/iso-639-language-codes.html), [ISO 4217](https://www.iso.org/iso-4217-currency-codes.html), [World Bank Open Data](http://data.worldbank.org), [The World Factbook](https://www.cia.gov/library/publications/the-world-factbook), [Wikipedia](https://www.wikipedia.org), [WTNG](http://www.wtng.info).


## License
This library is available under the [MIT license](LICENSE).