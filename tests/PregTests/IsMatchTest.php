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

class IsMatchTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_match()';
    }

    public function testSuccess(): void
    {
        $result = Preg::isMatch('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz', $matches);
        self::assertSame(true, $result);
        self::assertSame(array(0 => 'i', 'm' => 'i', 1 => 'i'), $matches);
    }

    public function testSuccessNoRef(): void
    {
        $result = Preg::isMatch('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz');
        self::assertSame(true, $result);
    }

    public function testFailure(): void
    {
        $result = Preg::isMatch('{abc}', 'def', $matches);
        self::assertSame(false, $result);
        self::assertSame(array(), $matches);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{(?P<m>[io])');
        @Preg::isMatch($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();
        Preg::isMatch('{(?P<m>[io])', 'abcdefghijklmnopqrstuvwxyz');
    }
}
