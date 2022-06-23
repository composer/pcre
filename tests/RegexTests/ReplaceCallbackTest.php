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

class ReplaceCallbackTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_replace_callback()';
    }

    public function testSuccess(): void
    {
        $result = Regex::replaceCallback('{(?P<m>d)}', function ($match) {
            return '('.$match[0].')';
        }, 'abcd');

        self::assertInstanceOf('Composer\Pcre\ReplaceResult', $result);
        self::assertTrue($result->matched);
        self::assertSame(1, $result->count);
        self::assertSame('abc(d)', $result->result);
    }

    public function testFailure(): void
    {
        $result = Regex::replaceCallback('{abc}', function ($match) {
            return '('.$match[0].')';
        }, 'def');

        self::assertInstanceOf('Composer\Pcre\ReplaceResult', $result);
        self::assertFalse($result->matched);
        self::assertSame(0, $result->count);
        self::assertSame('def', $result->result);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{(?P<m>d)');

        @Regex::replaceCallback($pattern, function ($match) {
            return '('.$match[0].')';
        }, 'abcd');
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();

        Regex::replaceCallback('{(?P<m>d)', function ($match) {
            return '('.$match[0].')';
        }, 'abcd');
    }

    public function testThrowsWithSubjectArray(): void
    {
        $this->doExpectException('InvalidArgumentException', Preg::ARRAY_MSG);

        Regex::replaceCallback('{(?P<m>d)}', function ($match) {
            return '('.$match[0].')';
        }, array('abcd')); // @phpstan-ignore-line
    }
}
