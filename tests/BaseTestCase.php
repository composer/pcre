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
        $error = function_exists('preg_last_error_msg') ? 'Backtrack limit exhausted' : 'PREG_BACKTRACK_LIMIT_ERROR';
        $this->expectPcreException($pattern, $error);
    }

    protected function expectPcreException(string $pattern, ?string $error = null): void
    {
        if (null === $this->pregFunction) {
            self::fail('Preg function name is missing');
        }

        if (null === $error) {
            // Only use a message if the error can be reliably determined
            if (function_exists('preg_last_error_msg')) {
                $error = 'Internal error';
            } else {
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

    protected function expectPcreWarning(?string $warning = null): void
    {
        if (null === $this->pregFunction) {
            self::fail('Preg function name is missing');
        }

        // this is a hack to make the tests work on 7.2/7.3
        // @see Preg::pregMatch
        if ($this->pregFunction === 'preg_match()' && PHP_VERSION_ID < 70400) {
            $this->pregFunction = 'preg_match_all()';
        }

        $warning = $warning !== null ? $warning : 'No ending matching delimiter \'}\' found';
        $message = sprintf('%s: %s', $this->pregFunction, $warning);
        $this->doExpectWarning($message);
    }
}
