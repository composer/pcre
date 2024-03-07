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
use Composer\Pcre\UnexpectedNullMatchException;

class ReplaceCallbackTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_replace_callback()';
    }

    public function testSuccess(): void
    {
        $result = Preg::replaceCallback('{(?P<m>d)}', function ($match) {
            return '('.$match[0].')';
        }, 'abcd', -1, $count);

        self::assertSame(1, $count);
        self::assertSame('abc(d)', $result);
    }

    public function testSuccessWithOffset(): void
    {
        $result = Preg::replaceCallback('{(?P<m>d)}', function ($match) {
            return '('.$match[0][0].')';
        }, 'abcd', -1, $count, PREG_OFFSET_CAPTURE);

        self::assertSame(1, $count);
        self::assertSame('abc(d)', $result);
    }

    public function testSuccessNoRef(): void
    {
        $result = Preg::replaceCallback('{(?P<m>d)}', function ($match) {
            return '('.$match[0].')';
        }, 'abcd');

        self::assertSame('abc(d)', $result);
    }

    public function testFailure(): void
    {
        $result = Preg::replaceCallback('{abc}', function ($match) {
            return '('.$match[0].')';
        }, 'def', -1, $count);

        self::assertSame(0, $count);
        self::assertSame('def', $result);
    }

    public function testSuccessStrictGroups(): void
    {
        $result = Preg::replaceCallbackStrictGroups('{(?P<m>\d)(?<matched>a)?}', function ($match) {
            return strtoupper($match['matched']);
        }, '3a', -1, $count);

        self::assertSame(1, $count);
        self::assertSame('A', $result);
    }

    public function testFailStrictGroups(): void
    {
        self::expectException(UnexpectedNullMatchException::class);
        self::expectExceptionMessage('Pattern "{(?P<m>\d)(?<unmatched>a)?}" had an unexpected unmatched group "unmatched", make sure the pattern always matches or use replaceCallback() instead.');

        $result = Preg::replaceCallbackStrictGroups('{(?P<m>\d)(?<unmatched>a)?}', function ($match) {
            return strtoupper($match['unmatched']);
        }, '123', -1, $count);
    }

    public function testSuccessStrictGroupsOffsets(): void
    {
        $result = Preg::replaceCallbackStrictGroups('{(?P<m>\d)(?<matched>a)?}', function ($match) {
            return strtoupper($match['matched'][0]);
        }, '3a', -1, $count, PREG_OFFSET_CAPTURE);

        self::assertSame(1, $count);
        self::assertSame('A', $result);
    }

    public function testFailsStrictGroupsOffsets(): void
    {
        self::expectException(UnexpectedNullMatchException::class);
        self::expectExceptionMessage('Pattern "{(?P<m>\d)(?<unmatched>a)?}" had an unexpected unmatched group "unmatched", make sure the pattern always matches or use replaceCallback() instead.');

        $result = Preg::replaceCallbackStrictGroups('{(?P<m>\d)(?<unmatched>a)?}', function ($match) {
            return strtoupper($match['unmatched'][0]);
        }, '3', -1, $count, PREG_OFFSET_CAPTURE);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{(?P<m>d)');

        @Preg::replaceCallback($pattern, function ($match) {
            return '('.$match[0].')';
        }, 'abcd');
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();

        Preg::replaceCallback('{(?P<m>d)', function ($match) {
            return '('.$match[0].')';
        }, 'abcd');
    }

    public function testThrowsWithSubjectArray(): void
    {
        $this->doExpectException('InvalidArgumentException', Preg::ARRAY_MSG);

        Preg::replaceCallback('{(?P<m>d)}', function ($match) {
            return '('.$match[0].')';
        }, array('abcd')); // @phpstan-ignore-line
    }
}
