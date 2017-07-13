<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Tests\Unit\Cache;

use CountLang\Cache\ObjectCache;
use CountLang\Entity\Entity\Country;
use CountLang\Entity\Entity\Currency;
use CountLang\Tests\Unit\UnitTestCase;

/**
 * Unit test for CountLang\Cache\ObjectCache class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 *
 * @coversDefaultClass CountLang\Cache\ObjectCache
 * @covers CountLang\Cache\ObjectCache<extended>
 */
class ObjectCacheTest extends UnitTestCase
{
    /**
     * @covers ::containsObject
     */
    public function testContainsObjectInit()
    {
        $objectCache = new ObjectCache();

        $this->assertFalse($objectCache->containsObject(Country::class));
    }

    /**
     * @depends testContainsObjectInit
     * @covers ::saveObject
     */
    public function testSaveObject()
    {
        $objectCache = new ObjectCache();

        $this->assertTrue($objectCache->saveObject(new Country()));
    }

    /**
     * @depends testSaveObject
     * @covers ::containsObject
     */
    public function testContainsObjectAfterSave()
    {
        $objectCache = new ObjectCache();

        $this->assertTrue($objectCache->containsObject(Country::class));
    }

    /**
     * @depends testContainsObjectAfterSave
     * @covers ::fetchObject
     */
    public function testFetchObject()
    {
        $objectCache = new ObjectCache();

        $this->assertInstanceOf(Country::class, $objectCache->fetchObject(Country::class));

        $objectCache->disableCache();
        $init = function($initParams = null) {
            return new Country();
        };
        $initParams = [];
        $this->assertInstanceOf(Country::class, $objectCache->fetchObject(Country::class, $init, $initParams));
        $objectCache->enableCache();

        $this->assertInstanceOf(Currency::class, $objectCache->fetchObject(Currency::class));
    }

    /**
     * @depends testFetchObject
     * @covers ::deleteObject
     */
    public function testDeleteObject()
    {
        $objectCache = new ObjectCache();

        $this->assertTrue($objectCache->deleteObject(Country::class));
    }

    /**
     * @covers ::doGetStats
     */
    public function testGetStats()
    {
        $objectCache = new ObjectCache();

        $this->assertTrue(is_array($objectCache->getStats()));
    }

    /**
     * @covers ::doFlush
     */
    public function testFlush()
    {
        $objectCache = new ObjectCache();

        $this->assertTrue($objectCache->flushAll());
    }
}