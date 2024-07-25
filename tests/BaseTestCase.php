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
     * @param  class-string<\Throwable> $class
     */
    protected function doExpectException(string $class, ?string $message = null): void
    {
        $this->expectException($class);
        if (null !== $message) {
            $this->expectExceptionMessage($message);
        }
    }

    protected function doExpectWarning(string $message): void
    {
        $this->expectWarning();
        $this->expectWarningMessage($message);
    }

    protected function expectPcreEngineException(string $pattern): void
    {
        $error = PHP_VERSION_ID >= 80000 ? 'Backtrack limit exhausted' : 'PREG_BACKTRACK_LIMIT_ERROR';
        $this->expectPcreException($pattern, $error);
    }

    protected function expectPcreException(string $pattern, ?string $error = null): void
    {
        if (null === $this->pregFunction) {
            self::fail('Preg function name is missing');
        }

        if (null === $error) {
            // Only use a message if the error can be reliably determined
            if (PHP_VERSION_ID >= 80000) {
                $error = 'Internal error';
            } else {
                $error = 'PREG_INTERNAL_ERROR';
            }
        }

        $message = sprintf('%s: failed executing "%s": %s', $this->pregFunction, $pattern, $error);

        $this->doExpectException('Composer\Pcre\PcreException', $message);
    }

    protected function expectPcreWarning(?string $warning = null): void
    {
        if (null === $this->pregFunction) {
            self::fail('Preg function name is missing');
        }

        $warning = $warning !== null ? $warning : 'No ending matching delimiter \'}\' found';
        $message = sprintf('%s: %s', $this->pregFunction, $warning);
        $this->doExpectWarning($message);
    }
}
