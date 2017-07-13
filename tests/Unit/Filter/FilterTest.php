<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Tests\Unit\Filter;

use CountLang\Filter\Filter;
use CountLang\Entity\Entity;
use CountLang\Collection\Collection;
use CountLang\Tests\Unit\UnitTestCase;

/**
 * Unit test for CountLang\Filter\Filter class.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Tests
 * @link      http://github.com/countlang/countlang
 *
 * @coversDefaultClass CountLang\Filter\Filter
 * @covers CountLang\Filter\Filter<extended>
 */
class FilterTest extends UnitTestCase
{
    /**
     * @covers ::applyFilter
     */
    public function testApplyFilter()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->expectException(\InvalidArgumentException::class);
        $filter->applyFilter('currencies', 'Euro', Filter::OPERATOR_LIKE);
    }

    /**
     * @covers ::applyFilters
     */
    public function testApplyFilters()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(Collection\Country::class, $filter->applyFilters([
            ['officialName', 'Kingdom of Denmark'],
            ['regionCode', '150']
        ]));
    }

    /**
     * @covers ::findEntityByIdentifier
     */
    public function testFindEntityByIdentifier()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(Entity\Country::class, $filter->findEntityByIdentifier('Kingdom of Denmark'));
        $this->assertNull($filter->findEntityByIdentifier(null));
    }

    /**
     * @covers ::eq
     */
    public function testEq()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('officialName', 'Kingdom of Denmark', Filter::OPERATOR_EQ)
        );

        $this->expectException(\InvalidArgumentException::class);
        $filter->applyFilter('officialName', ['Kingdom of Denmark'], Filter::OPERATOR_EQ);
    }

    /**
     * @covers ::neq
     */
    public function testNeq()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('officialName', 'Kingdom of Denmark', Filter::OPERATOR_NEQ)
        );
    }

    /**
     * @covers ::isNull
     */
    public function testIsNull()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('unAssignmentYear', null, Filter::OPERATOR_IS_NULL)
        );
    }

    /**
     * @covers ::isNotNull
     */
    public function testIsNotNull()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('unAssignmentYear', null, Filter::OPERATOR_IS_NOT_NULL)
        );
    }

    /**
     * @covers ::in
     */
    public function testIn()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('subRegionCode', ['154', '155'], Filter::OPERATOR_IN)
        );
    }

    /**
     * @covers ::notIn
     */
    public function testNotIn()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('subRegionCode', ['154', '155'], Filter::OPERATOR_NOT_IN)
        );
    }

    /**
     * @covers ::gt
     */
    public function testGt()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('population', 50, Filter::OPERATOR_GT)
        );
    }

    /**
     * @covers ::gte
     */
    public function testGte()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('population', 50, Filter::OPERATOR_GTE)
        );
    }

    /**
     * @covers ::lt
     */
    public function testLt()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('population', 50, Filter::OPERATOR_LT)
        );
    }

    /**
     * @covers ::lte
     */
    public function testLte()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('population', 50, Filter::OPERATOR_LTE)
        );
    }

    /**
     * @covers ::btw
     */
    public function testBtw()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('population', [0, 50], Filter::OPERATOR_BTW)
        );

        $this->expectException(\InvalidArgumentException::class);
        $filter->applyFilter('population', [50], Filter::OPERATOR_BTW);
    }

    /**
     * @covers ::like
     */
    public function testLike()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('officialName', 'Denmark', Filter::OPERATOR_LIKE)
        );
    }

    /**
     * @covers ::notLike
     */
    public function testNotLike()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('officialName', 'of', Filter::OPERATOR_NOT_LIKE)
        );
    }

    /**
     * @covers ::has
     */
    public function testHas()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('borders', 'DNK', Filter::OPERATOR_HAS)
        );
    }

    /**
     * @covers ::notHas
     */
    public function testNotHas()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('borders', 'DNK', Filter::OPERATOR_NOT_HAS)
        );
    }

    /**
     * @covers ::isEmpty
     */
    public function testIsEmpty()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('borders', null, Filter::OPERATOR_IS_EMPTY)
        );
    }

    /**
     * @covers ::isNotEmpty
     */
    public function testIsNotEmpty()
    {
        $filter = new Filter($this->getCountLang()->getCountries());

        $this->assertInstanceOf(
            Collection\Country::class,
            $filter->applyFilter('borders', null, Filter::OPERATOR_IS_NOT_EMPTY)
        );
    }
}