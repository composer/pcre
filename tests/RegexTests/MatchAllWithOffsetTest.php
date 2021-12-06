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

class MatchAllWithOffsetTest extends BaseTestCase
{
    /**
     * This can be replaced with a setUp() method when appropriate
     *
     * @before
     * @return void
     */
    public function registerFunctionName()
    {
        $this->pregFunction = 'preg_match_all()';
    }

    /**
     * @return void
     */
    public function testSuccess()
    {
        $result = Regex::matchAllWithOffset('{[aei]}', 'abcdefghijklmnopqrstuvwxyz');
        self::assertInstanceOf('Composer\Pcre\MatchAllWithOffsetResult', $result);
        self::assertTrue($result->matched);
        self::assertSame(3, $result->count);
        self::assertSame(array(0 => array(array('a', 0), array('e', 4), array('i', 8))), $result->matches);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $result = Regex::matchAllWithOffset('{abc}', 'def');
        self::assertInstanceOf('Composer\Pcre\MatchAllWithOffsetResult', $result);
        self::assertFalse($result->matched);
        self::assertSame(array(array()), $result->matches);
    }
}
