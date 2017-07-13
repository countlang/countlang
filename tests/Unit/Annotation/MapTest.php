<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Tests\Unit\Annotation;

use CountLang\Annotation\Map;
use CountLang\Tests\Unit\UnitTestCase;

/**
 * Unit test for CountLang\Annotation\Map class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 *
 * @coversDefaultClass CountLang\Annotation\Map
 */
class MapTest extends UnitTestCase
{
    /**
     * @covers ::getAsArray
     */
    public function testCountLang()
    {
        $map = new Map();
        $expected = [
            'type'         => 'string',
            'source'       => null,
            'isIdentifier' => null,
            'isRequired'   => null,
        ];

        $this->assertEquals($expected, $map->getAsArray('string'));

        $map->type = 'string';
        $this->assertEquals($expected, $map->getAsArray('array'));
    }
}