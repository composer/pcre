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

class SplitTest extends BaseTestCase
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
        $result = Preg::split('{[\s,]+}', 'a, b, c');
        self::assertSame(array('a', 'b', 'c'), $result);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $result = Preg::split('{[\s,]+}', 'abc');
        self::assertSame(array('abc'), $result);
    }

    /**
     * @return void
     */
    public function testBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->expectPcreException($pattern = '{[\s,]+');
        @Preg::split($pattern, 'a, b, c');
    }

    /**
     * @return void
     */
    public function testBadPatternTriggersWarningByDefault()
    {
        $this->expectPcreWarning();
        Preg::split('{[\s,]+', 'a, b, c');
    }
}
