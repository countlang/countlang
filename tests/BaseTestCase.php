<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Tests;

use CountLang\Cache\ObjectCache;
use CountLang\CountLang;

/**
 * Basic tests class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Returns CountLang\CountLang object.
     *
     * @param bool $useCache
     *
     * @return CountLang
     */
    protected function getCountLang($useCache = true)
    {
        $objectCache = new ObjectCache();

        if ($useCache) {
            $objectCache->enableCache();
        } else {
            $objectCache->disableCache();
        }

        return new CountLang();
    }
}