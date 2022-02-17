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

class MatchTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_match()';
    }

    public function testSuccess(): void
    {
        $count = Preg::match('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz', $matches);
        self::assertSame(1, $count);
        self::assertSame(array(0 => 'i', 'm' => 'i', 1 => 'i'), $matches);
    }

    public function testSuccessWithInt(): void
    {
        $count = Preg::match('{(?P<m>\d)}', 123, $matches); // @phpstan-ignore-line
        self::assertSame(1, $count);
        self::assertSame(array(0 => '1', 'm' => '1', 1 => '1'), $matches);
    }

    public function testTypeErrorWithNull(): void
    {
        $this->expectException('TypeError');
        $count = Preg::match('{(?P<m>\d)}', null, $matches); // @phpstan-ignore-line
    }

    public function testSuccessNoRef(): void
    {
        $count = Preg::match('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz');
        self::assertSame(1, $count);
    }

    public function testFailure(): void
    {
        $count = Preg::match('{abc}', 'def', $matches);
        self::assertSame(0, $count);
        self::assertSame(array(), $matches);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{(?P<m>[io])');
        @Preg::match($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();
        Preg::match('{(?P<m>[io])', 'abcdefghijklmnopqrstuvwxyz');
    }

    public function testThrowsIfEngineErrors(): void
    {
        $this->expectPcreEngineException($pattern = '/(?:\D+|<\d+>)*[!?]/');
        Preg::match($pattern, 'foobar foobar foobar');
    }
}
