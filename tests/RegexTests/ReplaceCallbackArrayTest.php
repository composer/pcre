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

/**
 * @requires PHP 7.0
 */
class ReplaceCallbackArrayTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_replace_callback_array()';
    }

    public function testSuccess(): void
    {
        $result = Regex::replaceCallbackArray(array('{(?P<m>d)}' => function ($match) {
            return '('.$match[0].')';
        }), 'abcd');

        self::assertInstanceOf('Composer\Pcre\ReplaceResult', $result);
        self::assertTrue($result->matched);
        self::assertSame(1, $result->count);
        self::assertSame('abc(d)', $result->result);
    }

    public function testFailure(): void
    {
        $result = Regex::replaceCallbackArray(array('{abc}' => function ($match) {
            return '('.$match[0].')';
        }), 'def');

        self::assertInstanceOf('Composer\Pcre\ReplaceResult', $result);
        self::assertFalse($result->matched);
        self::assertSame(0, $result->count);
        self::assertSame('def', $result->result);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{(?P<m>d)');

        @Regex::replaceCallbackArray(array($pattern => function ($match) {
            return '('.$match[0].')';
        }), 'abcd');
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();

        Regex::replaceCallbackArray(array('{(?P<m>d)' => function ($match) {
            return '('.$match[0].')';
        }), 'abcd');
    }

    public function testThrowsWithSubjectArray(): void
    {
        $this->doExpectException('InvalidArgumentException', Preg::ARRAY_MSG);

        Regex::replaceCallbackArray(array('{(?P<m>d)}' => function ($match) {
            return '('.$match[0].')';
        }), array('abcd')); // @phpstan-ignore-line
    }
}
