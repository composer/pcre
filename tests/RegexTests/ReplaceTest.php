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
    public function setUp(): void
    {
        $this->pregFunction = 'preg_replace()';
    }

    public function testSuccess(): void
    {
        $result = Regex::replace('{(?P<m>d)}', 'e', 'abcd');
        self::assertInstanceOf('Composer\Pcre\ReplaceResult', $result);
        self::assertTrue($result->matched);
        self::assertSame(1, $result->count);
        self::assertSame('abce', $result->result);
    }

    public function testFailure(): void
    {
        $result = Regex::replace('{abc}', '123', 'def');
        self::assertInstanceOf('Composer\Pcre\ReplaceResult', $result);
        self::assertFalse($result->matched);
        self::assertSame(0, $result->count);
        self::assertSame('def', $result->result);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{(?P<m>d)');
        @Regex::replace($pattern, 'e', 'abcd');
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();
        Regex::replace('{(?P<m>d)', 'e', 'abcd');
    }

    public function testThrowsWithSubjectArray(): void
    {
        $this->doExpectException('InvalidArgumentException', Preg::ARRAY_MSG);
        // @phpstan-ignore-next-line
        Regex::replace('{(?P<m>d)}', 'e', array('abcd'));
    }
}
