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
     * @param string $function
     * @param string $pattern
     * @return string|null
     */
    protected function formatPcreMessage($function, $pattern)
    {
        if (PHP_VERSION_ID>= 80000) {
            $error = 'Internal error';
        } elseif (PHP_VERSION_ID >= 70201) {
            $error = 'PREG_INTERNAL_ERROR';
        } else {
            // Ignoring here, some old versions return UNDEFINED_ERROR while some have been fixed
            return null;
        }

        return sprintf('%s: failed executing "%s": %s', $function, $pattern, $error);
    }
}
