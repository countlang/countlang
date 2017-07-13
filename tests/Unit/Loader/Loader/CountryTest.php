<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Tests\Unit\Loader\Loader;

use CountLang\Collection\Collection;
use \CountLang\Loader\Loader;
use CountLang\Tests\Unit\UnitTestCase;

/**
 * Unit test for CountLang\Loader\Loader\Country class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 *
 * @coversDefaultClass CountLang\Loader\Loader\Country
 * @covers CountLang\Loader\Loader\Country<extended>
 */
class CountryTest extends UnitTestCase
{
    /**
     * @covers ::getCollection
     */
    public function testGetCollection()
    {
        $loader = new Loader\Country();

        $this->assertInstanceOf(Collection\Country::class, $loader->getCollection());
        $this->assertInstanceOf(Collection\Country::class, $loader->getCollection());
    }
}