<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Tests\Unit\Entity;

use CountLang\Entity\EntityMap;
use CountLang\Tests\Unit\UnitTestCase;
use CountLang\Entity\Entity;

/**
 * Unit test for CountLang\Entity\EntityMap class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 *
 * @coversDefaultClass CountLang\Entity\EntityMap
 * @covers CountLang\Entity\EntityMap<extended>
 */
class EntityMapTest extends UnitTestCase
{
    /**
     * @covers ::getMap
     */
    public function testGetEntityMap()
    {
        $this->assertInternalType(
            'array',
            (new EntityMap(Entity\Country::class))->getMap()
        );
    }

    /**
     * @covers ::getPropertyParams
     */
    public function testGetPropertyParams()
    {
        $this->assertInternalType(
            'array',
            (new EntityMap(Entity\Country::class))->getPropertyParams('population')
        );

        $this->expectException(\UnexpectedValueException::class);
        (new EntityMap(Entity\Country::class))->getPropertyParams('name');
    }
}