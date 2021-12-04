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

class GrepTest extends BaseTestCase
{
    /**
     * This can be replaced with a setUp() method when appropriate
     *
     * @before
     * @return void
     */
    public function registerFunctionName()
    {
        $this->pregFunction = 'preg_grep()';
    }

    /**
     * @return void
     */
    public function testSuccess()
    {
        $result = Preg::grep('{[bc]}', array('a', 'b', 'c'));
        $this->assertSame(array(1 => 'b', 2 => 'c'), $result);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $result = Preg::grep('{[de]}', array('a', 'b', 'c'));
        $this->assertSame(array(), $result);
    }

    /**
     * @return void
     */
    public function testBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->expectPcreException($pattern = '{[de]');
        @Preg::grep($pattern, array('a', 'b', 'c'));
    }

    /**
     * @return void
     */
    public function testBadPatternTriggersWarningByDefault()
    {
        $this->expectPcreWarning();
        Preg::grep('{[de]', array('a', 'b', 'c'));
    }
}
