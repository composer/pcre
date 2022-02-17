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

class IsMatchTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_match()';
    }

    public function testSuccess(): void
    {
        $result = Regex::isMatch('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz');
        self::assertTrue($result);
    }

    public function testFailure(): void
    {
        $result = Regex::isMatch('{abc}', 'def');
        self::assertFalse($result);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{(?P<m>[io])');
        @Regex::isMatch($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();
        Regex::isMatch('{(?P<m>[io])', 'abcdefghijklmnopqrstuvwxyz');
    }
}
