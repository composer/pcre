<?php

/*
 * This file is part of composer/pcre.
 *
 * (c) Composer <https://github.com/composer>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Composer\Pcre;

use PHPUnit\Framework\TestCase;

class RegexTest extends TestCase
{
    /**
     * @return void
     */
    public function testMatch()
    {
        $result = Regex::match('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz');
        $this->assertInstanceOf('Composer\Pcre\MatchResult', $result);
        $this->assertTrue($result->matched);
        $this->assertSame(array(0 => 'i', 'm' => 'i', 1 => 'i'), $result->matches);
    }

    /**
     * @return void
     */
    public function testMatchAll()
    {
        $result = Regex::matchAll('{[aei]}', 'abcdefghijklmnopqrstuvwxyz');
        $this->assertInstanceOf('Composer\Pcre\MatchAllResult', $result);
        $this->assertTrue($result->matched);
        $this->assertSame(3, $result->count);
        $this->assertSame(array(0 => array('a', 'e', 'i')), $result->matches);
    }

    /**
     * @return void
     */
    public function testReplace()
    {
        $result = Regex::replace('{(?P<m>d)}', 'e', 'abcd');
        $this->assertInstanceOf('Composer\Pcre\ReplaceResult', $result);
        $this->assertTrue($result->matched);
        $this->assertSame(1, $result->count);
        $this->assertSame('abce', $result->result);
    }

    /**
     * @return void
     */
    public function testReplaceCallback()
    {
        $result = Regex::replaceCallback('{(?P<m>d)}', function ($match) {
            return '('.$match[0].')';
        }, 'abcd');
        $this->assertInstanceOf('Composer\Pcre\ReplaceResult', $result);
        $this->assertTrue($result->matched);
        $this->assertSame(1, $result->count);
        $this->assertSame('abc(d)', $result->result);
    }
}
