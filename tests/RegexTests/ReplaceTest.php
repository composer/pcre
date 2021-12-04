<?php

/*
 * This file is part of composer/pcre.
 *
 * (c) Composer <https://github.com/composer>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Composer\Pcre\RegexTests;

use Composer\Pcre\BaseTestCase;
use Composer\Pcre\Preg;
use Composer\Pcre\Regex;

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
        $result = Regex::replace('{(?P<m>d)}', 'e', 'abcd');
        $this->assertInstanceOf('Composer\Pcre\ReplaceResult', $result);
        $this->assertTrue($result->matched);
        $this->assertSame(1, $result->count);
        $this->assertSame('abce', $result->result);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $result = Regex::replace('{abc}', '123', 'def');
        $this->assertInstanceOf('Composer\Pcre\ReplaceResult', $result);
        $this->assertFalse($result->matched);
        $this->assertSame(0, $result->count);
        $this->assertSame('def', $result->result);
    }

    /**
     * @return void
     */
    public function testBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->expectPcreException($pattern = '{(?P<m>d)');
        @Regex::replace($pattern, 'e', 'abcd');
    }

    /**
     * @return void
     */
    public function testBadPatternTriggersWarningByDefault()
    {
        $this->expectPcreWarning();
        Regex::replace('{(?P<m>d)', 'e', 'abcd');
    }

    /**
     * @return void
     */
    public function testThrowsWithSubjectArray()
    {
        $this->doExpectException('InvalidArgumentException', Preg::ARRAY_MSG);
        // @phpstan-ignore-next-line
        Regex::replace('{(?P<m>d)}', 'e', array('abcd'));
    }
}
