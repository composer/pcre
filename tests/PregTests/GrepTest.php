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

class GrepTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_grep()';
    }

    public function testSuccess(): void
    {
        $result = Preg::grep('{[bc]}', array('a', 'b', 'c'));
        self::assertSame(array(1 => 'b', 2 => 'c'), $result);
    }

    public function testFailure(): void
    {
        $result = Preg::grep('{[de]}', array('a', 'b', 'c'));
        self::assertSame(array(), $result);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{[de]');
        @Preg::grep($pattern, array('a', 'b', 'c'));
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();
        Preg::grep('{[de]', array('a', 'b', 'c'));
    }
}
