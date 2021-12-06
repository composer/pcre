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
    /**
     * This can be replaced with a setUp() method when appropriate
     *
     * @before
     * @return void
     */
    public function registerFunctionName()
    {
        $this->pregFunction = 'preg_match()';
    }

    /**
     * @return void
     */
    public function testSuccess()
    {
        $result = Regex::matchWithOffsets('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz');
        self::assertInstanceOf('Composer\Pcre\MatchWithOffsetsResult', $result);
        self::assertTrue($result->matched);
        self::assertSame(array(0 => array('i', 8), 'm' => array('i', 8), 1 => array('i', 8)), $result->matches);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $result = Regex::matchWithOffsets('{abc}', 'def');
        self::assertInstanceOf('Composer\Pcre\MatchWithOffsetsResult', $result);
        self::assertFalse($result->matched);
        self::assertSame(array(), $result->matches);
    }
}
