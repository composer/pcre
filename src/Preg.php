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

class Preg
{
    /**
     * @param string|string[] $pattern
     * @param string|string[] $replacement
     * @param string|string[] $subject
     * @param int             $limit
     * @param int             $count Set by method
     * @return string|string[]
     */
    public static function filter($pattern, $replacement, $subject, $limit, &$count)
    {
        $error = null;
        set_error_handler(function ($errno, $errstr) use (&$error) {
            $error = $errstr;
            return false;
        });

        $result = preg_filter($pattern, $replacement, $subject, $limit, $count);
        restore_error_handler();
        // Keep phpstan happy
        if (!(is_string($result) || is_array($result)) || $error) {
            throw PcreException::fromFunction('preg_filter', $pattern);
        }

        return $result;
    }

    /**
     * @param string   $pattern
     * @param string[] $array
     * @param int      $flags
     * @return string[]
     */
    public static function grep($pattern, array $array, $flags = 0)
    {
        $result = preg_grep($pattern, $array, $flags);
        if ($result === false) {
            throw PcreException::fromFunction('preg_grep', $pattern);
        }

        return $result;
    }

    /**
     * @param string   $pattern
     * @param string   $subject
     * @param array<string|null> $matches Set by method
     * @param int      $flags PREG_UNMATCHED_AS_NULL, only available on PHP 7.2+
     * @param int      $offset
     * @return int
     */
    public static function match($pattern, $subject, &$matches, $flags = 0, $offset = 0)
    {
        $result = preg_match($pattern, $subject, $matches, $flags, $offset);

        if ($result === false) {
            throw PcreException::fromFunction('preg_match', $pattern);
        }

        return $result;
    }

    /**
     * @param string   $pattern
     * @param string   $subject
     * @param array<string|null> $matches Set by method
     * @param int      $flags PREG_UNMATCHED_AS_NULL, only available on PHP 7.2+
     * @param int      $offset
     * @return int
     */
    public static function matchAll($pattern, $subject, &$matches, $flags = 0, $offset = 0)
    {
        $result = preg_match_all($pattern, $subject, $matches, $flags, $offset);
        if ($result === false || $result === null) {
            throw PcreException::fromFunction('preg_match_all', $pattern);
        }

        return $result;
    }

    /**
     * @param string|string[] $pattern
     * @param string|string[] $replacement
     * @param string|string[] $subject
     * @param int             $limit
     * @param int             $count Set by method
     * @return string|string[]
     */
    public static function replace($pattern, $replacement, $subject, $limit, &$count)
    {
        $result = preg_replace($pattern, $replacement, $subject, $limit, $count);
        if ($result === null) {
            throw PcreException::fromFunction('preg_replace', $pattern);
        }

        return $result;
    }

    /**
     * @param string|string[] $pattern
     * @param callable        $replacement
     * @param string|string[] $subject
     * @param int             $limit
     * @param int             $count Set by method
     * @param int             $flags PREG_OFFSET_CAPTURE or PREG_UNMATCHED_AS_NULL, only available on PHP 7.4+
     * @return string|string[]
     */
    public static function replaceCallback($pattern, $replacement, $subject, $limit, &$count, $flags = 0)
    {
        if (PHP_VERSION_ID >= 70400) {
            $result = preg_replace_callback($pattern, $replacement, $subject, $limit, $count, $flags);
        } else {
            $result = preg_replace_callback($pattern, $replacement, $subject, $limit, $count);
        }
        if ($result === null) {
            throw PcreException::fromFunction('preg_replace_callback', $pattern);
        }

        return $result;
    }

    /**
     * Available from PHP 7.0
     *
     * @param array<string, callable> $pattern
     * @param string|string[] $subject
     * @param int             $limit
     * @param int             $count Set by method
     * @param int             $flags PREG_OFFSET_CAPTURE or PREG_UNMATCHED_AS_NULL, only available on PHP 7.4+
     * @return string|string[]
     */
    public static function replaceCallbackArray(array $pattern, $subject, $limit, &$count, $flags = 0)
    {
        if (PHP_VERSION_ID >= 70400) {
            $result = preg_replace_callback_array($pattern, $subject, $limit, $count, $flags);
        } else {
            $result = preg_replace_callback_array($pattern, $subject, $limit, $count);
        }
        if ($result === null) {
            $pattern = array_keys($pattern);
            throw PcreException::fromFunction('preg_replace_callback', $pattern);
        }

        return $result;
    }

    /**
     * @param string $pattern
     * @param string $subject
     * @param int $limit
     * @param int $flags
     * @return string[]
     */
    public static function split($pattern, $subject, $limit = -1, $flags = 0)
    {
        $result = preg_split($pattern, $subject, $limit, $flags);
        if ($result === false) {
            throw PcreException::fromFunction('preg_split', $pattern);
        }

        return $result;
    }
}
