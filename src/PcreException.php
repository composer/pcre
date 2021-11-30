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

class PcreException extends \RuntimeException
{
    /**
     * @param  string $function
     * @param  string $pattern
     * @param  string $subject
     * @return self
     */
    public static function fromFunction($function, $pattern, $subject)
    {
        $code = preg_last_error();

        return new PcreException($function.' failed executing "'.$pattern.'": '.self::pcreLastErrorMessage($code), $code);
    }

    /**
     * @param  int $code
     * @return string
     */
    private static function pcreLastErrorMessage($code)
    {
        if (PHP_VERSION_ID >= 80000) {
            return preg_last_error_msg();
        }

        $constants = get_defined_constants(true);
        if (!isset($constants['pcre'])) {
            return 'UNDEFINED_ERROR';
        }

        $constants = array_filter($constants['pcre'], function ($key) {
            return substr($key, -6) == '_ERROR';
        }, ARRAY_FILTER_USE_KEY);

        /** @var string|false $error */
        $error = array_search($code, $constants, true);
        if (false === $error) {
            return 'UNDEFINED_ERROR';
        }

        return $error;
    }
}
