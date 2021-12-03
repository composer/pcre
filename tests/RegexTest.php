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
    public function testMatchWithBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->doExpectException('Composer\Pcre\PcreException', 'preg_match(): failed executing "{abc": '.(PHP_VERSION_ID >= 80000 ? 'Internal error' : (PHP_VERSION_ID >= 70201 ? 'PREG_INTERNAL_ERROR' : '' /* Ignoring here, some old versions return UNDEFINED_ERROR while some have been fixed */)));
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
    public function testMatchAllWithBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->doExpectException('Composer\Pcre\PcreException');
        @Regex::matchAll('{[aei]', 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testMatchAllWithBadPatternTriggersWarningByDefault()
    {
        $this->doExpectWarning('preg_match_all(): No ending matching delimiter \'}\' found');
        Regex::matchAll('{[aei]', 'abcdefghijklmnopqrstuvwxyz');
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
    public function testReplaceWithBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->doExpectException('Composer\Pcre\PcreException');
        @Regex::replace('{(?P<m>d)', 'e', 'abcd');
    }

    /**
     * @return void
     */
    public function testReplaceWithBadPatternTriggersWarningByDefault()
    {
        $this->doExpectWarning('preg_replace(): No ending matching delimiter \'}\' found');
        Regex::replace('{(?P<m>d)', 'e', 'abcd');
    }

    /**
     * @return void
     */
    public function testReplaceThrowsWithSubjectArray()
    {
        $this->doExpectException('InvalidArgumentException', Preg::ARRAY_MSG);
        // @phpstan-ignore-next-line
        Regex::replace('{(?P<m>d)}', 'e', array('abcd'));
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
     * @return void
     */
    public function testReplaceCallbackWithBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->doExpectException('Composer\Pcre\PcreException');

        @Regex::replaceCallback('{(?P<m>d)', function ($match) {
            return '('.$match[0].')';
        }, 'abcd');
    }

    /**
     * @return void
     */
    public function testReplaceCallbackWithBadPatternTriggersWarningByDefault()
    {
        $this->doExpectWarning('preg_replace_callback(): No ending matching delimiter \'}\' found');

        Regex::replaceCallback('{(?P<m>d)', function ($match) {
            return '('.$match[0].')';
        }, 'abcd');
    }

    /**
     * @return void
     */
    public function testReplaceCallbackThrowsWithSubjectArray()
    {
        $this->doExpectException('InvalidArgumentException', Preg::ARRAY_MSG);

        Regex::replaceCallback('{(?P<m>d)}', function ($match) {
            return '('.$match[0].')';
        }, array('abcd')); // @phpstan-ignore-line
    }

    /**
     * @requires PHP 7.0
     * @return void
     */
    public function testReplaceCallbackArray()
    {
        $result = Regex::replaceCallbackArray(array('{(?P<m>d)}' => function ($match) {
            return '('.$match[0].')';
        }), 'abcd');
        $this->assertInstanceOf('Composer\Pcre\ReplaceResult', $result);
        $this->assertTrue($result->matched);
        $this->assertSame(1, $result->count);
        $this->assertSame('abc(d)', $result->result);
    }

    /**
     * @requires PHP 7.0
     * @return void
     */
    public function testReplaceCallbackArrayNoMatch()
    {
        $result = Regex::replaceCallbackArray(array('{abc}' => function ($match) {
            return '('.$match[0].')';
        }), 'def');
        $this->assertInstanceOf('Composer\Pcre\ReplaceResult', $result);
        $this->assertFalse($result->matched);
        $this->assertSame(0, $result->count);
        $this->assertSame('def', $result->result);
    }

    /**
     * @requires PHP 7.0
     * @return void
     */
    public function testReplaceCallbackArrayWithBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->doExpectException('Composer\Pcre\PcreException');

        @Regex::replaceCallbackArray(array('{(?P<m>d)' => function ($match) {
            return '('.$match[0].')';
        }), 'abcd');
    }

    /**
     * @requires PHP 7.0
     * @return void
     */
    public function testReplaceCallbackArrayWithBadPatternTriggersWarningByDefault()
    {
        $this->doExpectWarning('preg_replace_callback_array(): No ending matching delimiter \'}\' found');

        Regex::replaceCallbackArray(array('{(?P<m>d)' => function ($match) {
            return '('.$match[0].')';
        }), 'abcd');
    }

    /**
     * @requires PHP 7.0
     * @return void
     */
    public function testReplaceCallbackArrayThrowsWithSubjectArray()
    {
        $this->doExpectException('InvalidArgumentException', Preg::ARRAY_MSG);

        Regex::replaceCallbackArray(array('{(?P<m>d)}' => function ($match) {
            return '('.$match[0].')';
        }), array('abcd')); // @phpstan-ignore-line
    }

    /**
     * @return void
     */
    public function testIsMatch()
    {
        $result = Regex::isMatch('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz');
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testIsMatchNoMatch()
    {
        $result = Regex::isMatch('{abc}', 'def');
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testIsMatchBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->doExpectException('Composer\Pcre\PcreException');
        @Regex::isMatch('{(?P<m>[io])', 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testIsMatchWithBadPatternTriggersWarningByDefault()
    {
        $this->doExpectWarning('preg_match(): No ending matching delimiter \'}\' found');
        Regex::isMatch('{(?P<m>[io])', 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @param  class-string<\Exception> $class
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
        if (method_exists($this, 'expectWarning') && method_exists($this, 'expectWarningMessage')) {
            $this->expectWarning();
            $this->expectWarningMessage($message);
        } else {
            $class = class_exists('PHPUnit\Framework\Error\Warning') ? 'PHPUnit\Framework\Error\Warning' : 'PHPUnit_Framework_Error_Warning';
            // @phpstan-ignore-next-line
            $this->doExpectException($class, $message);
        }
    }
}
