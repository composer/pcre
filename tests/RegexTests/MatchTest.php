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
use Composer\Pcre\Regex;
use Composer\Pcre\UnexpectedNullMatchException;

class MatchTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_match()';
    }

    public function testSuccess(): void
    {
        $result = Regex::match('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz');
        self::assertInstanceOf('Composer\Pcre\MatchResult', $result);
        self::assertTrue($result->matched);
        self::assertSame(array(0 => 'i', 'm' => 'i', 1 => 'i'), $result->matches);
    }

    public function testFailure(): void
    {
        $result = Regex::match('{abc}', 'def');
        self::assertInstanceOf('Composer\Pcre\MatchResult', $result);
        self::assertFalse($result->matched);
        self::assertSame(array(), $result->matches);
    }

    public function testSuccessStrictGroups(): void
    {
        $result = Regex::matchStrictGroups('{(?<m>\d)(?<matched>a)}', '3a');
        self::assertSame(array(0 => '3a', 'm' => '3', 1 => '3', 'matched' => 'a', 2 => 'a'), $result->matches);
    }

    public function testFailStrictGroups(): void
    {
        self::expectException(UnexpectedNullMatchException::class);
        self::expectExceptionMessage('Pattern "{(?<m>\d)(?<unmatched>b)?}" had an unexpected unmatched group "unmatched", make sure the pattern always matches or use match() instead.');
        // @phpstan-ignore composerPcre.unsafeStrictGroups
        Regex::matchStrictGroups('{(?<m>\d)(?<unmatched>b)?}', '123');
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{abc');
        @Regex::match($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();
        Regex::match('{abc', 'abcdefghijklmnopqrstuvwxyz');
    }

    public function testThrowsIfEngineErrors(): void
    {
        $this->expectPcreEngineException($pattern = '/(?:\D+|<\d+>)*[!?]/');
        Regex::match($pattern, 'foobar foobar foobar');
    }
}
