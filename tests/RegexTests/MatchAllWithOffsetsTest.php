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

class MatchAllWithOffsetsTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->pregFunction = 'preg_match_all()';
    }

    public function testSuccess(): void
    {
        $result = Regex::matchAllWithOffsets('{[aei]}', 'abcdefghijklmnopqrstuvwxyz');
        self::assertInstanceOf('Composer\Pcre\MatchAllWithOffsetsResult', $result);
        self::assertTrue($result->matched);
        self::assertSame(3, $result->count);
        self::assertSame(array(0 => array(array('a', 0), array('e', 4), array('i', 8))), $result->matches);
    }

    public function testFailure(): void
    {
        $result = Regex::matchAllWithOffsets('{abc}', 'def');
        self::assertInstanceOf('Composer\Pcre\MatchAllWithOffsetsResult', $result);
        self::assertFalse($result->matched);
        self::assertSame(array(array()), $result->matches);
    }
}
