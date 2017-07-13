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
use CountLang\Collection\Collection\SubRegion as SubRegionCollection;
use CountLang\CountLang;
use CountLang\Annotation\Map;

/**
 * Region Entity object.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Entity
 * @link      http://github.com/countlang/countlang
 */
class Region extends Entity
{
    /**
     * @var string Region code.
     * @map(source="code", isIdentifier=true, isRequired=true)
     */
    private $code;
    /**
     * @var string Region name.
     * @map(source="name", isIdentifier=true, isRequired=true)
     */
    private $name;

    /** @var SubRegionCollection SubRegion Collection object with sub-regions that belong to this region. */
    private $subRegionsCollection;

    /** @var CountryCollection Country Collection object with countries that belong to this region. */
    private $countriesCollection;

    /**
     * Return region code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Return region name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns SubRegion Collection object with sub-regions that belong to this region.
     *
     * @return SubRegionCollection
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getSubRegionsCollection()
    {
        if (null !== $this->subRegionsCollection) {
            return $this->subRegionsCollection;
        }

        return $this->subRegionsCollection = (new CountLang())->getSubRegions(['regionCode', $this->getCode()]);
    }

    /**
     * Returns Country Collection object with countries that belong to this region.
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

        return $this->countriesCollection = (new CountLang())->getCountries(['regionCode', $this->getCode()]);
    }
}