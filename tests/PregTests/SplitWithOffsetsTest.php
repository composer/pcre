<?php

/*
 * This file is part of composer/pcre.
 *
 * (c) Composer <https://github.com/composer>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Composer\Pcre\PregTests;

use Composer\Pcre\BaseTestCase;
use Composer\Pcre\Preg;

class SplitWithOffsetsTest extends BaseTestCase
{
    /**
     * This can be replaced with a setUp() method when appropriate
     *
     * @before
     * @return void
     */
    public function registerFunctionName()
    {
        $this->pregFunction = 'preg_split()';
    }

    /**
     * @return void
     */
    public function testSuccess()
    {
        $result = Preg::splitWithOffsets('{[\s,]+}', 'a, b, c');
        self::assertSame(array(array('a', 0), array('b', 3), array('c', 6)), $result);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $result = Preg::splitWithOffsets('{[\s,]+}', 'abc');
        self::assertSame(array(array('abc', 0)), $result);
    }

    /**
     * @return void
     */
    public function testBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->expectPcreException($pattern = '{[\s,]+');
        @Preg::splitWithOffsets($pattern, 'a, b, c');
    }

    /**
     * @return void
     */
    public function testBadPatternTriggersWarningByDefault()
    {
        $this->expectPcreWarning();
        Preg::splitWithOffsets('{[\s,]+', 'a, b, c');
    }
}
