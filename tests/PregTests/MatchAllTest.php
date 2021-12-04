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

class MatchAllTest extends BaseTestCase
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
        $count = Preg::matchAll('{[aei]}', 'abcdefghijklmnopqrstuvwxyz', $matches);
        $this->assertSame(3, $count);
        $this->assertSame(array(0 => array('a', 'e', 'i')), $matches);
    }

    /**
     * @return void
     */
    public function testSuccessNoRef()
    {
        $count = Preg::matchAll('{[aei]}', 'abcdefghijklmnopqrstuvwxyz');
        $this->assertSame(3, $count);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $count = Preg::matchAll('{abc}', 'def', $matches);
        $this->assertSame(0, $count);
        $this->assertSame(array(array()), $matches);
    }

    /**
     * @return void
     */
    public function testBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->expectPcreException($pattern = '{[aei]');
        @Preg::matchAll($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testBadPatternTriggersWarningByDefault()
    {
        $this->expectPcreWarning();
        Preg::matchAll('{[aei]', 'abcdefghijklmnopqrstuvwxyz');
    }
}
