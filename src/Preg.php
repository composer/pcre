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
    const ARRAY_MSG = '$subject as an array is not supported. You can use \'foreach\' instead.';

    /**
     * @param string   $pattern
     * @param string   $subject
     * @param array<string|null> $matches Set by method
     * @param int      $flags PREG_UNMATCHED_AS_NULL, only available on PHP 7.2+
     * @param int      $offset
     * @return int
     */
    public static function match($pattern, $subject, &$matches = null, $flags = 0, $offset = 0)
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
    public static function matchAll($pattern, $subject, &$matches = null, $flags = 0, $offset = 0)
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
     * @param string          $subject
     * @param int             $limit
     * @param int             $count Set by method
     * @return string
     */
    public static function replace($pattern, $replacement, $subject, $limit = -1, &$count = null)
    {
        if (is_array($subject)) { // @phpstan-ignore-line
            throw new \InvalidArgumentException(static::ARRAY_MSG);
        }

        $result = preg_replace($pattern, $replacement, $subject, $limit, $count);
        if ($result === null) {
            throw PcreException::fromFunction('preg_replace', $pattern);
        }

        return $result;
    }

    /**
     * @param string|string[] $pattern
     * @param callable        $replacement
     * @param string          $subject
     * @param int             $limit
     * @param int             $count Set by method
     * @param int             $flags PREG_OFFSET_CAPTURE or PREG_UNMATCHED_AS_NULL, only available on PHP 7.4+
     * @return string
     */
    public static function replaceCallback($pattern, $replacement, $subject, $limit = -1, &$count = null, $flags = 0)
    {
        if (is_array($subject)) { // @phpstan-ignore-line
            throw new \InvalidArgumentException(static::ARRAY_MSG);
        }

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
     * @param string $subject
     * @param int    $limit
     * @param int    $count Set by method
     * @param int    $flags PREG_OFFSET_CAPTURE or PREG_UNMATCHED_AS_NULL, only available on PHP 7.4+
     * @return string
     */
    public static function replaceCallbackArray(array $pattern, $subject, $limit = -1, &$count = null, $flags = 0)
    {
        if (is_array($subject)) { // @phpstan-ignore-line
            throw new \InvalidArgumentException(static::ARRAY_MSG);
        }

        if (PHP_VERSION_ID >= 70400) {
            $result = preg_replace_callback_array($pattern, $subject, $limit, $count, $flags);
        } else {
            $result = preg_replace_callback_array($pattern, $subject, $limit, $count);
        }
        if ($result === null) {
            $pattern = array_keys($pattern);
            throw PcreException::fromFunction('preg_replace_callback_array', $pattern);
        }

        return $result;
    }

    /**
     * @param string $pattern
     * @param string $subject
     * @param int    $limit
     * @param int    $flags
     * @return list<string>
     */
    public static function split($pattern, $subject, $limit = -1, $flags = 0)
    {
        $result = preg_split($pattern, $subject, $limit, $flags);
        if ($result === false) {
            throw PcreException::fromFunction('preg_split', $pattern);
        }

        return $result;
    }

    /**
     * @template T of string|\Stringable
     * @param string   $pattern
     * @param array<T> $array
     * @param int      $flags PREG_GREP_INVERT
     * @return array<T>
     */
    public static function grep($pattern, array $array, $flags = 0)
    {
        $result = preg_grep($pattern, $array, $flags);
        if ($result === false) {
            throw PcreException::fromFunction('preg_grep', $pattern);
        }

        return $result;
    }
}
