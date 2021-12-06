<?php

/*
 * This file is part of composer/pcre.
 *
 * (c) Composer <https://github.com/composer>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Composer\Pcre\PregTests;

use Composer\Pcre\BaseTestCase;
use Composer\Pcre\Preg;

class IsMatchWithOffsetsTest extends BaseTestCase
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
        $result = Preg::isMatchWithOffsets('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz', $matches);
        self::assertSame(true, $result);
        self::assertSame(array(0 => array('i', 8), 'm' => array('i', 8), 1 => array('i', 8)), $matches);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $result = Preg::isMatchWithOffsets('{abc}', 'def', $matches);
        self::assertSame(false, $result);
        self::assertSame(array(), $matches);
    }

    /**
     * @return void
     */
    public function testBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->expectPcreException($pattern = '{(?P<m>[io])');
        @Preg::isMatchWithOffsets($pattern, 'abcdefghijklmnopqrstuvwxyz', $matches);
    }

    /**
     * @return void
     */
    public function testBadPatternTriggersWarningByDefault()
    {
        $this->expectPcreWarning();
        Preg::isMatchWithOffsets('{(?P<m>[io])', 'abcdefghijklmnopqrstuvwxyz', $matches);
    }
}
