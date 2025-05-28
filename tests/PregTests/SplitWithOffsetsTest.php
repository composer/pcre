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

class SplitWithOffsetsTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_split()';
    }

    public function testSuccess(): void
    {
        $result = Preg::splitWithOffsets('{[\s,]+}', 'a, b, c');
        self::assertSame([['a', 0], ['b', 3], ['c', 6]], $result);
    }

    public function testFailure(): void
    {
        $result = Preg::splitWithOffsets('{[\s,]+}', 'abc');
        self::assertSame([['abc', 0]], $result);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{[\s,]+');
        @Preg::splitWithOffsets($pattern, 'a, b, c');
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();
        Preg::splitWithOffsets('{[\s,]+', 'a, b, c');
    }
}
