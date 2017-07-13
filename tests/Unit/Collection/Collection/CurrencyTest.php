<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Tests\Unit\Collection\Collection;

use CountLang\Tests\Unit\UnitTestCase;
use CountLang\Entity\Entity;

/**
 * Unit test for CountLang\Collection\Collection\Currency class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 *
 * @coversDefaultClass CountLang\Collection\Collection\Currency
 * @covers CountLang\Collection\Collection\Currency<extended>
 */
class CurrencyTest extends UnitTestCase
{
    /**
     * @covers ::getEntity
     */
    public function testGetEntity()
    {
        $this->assertInstanceOf(
            Entity\Currency::class,
            $this->getCountLang()->getCurrencies()->getEntity());
    }
}