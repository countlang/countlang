<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Collection\Collection;

use CountLang\Collection\Collection;
use CountLang\Entity\Entity\Country as CountryEntity;

/**
 * Country Collection object.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Collection
 * @link      http://github.com/countlang/countlang
 */
class Country extends Collection
{
    /**
     * Return CountryEntity class name.
     *
     * @inheritdoc
     */
    protected function getEntityClassName()
    {
        return CountryEntity::class;
    }
}