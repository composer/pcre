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

class SplitTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_split()';
    }

    public function testSuccess(): void
    {
        $result = Preg::split('{[\s,]+}', 'a, b, c');
        self::assertSame(array('a', 'b', 'c'), $result);
    }

    public function testFailure(): void
    {
        $result = Preg::split('{[\s,]+}', 'abc');
        self::assertSame(array('abc'), $result);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{[\s,]+');
        @Preg::split($pattern, 'a, b, c');
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();
        Preg::split('{[\s,]+', 'a, b, c');
    }
}
