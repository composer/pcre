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

class PregTest extends BaseTestCase
{
    /**
     * @return void
     */
    public function testMatch()
    {
        $count = Preg::match('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz', $matches);
        $this->assertSame(1, $count);
        $this->assertSame(array(0 => 'i', 'm' => 'i', 1 => 'i'), $matches);
    }

    /**
     * @return void
     */
    public function testMatchNoRef()
    {
        $count = Preg::match('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz');
        $this->assertSame(1, $count);
    }

    /**
     * @return void
     */
    public function testMatchNoMatch()
    {
        $count = Preg::match('{abc}', 'def', $matches);
        $this->assertSame(0, $count);
        $this->assertSame(array(), $matches);
    }

    /**
     * @return void
     */
    public function testMatchWithBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $message = $this->formatPcreMessage('preg_match()', $pattern = '{abc');
        $this->doExpectException('Composer\Pcre\PcreException', $message);
        @Preg::match($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testMatchWithBadPatternTriggersWarningByDefault()
    {
        $this->doExpectWarning('preg_match(): No ending matching delimiter \'}\' found');
        Preg::match('{abc', 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testMatchThrowsIfEngineErrors()
    {
        $this->doExpectException('Composer\Pcre\PcreException', 'preg_match(): failed executing "/(?:\D+|<\d+>)*[!?]/": '.(PHP_VERSION_ID >= 80000 ? 'Backtrack limit exhausted' : 'PREG_BACKTRACK_LIMIT_ERROR'));
        Preg::match('/(?:\D+|<\d+>)*[!?]/', 'foobar foobar foobar');
    }

    /**
     * @return void
     */
    public function testMatchAll()
    {
        $count = Preg::matchAll('{[aei]}', 'abcdefghijklmnopqrstuvwxyz', $matches);
        $this->assertSame(3, $count);
        $this->assertSame(array(0 => array('a', 'e', 'i')), $matches);
    }

    /**
     * @return void
     */
    public function testMatchAllNoRef()
    {
        $count = Preg::matchAll('{[aei]}', 'abcdefghijklmnopqrstuvwxyz');
        $this->assertSame(3, $count);
    }

    /**
     * @return void
     */
    public function testMatchAllNoMatch()
    {
        $count = Preg::matchAll('{abc}', 'def', $matches);
        $this->assertSame(0, $count);
        $this->assertSame(array(array()), $matches);
    }

    /**
     * @return void
     */
    public function testMatchAllWithBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $message = $this->formatPcreMessage('preg_match_all()', $pattern = '{[aei]');
        $this->doExpectException('Composer\Pcre\PcreException', $message);
        @Preg::matchAll($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testMatchAllWithBadPatternTriggersWarningByDefault()
    {
        $this->doExpectWarning('preg_match_all(): No ending matching delimiter \'}\' found');
        Preg::matchAll('{[aei]', 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testReplace()
    {
        $result = Preg::replace('{(?P<m>d)}', 'e', 'abcd', -1, $count);
        $this->assertSame(1, $count);
        $this->assertSame('abce', $result);
    }

    /**
     * @return void
     */
    public function testReplaceNoRef()
    {
        $result = Preg::replace('{(?P<m>d)}', 'e', 'abcd', -1);
        $this->assertSame('abce', $result);
    }

    /**
     * @return void
     */
    public function testReplaceNoMatch()
    {
        $result = Preg::replace('{abc}', '123', 'def', -1, $count);
        $this->assertSame(0, $count);
        $this->assertSame('def', $result);
    }

    /**
     * @return void
     */
    public function testReplaceWithBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $message = $this->formatPcreMessage('preg_replace()', $pattern = '{(?P<m>d)');
        $this->doExpectException('Composer\Pcre\PcreException', $message);
        @Preg::replace($pattern, 'e', 'abcd', -1);
    }

    /**
     * @return void
     */
    public function testReplaceWithBadPatternTriggersWarningByDefault()
    {
        $this->doExpectWarning('preg_replace(): No ending matching delimiter \'}\' found');
        Preg::replace('{(?P<m>d)', 'e', 'abcd', -1);
    }

    /**
     * @return void
     */
    public function testReplaceThrowsWithSubjectArray()
    {
        $this->doExpectException('InvalidArgumentException', Preg::ARRAY_MSG);
        // @phpstan-ignore-next-line
        Preg::replace('{(?P<m>d)}', 'e', array('abcd'));
    }

    /**
     * @return void
     */
    public function testReplaceCallback()
    {
        $result = Preg::replaceCallback('{(?P<m>d)}', function ($match) {
            return '('.$match[0].')';
        }, 'abcd', -1, $count);
        $this->assertSame(1, $count);
        $this->assertSame('abc(d)', $result);
    }

    /**
     * @return void
     */
    public function testReplaceCallbackNoRef()
    {
        $result = Preg::replaceCallback('{(?P<m>d)}', function ($match) {
            return '('.$match[0].')';
        }, 'abcd');
        $this->assertSame('abc(d)', $result);
    }

    /**
     * @return void
     */
    public function testReplaceCallbackNoMatch()
    {
        $result = Preg::replaceCallback('{abc}', function ($match) {
            return '('.$match[0].')';
        }, 'def', -1, $count);
        $this->assertSame(0, $count);
        $this->assertSame('def', $result);
    }

    /**
     * @return void
     */
    public function testReplaceCallbackWithBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $message = $this->formatPcreMessage('preg_replace_callback()', $pattern = '{(?P<m>d)');
        $this->doExpectException('Composer\Pcre\PcreException', $message);

        @Preg::replaceCallback($pattern, function ($match) {
            return '('.$match[0].')';
        }, 'abcd');
    }

    /**
     * @return void
     */
    public function testReplaceCallbackWithBadPatternTriggersWarningByDefault()
    {
        $this->doExpectWarning('preg_replace_callback(): No ending matching delimiter \'}\' found');

        Preg::replaceCallback('{(?P<m>d)', function ($match) {
            return '('.$match[0].')';
        }, 'abcd');
    }

    /**
     * @return void
     */
    public function testReplaceCallbackThrowsWithSubjectArray()
    {
        $this->doExpectException('InvalidArgumentException', Preg::ARRAY_MSG);

        Preg::replaceCallback('{(?P<m>d)}', function ($match) {
            return '('.$match[0].')';
        }, array('abcd')); // @phpstan-ignore-line
    }

    /**
     * @requires PHP 7.0
     * @return void
     */
    public function testReplaceCallbackArray()
    {
        $result = Preg::replaceCallbackArray(array('{(?P<m>d)}' => function ($match) {
            return '('.$match[0].')';
        }), 'abcd', -1, $count);
        $this->assertSame(1, $count);
        $this->assertSame('abc(d)', $result);
    }

    /**
     * @requires PHP 7.0
     * @return void
     */
    public function testReplaceCallbackArrayNoRef()
    {
        $result = Preg::replaceCallbackArray(array('{(?P<m>d)}' => function ($match) {
            return '('.$match[0].')';
        }), 'abcd');
        $this->assertSame('abc(d)', $result);
    }

    /**
     * @requires PHP 7.0
     * @return void
     */
    public function testReplaceCallbackArrayNoMatch()
    {
        $result = Preg::replaceCallbackArray(array('{abc}' => function ($match) {
            return '('.$match[0].')';
        }), 'def', -1, $count);
        $this->assertSame(0, $count);
        $this->assertSame('def', $result);
    }

    /**
     * @requires PHP 7.0
     * @return void
     */
    public function testReplaceCallbackArrayWithBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $message = $this->formatPcreMessage('preg_replace_callback_array()', $pattern = '{(?P<m>d)');
        $this->doExpectException('Composer\Pcre\PcreException', $message);

        @Preg::replaceCallbackArray(array($pattern => function ($match) {
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

        Preg::replaceCallbackArray(array('{(?P<m>d)' => function ($match) {
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

        Preg::replaceCallbackArray(array('{(?P<m>d)}' => function ($match) {
            return '('.$match[0].')';
        }), array('abcd')); // @phpstan-ignore-line
    }

    /**
     * @return void
     */
    public function testSplit()
    {
        $result = Preg::split('{[\s,]+}', 'a, b, c');
        $this->assertSame(array('a', 'b', 'c'), $result);
    }

    /**
     * @return void
     */
    public function testSplitNoMatch()
    {
        $result = Preg::split('{[\s,]+}', 'abc');
        $this->assertSame(array('abc'), $result);
    }

    /**
     * @return void
     */
    public function testSplitWithBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $message = $this->formatPcreMessage('preg_split()', $pattern = '{[\s,]+');
        $this->doExpectException('Composer\Pcre\PcreException', $message);
        @Preg::split($pattern, 'a, b, c');
    }

    /**
     * @return void
     */
    public function testSplitWithBadPatternTriggersWarningByDefault()
    {
        $this->doExpectWarning('preg_split(): No ending matching delimiter \'}\' found');
        Preg::split('{[\s,]+', 'a, b, c');
    }

    /**
     * @return void
     */
    public function testGrep()
    {
        $result = Preg::grep('{[bc]}', array('a', 'b', 'c'));
        $this->assertSame(array(1 => 'b', 2 => 'c'), $result);
    }

    /**
     * @return void
     */
    public function testGrepNoMatch()
    {
        $result = Preg::grep('{[de]}', array('a', 'b', 'c'));
        $this->assertSame(array(), $result);
    }

    /**
     * @return void
     */
    public function testGrepWithBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $message = $this->formatPcreMessage('preg_grep()', $pattern = '{[de]');
        $this->doExpectException('Composer\Pcre\PcreException', $message);
        @Preg::grep($pattern, array('a', 'b', 'c'));
    }

    /**
     * @return void
     */
    public function testGrepWithBadPatternTriggersWarningByDefault()
    {
        $this->doExpectWarning('preg_grep(): No ending matching delimiter \'}\' found');
        Preg::grep('{[de]', array('a', 'b', 'c'));
    }
}
