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
use Composer\Pcre\UnexpectedNullMatchException;

class MatchAllTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_match_all()';
    }

    public function testSuccess(): void
    {
        $count = Preg::matchAll('{[aei]}', 'abcdefghijklmnopqrstuvwxyz', $matches);
        self::assertSame(3, $count);
        self::assertSame(array(0 => array('a', 'e', 'i')), $matches);
    }

    public function testSuccessNoRef(): void
    {
        $count = Preg::matchAll('{[aei]}', 'abcdefghijklmnopqrstuvwxyz');
        self::assertSame(3, $count);
    }

    public function testFailure(): void
    {
        $count = Preg::matchAll('{abc}', 'def', $matches);
        self::assertSame(0, $count);
        self::assertSame(array(array()), $matches);
    }

    public function testSuccessStrictGroups(): void
    {
        $count = Preg::matchAllStrictGroups('{(?<m>\d)(?<matched>a)}', '3a', $matches);
        self::assertSame(1, $count);
        self::assertSame(array(0 => ['3a'], 'm' => ['3'], 1 => ['3'], 'matched' => ['a'], 2 => ['a']), $matches);
    }

    public function testFailStrictGroups(): void
    {
        self::expectException(UnexpectedNullMatchException::class);
        self::expectExceptionMessage('Pattern "{(?<m>\d)(?<unmatched>b)?}" had an unexpected unmatched group "unmatched", make sure the pattern always matches or use matchAll() instead.');
        // @phpstan-ignore composerPcre.unsafeStrictGroups
        Preg::matchAllStrictGroups('{(?<m>\d)(?<unmatched>b)?}', '123', $matches);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{[aei]');
        @Preg::matchAll($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();
        Preg::matchAll('{[aei]', 'abcdefghijklmnopqrstuvwxyz');
    }
}
