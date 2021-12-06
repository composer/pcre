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

class MatchAllTest extends BaseTestCase
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
        $result = Regex::matchAll('{[aei]}', 'abcdefghijklmnopqrstuvwxyz');
        self::assertInstanceOf('Composer\Pcre\MatchAllResult', $result);
        self::assertTrue($result->matched);
        self::assertSame(3, $result->count);
        self::assertSame(array(0 => array('a', 'e', 'i')), $result->matches);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $result = Regex::matchAll('{abc}', 'def');
        self::assertInstanceOf('Composer\Pcre\MatchAllResult', $result);
        self::assertFalse($result->matched);
        self::assertSame(array(array()), $result->matches);
    }

    /**
     * @return void
     */
    public function testBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->expectPcreException($pattern = '{[aei]');
        @Regex::matchAll($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testBadPatternTriggersWarningByDefault()
    {
        $this->expectPcreWarning();
        Regex::matchAll('{[aei]', 'abcdefghijklmnopqrstuvwxyz');
    }
}
