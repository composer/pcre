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

class IsMatchAllWithOffsetsTest extends BaseTestCase
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
        $result = Preg::isMatchAllWithOffsets('{[aei]}', 'abcdefghijklmnopqrstuvwxyz', $matches);
        self::assertSame(true, $result);
        self::assertSame(array(0 => array(array('a', 0), array('e', 4), array('i', 8))), $matches);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $result = Preg::isMatchAllWithOffsets('{abc}', 'def', $matches);
        self::assertSame(false, $result);
        self::assertSame(array(array()), $matches);
    }

    /**
     * @return void
     */
    public function testBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->expectPcreException($pattern = '{[aei]');
        @Preg::isMatchAllWithOffsets($pattern, 'abcdefghijklmnopqrstuvwxyz', $matches);
    }

    /**
     * @return void
     */
    public function testBadPatternTriggersWarningByDefault()
    {
        $this->expectPcreWarning();
        Preg::isMatchAllWithOffsets('{[aei]', 'abcdefghijklmnopqrstuvwxyz', $matches);
    }
}
