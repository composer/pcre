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

class IsMatchAllWithOffsetsTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_match_all()';
    }

    public function testSuccess(): void
    {
        $result = Preg::isMatchAllWithOffsets('{[aei]}', 'abcdefghijklmnopqrstuvwxyz', $matches);
        self::assertSame(true, $result);
        self::assertSame(array(0 => array(array('a', 0), array('e', 4), array('i', 8))), $matches);
    }

    public function testFailure(): void
    {
        $result = Preg::isMatchAllWithOffsets('{abc}', 'def', $matches);
        self::assertSame(false, $result);
        self::assertSame(array(array()), $matches);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{[aei]');
        @Preg::isMatchAllWithOffsets($pattern, 'abcdefghijklmnopqrstuvwxyz', $matches);
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();
        Preg::isMatchAllWithOffsets('{[aei]', 'abcdefghijklmnopqrstuvwxyz', $matches);
    }
}
