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
use Composer\Pcre\MatchFirstResult;
use Composer\Pcre\Regex;

class MatchFirstTest extends BaseTestCase
{
    private const REGEX = '/test=([\w]+)/';

    public function setUp(): void
    {
        $this->pregFunction = 'preg_match()';
    }

    public function testSuccessFirstMatch(): void
    {
        $result = Regex::matchFirst(self::REGEX, 'test=rock');
        self::assertInstanceOf(MatchFirstResult::class, $result);
        self::assertTrue($result->matched);
        self::assertSame('rock', $result->match);
    }

    public function testSuccessSecondMatch(): void
    {
        $result = Regex::matchFirst(self::REGEX, 'test=32');
        self::assertInstanceOf(MatchFirstResult::class, $result);
        self::assertTrue($result->matched);
        self::assertSame('32', $result->match);
    }

    public function testFailure(): void
    {
        $result = Regex::matchFirst(self::REGEX, 'test');
        self::assertInstanceOf(MatchFirstResult::class, $result);
        self::assertFalse($result->matched);
        self::assertSame(null, $result->match);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{abc');
        @Regex::matchFirst($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();
        Regex::matchFirst('{abc', 'abcdefghijklmnopqrstuvwxyz');
    }

    public function testThrowsIfEngineErrors(): void
    {
        $this->expectPcreEngineException($pattern = '/(?:\D+|<\d+>)*[!?]/');
        Regex::matchFirst($pattern, 'foobar foobar foobar');
    }
}
