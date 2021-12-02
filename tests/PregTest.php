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

class PregTest extends TestCase
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
        $count = Preg::match('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz', $matches);
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
    public function testMatchThrowsWithBadPatternIfWarningsAreNotThrowing()
    {
        $this->doExpectException('Composer\Pcre\PcreException', 'preg_match(): failed executing "{abc": '.(PHP_VERSION_ID >= 80000 ? 'Internal error' : (PHP_VERSION_ID >= 70201 ? 'PREG_INTERNAL_ERROR' : '' /* Ignoring here, some old versions return UNDEFINED_ERROR while some have been fixed */)));
        @Preg::match('{abc', 'abcdefghijklmnopqrstuvwxyz', $matches);
    }

    /**
     * @return void
     */
    public function testMatchWithBadPatternTriggersWarningByDefault()
    {
        $this->doExpectWarning('preg_match(): No ending matching delimiter \'}\' found');
        Preg::match('{abc', 'abcdefghijklmnopqrstuvwxyz', $matches);
    }

    /**
     * @return void
     */
    public function testMatchThrowsIfEngineErrors()
    {
        $this->doExpectException('Composer\Pcre\PcreException', 'preg_match(): failed executing "/(?:\D+|<\d+>)*[!?]/": '.(PHP_VERSION_ID >= 80000 ? 'Backtrack limit exhausted' : 'PREG_BACKTRACK_LIMIT_ERROR'));
        Preg::match('/(?:\D+|<\d+>)*[!?]/', 'foobar foobar foobar', $matches);
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
        $count = Preg::matchAll('{[aei]}', 'abcdefghijklmnopqrstuvwxyz', $matches);
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
        $result = Preg::replace('{(?P<m>d)}', 'e', 'abcd', -1, $count);
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
        }, 'abcd', -1, $count);
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
        if (method_exists($this, 'expectWarning')) {
            $this->expectWarning();
            $this->expectWarning($message);
        } else {
            // @phpstan-ignore-next-line
            $this->setExpectedException(class_exists('PHPUnit\Framework\Error\Warning') ? 'PHPUnit\Framework\Error\Warning' : 'PHPUnit_Framework_Error_Warning', $message);
        }
    }
}
