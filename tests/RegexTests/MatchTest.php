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
