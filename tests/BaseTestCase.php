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

class BaseTestCase extends TestCase
{
    /** @var string|null */
    protected $pregFunction = null;

    /**
     * @param  class-string<\Exception> $class
     * @param  ?string $message
     * @return void
     */
    protected function doExpectException($class, $message = null)
    {
        $this->expectException($class);
        if (null !== $message) {
            $this->expectExceptionMessage($message);
        }
    }

    /**
     * @param  string $message
     * @return void
     */
    protected function doExpectWarning($message)
    {
        $this->expectWarning();
        $this->expectWarningMessage($message);
    }

    /**
     * @param string $pattern
     * @return void
     */
    protected function expectPcreEngineException($pattern)
    {
        $error = PHP_VERSION_ID >= 80000 ? 'Backtrack limit exhausted' : 'PREG_BACKTRACK_LIMIT_ERROR';
        $this->expectPcreException($pattern, $error);
    }

    /**
     * @param string $pattern
     * @param string $error
     * @return void
     */
    protected function expectPcreException($pattern, $error = null)
    {
        if (null === $this->pregFunction) {
            self::fail('Preg function name is missing');
        }

        if (null === $error) {
            // Only use a message if the error can be reliably determined
            if (PHP_VERSION_ID >= 80000) {
                $error = 'Internal error';
            } elseif (PHP_VERSION_ID >= 70201) {
                $error = 'PREG_INTERNAL_ERROR';
            }
        }

        if (null !== $error) {
            $message = sprintf('%s: failed executing "%s": %s', $this->pregFunction, $pattern, $error);
        } else {
            $message = null;
        }

        $this->doExpectException('Composer\Pcre\PcreException', $message);
    }

    /**
     * @param string $warning
     * @return void
     */
    protected function expectPcreWarning($warning = null)
    {
        if (null === $this->pregFunction) {
            self::fail('Preg function name is missing');
        }

        $warning = $warning !== null ? $warning : 'No ending matching delimiter \'}\' found';
        $message = sprintf('%s: %s', $this->pregFunction, $warning);
        $this->doExpectWarning($message);
    }
}
