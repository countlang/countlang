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

use CountLang\Tests\Unit\UnitTestCase;

/**
 * Unit test for CountLang\Entity\Entity class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 *
 * @coversDefaultClass CountLang\Entity\Entity
 */
class EntityTest extends UnitTestCase
{
    /**
     * @covers ::select
     */
    public function testSelect()
    {
        $this->assertInternalType('array', $this->getCountLang()->getCountry('Denmark')->select());
        $this->assertInternalType('int', $this->getCountLang()->getCountry('Denmark')->select('population'));
        $this->assertInternalType('array', $this->getCountLang()->getCountry('Denmark')->select(['population', 'area']));

        $this->expectException(\InvalidArgumentException::class);
        $this->getCountLang()->getCountry('Denmark')->select(['population', 'areal']);
    }

    /**
     * @covers ::__toString
     */
    public function testToString()
    {
        $this->assertInternalType('string', (string)$this->getCountLang()->getCountry('Denmark'));
    }
}