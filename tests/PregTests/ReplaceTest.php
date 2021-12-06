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

class ReplaceTest extends BaseTestCase
{
    /**
     * This can be replaced with a setUp() method when appropriate
     *
     * @before
     * @return void
     */
    public function registerFunctionName()
    {
        $this->pregFunction = 'preg_replace()';
    }

    /**
     * @return void
     */
    public function testSuccess()
    {
        $result = Preg::replace('{(?P<m>d)}', 'e', 'abcd', -1, $count);
        self::assertSame(1, $count);
        self::assertSame('abce', $result);
    }

    /**
     * @return void
     */
    public function testSuccessNoRef()
    {
        $result = Preg::replace('{(?P<m>d)}', 'e', 'abcd', -1);
        self::assertSame('abce', $result);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $result = Preg::replace('{abc}', '123', 'def', -1, $count);
        self::assertSame(0, $count);
        self::assertSame('def', $result);
    }

    /**
     * @return void
     */
    public function testBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->expectPcreException($pattern = '{(?P<m>d)');
        @Preg::replace($pattern, 'e', 'abcd', -1);
    }

    /**
     * @return void
     */
    public function testBadPatternTriggersWarningByDefault()
    {
        $this->expectPcreWarning();
        Preg::replace('{(?P<m>d)', 'e', 'abcd', -1);
    }

    /**
     * @return void
     */
    public function testThrowsWithSubjectArray()
    {
        $this->doExpectException('InvalidArgumentException', Preg::ARRAY_MSG);
        // @phpstan-ignore-next-line
        Preg::replace('{(?P<m>d)}', 'e', array('abcd'));
    }
}
