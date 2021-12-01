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
    public function testMatchNoMatch()
    {
        $result = Regex::match('{abc}', 'def');
        $this->assertInstanceOf('Composer\Pcre\MatchResult', $result);
        $this->assertFalse($result->matched);
        $this->assertSame(array(), $result->matches);
    }

    /**
     * @return void
     */
    public function testMatchThrowsWithBadPatternIfWarningsAreNotThrowing()
    {
        $this->doExpectException('Composer\Pcre\PcreException', 'preg_match(): failed executing "{abc": '.(PHP_VERSION_ID >= 80000 ? 'Internal error' : 'PREG_INTERNAL_ERROR'));
        @Regex::match('{abc', 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testMatchWithBadPatternTriggersWarningByDefault()
    {
        $this->doExpectWarning('preg_match(): No ending matching delimiter \'}\' found');
        Regex::match('{abc', 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testMatchThrowsIfEngineErrors()
    {
        $this->doExpectException('Composer\Pcre\PcreException', 'preg_match(): failed executing "/(?:\D+|<\d+>)*[!?]/": '.(PHP_VERSION_ID >= 80000 ? 'Backtrack limit exhausted' : 'PREG_BACKTRACK_LIMIT_ERROR'));
        Regex::match('/(?:\D+|<\d+>)*[!?]/', 'foobar foobar foobar');
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
    public function testMatchAllNoMatch()
    {
        $result = Regex::matchAll('{abc}', 'def');
        $this->assertInstanceOf('Composer\Pcre\MatchAllResult', $result);
        $this->assertFalse($result->matched);
        $this->assertSame(array(array()), $result->matches);
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
    public function testReplaceNoMatch()
    {
        $result = Regex::replace('{abc}', '123', 'def');
        $this->assertInstanceOf('Composer\Pcre\ReplaceResult', $result);
        $this->assertFalse($result->matched);
        $this->assertSame(0, $result->count);
        $this->assertSame('def', $result->result);
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

    /**
     * @return void
     */
    public function testReplaceCallbackNoMatch()
    {
        $result = Regex::replaceCallback('{abc}', function ($match) {
            return '('.$match[0].')';
        }, 'def');
        $this->assertInstanceOf('Composer\Pcre\ReplaceResult', $result);
        $this->assertFalse($result->matched);
        $this->assertSame(0, $result->count);
        $this->assertSame('def', $result->result);
    }

    /**
     * @param  class-string $class
     * @param  ?string $message
     * @return void
     */
    private function doExpectException($class, $message = null)
    {
        if (method_exists($this, 'expectException')) {
            $this->expectException($class);
            if ($message) {
                $this->expectExceptionMessage($message);
            }
        } else {
            // @phpstan-ignore-next-line
            $this->setExpectedException($class, $message);
        }
    }

    /**
     * @param  string $message
     * @return void
     */
    private function doExpectWarning($message)
    {
        if (method_exists($this, 'expectWarning')) {
            $this->expectWarning($message);
        } else {
            // @phpstan-ignore-next-line
            $this->setExpectedException('PHPUnit\Framework\Error\Warning', $message);
        }
    }
}
