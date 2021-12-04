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

class MatchTest extends BaseTestCase
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
        $count = Preg::match('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz', $matches);
        $this->assertSame(1, $count);
        $this->assertSame(array(0 => 'i', 'm' => 'i', 1 => 'i'), $matches);
    }

    /**
     * @return void
     */
    public function testSuccessNoRef()
    {
        $count = Preg::match('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz');
        $this->assertSame(1, $count);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $count = Preg::match('{abc}', 'def', $matches);
        $this->assertSame(0, $count);
        $this->assertSame(array(), $matches);
    }

    /**
     * @return void
     */
    public function testBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->setPcreException($pattern = '{(?P<m>[io])');
        @Preg::match($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testBadPatternTriggersWarningByDefault()
    {
        $this->setPcreWarning();
        Preg::match('{(?P<m>[io])', 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testThrowsIfEngineErrors()
    {
        $this->setPcreEngineException($pattern = '/(?:\D+|<\d+>)*[!?]/');
        Preg::match($pattern, 'foobar foobar foobar');
    }
}
