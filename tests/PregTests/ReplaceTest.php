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

class ReplaceTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_replace()';
    }

    public function testSuccess(): void
    {
        $result = Preg::replace('{(?P<m>d)}', 'e', 'abcd', -1, $count);
        self::assertSame(1, $count);
        self::assertSame('abce', $result);
    }

    public function testSuccessNoRef(): void
    {
        $result = Preg::replace('{(?P<m>d)}', 'e', 'abcd', -1);
        self::assertSame('abce', $result);
    }

    public function testFailure(): void
    {
        $result = Preg::replace('{abc}', '123', 'def', -1, $count);
        self::assertSame(0, $count);
        self::assertSame('def', $result);
    }

    public function testBadPatternThrowsIfWarningsAreNotThrowing(): void
    {
        $this->expectPcreException($pattern = '{(?P<m>d)');
        @Preg::replace($pattern, 'e', 'abcd', -1);
    }

    public function testBadPatternTriggersWarningByDefault(): void
    {
        $this->expectPcreWarning();
        Preg::replace('{(?P<m>d)', 'e', 'abcd', -1);
    }

    public function testThrowsWithSubjectArray(): void
    {
        $this->doExpectException('InvalidArgumentException', Preg::ARRAY_MSG);
        // @phpstan-ignore-next-line
        Preg::replace('{(?P<m>d)}', 'e', array('abcd'));
    }
}
