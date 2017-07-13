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
use CountLang\CountLang;
use CountLang\Annotation\Map;

/**
 * SubRegion Entity object.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Entity
 * @link      http://github.com/countlang/countlang
 */
class SubRegion extends Entity
{
    /**
     * @var string Sub-region code.
     * @map(source="code", isIdentifier=true, isRequired=true)
     */
    private $code;
    /**
     * @var string Sub-region name.
     * @map(source="name", isIdentifier=true, isRequired=true)
     */
    private $name;
    /**
     * @var string Region code.
     * @map(source="regionCode", isIdentifier=false, isRequired=true)
     */
    private $regionCode;

    /** @var Region Region Entity object of region this sub-region is belong to. */
    protected $regionEntity;

    /** @var CountryCollection Country Collection object with countries that belong to this sub-region. */
    protected $countriesCollection;

    /**
     * Returns sub-region code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns sub-region name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns region code
     *
     * @return string
     */
    public function getRegionCode()
    {
        return $this->regionCode;
    }

    /**
     * Returns Region Entity object of region this sub-region is belong to.
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
     * Returns Country Collection object with countries that belong to this sub-region.
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

        return $this->countriesCollection = (new CountLang())->getCountries(['subRegionCode', $this->getCode()]);
    }
}