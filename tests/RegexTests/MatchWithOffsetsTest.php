<?php

/*
 * This file is part of composer/pcre.
 *
 * (c) Composer <https://github.com/composer>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Composer\Pcre\RegexTests;

use Composer\Pcre\BaseTestCase;
use Composer\Pcre\Regex;

class MatchWithOffsetsTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_match()';
    }

    public function testSuccess(): void
    {
        $result = Regex::matchWithOffsets('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz');
        self::assertInstanceOf('Composer\Pcre\MatchWithOffsetsResult', $result);
        self::assertTrue($result->matched);
        self::assertSame(array(0 => array('i', 8), 'm' => array('i', 8), 1 => array('i', 8)), $result->matches);
    }

    public function testFailure(): void
    {
        $result = Regex::matchWithOffsets('{abc}', 'def');
        self::assertInstanceOf('Composer\Pcre\MatchWithOffsetsResult', $result);
        self::assertFalse($result->matched);
        self::assertSame(array(), $result->matches);
    }
}
