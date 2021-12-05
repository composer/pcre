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

class IsMatchTest extends BaseTestCase
{
    /**
     * This can be replaced with a setUp() method when appropriate
     *
     * @before
     * @return void
     */
    public function registerFunctionName()
    {
        $this->pregFunction = 'preg_match()';
    }

    /**
     * @return void
     */
    public function testSuccess()
    {
        $result = Preg::isMatch('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz', $matches);
        $this->assertSame(true, $result);
        $this->assertSame(array(0 => 'i', 'm' => 'i', 1 => 'i'), $matches);
    }

    /**
     * @return void
     */
    public function testSuccessNoRef()
    {
        $result = Preg::isMatch('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz');
        $this->assertSame(true, $result);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $result = Preg::isMatch('{abc}', 'def', $matches);
        $this->assertSame(false, $result);
        $this->assertSame(array(), $matches);
    }

    /**
     * @return void
     */
    public function testBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->expectPcreException($pattern = '{(?P<m>[io])');
        @Preg::isMatch($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testBadPatternTriggersWarningByDefault()
    {
        $this->expectPcreWarning();
        Preg::isMatch('{(?P<m>[io])', 'abcdefghijklmnopqrstuvwxyz');
    }
}
