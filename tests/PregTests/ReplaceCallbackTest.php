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

class ReplaceCallbackTest extends BaseTestCase
{
    /**
     * This can be replaced with a setUp() method when appropriate
     *
     * @before
     * @return void
     */
    public function registerFunctionName()
    {
        $this->pregFunction = 'preg_replace_callback()';
    }

    /**
     * @return void
     */
    public function testSuccess()
    {
        $result = Preg::replaceCallback('{(?P<m>d)}', function ($match) {
            return '('.$match[0].')';
        }, 'abcd', -1, $count);

        $this->assertSame(1, $count);
        $this->assertSame('abc(d)', $result);
    }

    /**
     * @return void
     */
    public function testSuccessNoRef()
    {
        $result = Preg::replaceCallback('{(?P<m>d)}', function ($match) {
            return '('.$match[0].')';
        }, 'abcd');

        $this->assertSame('abc(d)', $result);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $result = Preg::replaceCallback('{abc}', function ($match) {
            return '('.$match[0].')';
        }, 'def', -1, $count);

        $this->assertSame(0, $count);
        $this->assertSame('def', $result);
    }

    /**
     * @return void
     */
    public function testBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->setPcreException($pattern = '{(?P<m>d)');

        @Preg::replaceCallback($pattern, function ($match) {
            return '('.$match[0].')';
        }, 'abcd');
    }

    /**
     * @return void
     */
    public function testBadPatternTriggersWarningByDefault()
    {
        $this->setPcreWarning();

        Preg::replaceCallback('{(?P<m>d)', function ($match) {
            return '('.$match[0].')';
        }, 'abcd');
    }

    /**
     * @return void
     */
    public function testThrowsWithSubjectArray()
    {
        $this->doExpectException('InvalidArgumentException', Preg::ARRAY_MSG);

        Preg::replaceCallback('{(?P<m>d)}', function ($match) {
            return '('.$match[0].')';
        }, array('abcd')); // @phpstan-ignore-line
    }
}
