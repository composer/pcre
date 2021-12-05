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

class IsMatchAllTest extends BaseTestCase
{
    /**
     * This can be replaced with a setUp() method when appropriate
     *
     * @before
     * @return void
     */
    public function registerFunctionName()
    {
        $this->pregFunction = 'preg_match_all()';
    }

    /**
     * @return void
     */
    public function testSuccess()
    {
        $result = Preg::isMatchAll('{[aei]}', 'abcdefghijklmnopqrstuvwxyz', $matches);
        $this->assertSame(true, $result);
        $this->assertSame(array(0 => array('a', 'e', 'i')), $matches);
    }

    /**
     * @return void
     */
    public function testSuccessNoRef()
    {
        $result = Preg::isMatchAll('{[aei]}', 'abcdefghijklmnopqrstuvwxyz');
        $this->assertSame(true, $result);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $result = Preg::isMatchAll('{abc}', 'def', $matches);
        $this->assertSame(false, $result);
        $this->assertSame(array(array()), $matches);
    }

    /**
     * @return void
     */
    public function testBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->expectPcreException($pattern = '{[aei]');
        @Preg::isMatchAll($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testBadPatternTriggersWarningByDefault()
    {
        $this->expectPcreWarning();
        Preg::isMatchAll('{[aei]', 'abcdefghijklmnopqrstuvwxyz');
    }
}
