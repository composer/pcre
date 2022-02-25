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

class MatchAllTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_match_all()';
    }

    public function testSuccess(): void
    {
        $result = Regex::matchAll('{[aei]}', 'abcdefghijklmnopqrstuvwxyz');
        self::assertInstanceOf('Composer\Pcre\MatchAllResult', $result);
        self::assertTrue($result->matched);
        self::assertSame(3, $result->count);
        self::assertSame(array(0 => array('a', 'e', 'i')), $result->matches);
    }

    public function testFailure(): void
    {
        $result = Regex::matchAll('{abc}', 'def');
        self::assertInstanceOf('Composer\Pcre\MatchAllResult', $result);
        self::assertFalse($result->matched);
        self::assertSame(array(array()), $result->matches);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{[aei]');
        @Regex::matchAll($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();
        Regex::matchAll('{[aei]', 'abcdefghijklmnopqrstuvwxyz');
    }
}
