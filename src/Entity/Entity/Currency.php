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
use CountLang\Collection\Collection\Country as CountryCollection;
use CountLang\Filter\Filter;
use CountLang\CountLang;
use CountLang\Annotation\Map;

/**
 * Currency Entity object.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Entity
 * @link      http://github.com/countlang/countlang
 */
class Currency extends Entity
{
    /** ********** name block ********** */

    /**
     * @var string Currency official (full) name.
     * @map(source="name_official", isIdentifier=true, isRequired=true)
     */
    private $officialName;
    /**
     * @var string Short name of currency.
     * @map(source="name_short", isIdentifier=true, isRequired=true)
     */
    private $shortName;
    /**
     * @var array Array with alternative names for currency.
     * @map(source="name_alt", isIdentifier=true, isRequired=true)
     */
    private $altNames = [];

    /** ********** isoCodes block ********** */

    /**
     * @var string Alpha code of currency according to the ISO 4217.
     * @map(source="isoCodes_alpha", isIdentifier=true, isRequired=true)
     */
    private $alphaCode;
    /**
     * @var string Numeric code of currency according to the ISO 4217.
     * @map(source="isoCodes_numeric", isIdentifier=true, isRequired=true)
     */
    private $numericCode;

    /** ********** symbol block ********** */

    /**
     * @var string Currency symbol.
     * @map(source="symbol", isIdentifier=false, isRequired=true)
     */
    private $symbol;

    /** ********** magnitude block ********** */

    /**
     * @var integer Currency magnitude.
     * @map(source="magnitude", isIdentifier=false, isRequired=false)
     */
    private $magnitude;

    // ----------------------------------------

    /** @var CountryCollection Country Collection object with countries that use this currency. */
    private $countriesCollection;

    /**
     * Returns currency official (full) name.
     *
     * @return string
     */
    public function getOfficialName()
    {
        return $this->officialName;
    }

    /**
     * Returns short name of currency.
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Returns array with alternative names for currency.
     *
     * @return array
     */
    public function getAltNames()
    {
        return $this->altNames;
    }

    /**
     * Returns alpha code of currency according to the ISO 4217.
     *
     * @return string
     */
    public function getAlphaCode()
    {
        return $this->alphaCode;
    }

    /**
     * Returns numeric code of currency according to the ISO 4217.
     *
     * @return string
     */
    public function getNumericCode()
    {
        return $this->numericCode;
    }

    /**
     * Returns currency symbol.
     *
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Returns currency magnitude.
     *
     * @return int
     */
    public function getMagnitude()
    {
        return $this->magnitude;
    }

    /**
     * Returns Country Collection object with countries that use this currency.
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

        return $this->countriesCollection = (new CountLang())->getCountries(
            ['currencies', $this->getAlphaCode(), Filter::OPERATOR_HAS]
        );
    }
}