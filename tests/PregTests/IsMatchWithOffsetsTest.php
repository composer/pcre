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

class IsMatchWithOffsetsTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_match()';
    }

    public function testSuccess(): void
    {
        $result = Preg::isMatchWithOffsets('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz', $matches);
        self::assertSame(true, $result);
        self::assertSame([0 => ['i', 8], 'm' => ['i', 8], 1 => ['i', 8]], $matches);
    }

    public function testFailure(): void
    {
        $result = Preg::isMatchWithOffsets('{abc}', 'def', $matches);
        self::assertSame(false, $result);
        self::assertSame([], $matches);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{(?P<m>[io])');
        @Preg::isMatchWithOffsets($pattern, 'abcdefghijklmnopqrstuvwxyz', $matches);
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();
        Preg::isMatchWithOffsets('{(?P<m>[io])', 'abcdefghijklmnopqrstuvwxyz', $matches);
    }
}
