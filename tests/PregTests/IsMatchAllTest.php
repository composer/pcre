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

class IsMatchAllTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_match_all()';
    }

    public function testSuccess(): void
    {
        $result = Preg::isMatchAll('{[aei]}', 'abcdefghijklmnopqrstuvwxyz', $matches);
        self::assertSame(true, $result);
        self::assertSame(array(0 => array('a', 'e', 'i')), $matches);
    }

    public function testSuccessNoRef(): void
    {
        $result = Preg::isMatchAll('{[aei]}', 'abcdefghijklmnopqrstuvwxyz');
        self::assertSame(true, $result);
    }

    public function testFailure(): void
    {
        $result = Preg::isMatchAll('{abc}', 'def', $matches);
        self::assertSame(false, $result);
        self::assertSame(array(array()), $matches);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{[aei]');
        @Preg::isMatchAll($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();
        Preg::isMatchAll('{[aei]', 'abcdefghijklmnopqrstuvwxyz');
    }
}
