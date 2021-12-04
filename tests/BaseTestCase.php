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
    /** @var string */
    protected $pregFunction;

    /**
     * @param  class-string<\Exception> $class
     * @param  ?string $message
     * @return void
     */
    protected function doExpectException($class, $message = null)
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
    protected function doExpectWarning($message)
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
        if (!$this->pregFunction) {
            $this->fail('Preg function name is missing');
        }

        if (!$error) {
            // Only use a message if the error can be reliably determined
            if (PHP_VERSION_ID >= 80000) {
                $error = 'Internal error';
            } elseif (PHP_VERSION_ID >= 70201) {
                $error = 'PREG_INTERNAL_ERROR';
            }
        }

        if ($error) {
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
        if (!$this->pregFunction) {
            $this->fail('Preg function name is missing');
        }

        $warning = $warning ?: 'No ending matching delimiter \'}\' found';
        $message = sprintf('%s: %s', $this->pregFunction, $warning);
        $this->doExpectWarning($message);
    }
}
