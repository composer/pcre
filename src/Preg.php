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
     * @param string $pattern
     * @param string $subject
     * @param array<string|null> &$matches
     * @param int    $flags PREG_UNMATCHED_AS_NULL, only available on PHP 7.2+
     * @param int    $offset
     * @return int Number of matches
     */
    public static function match($pattern, $subject, &$matches = null, $flags = 0, $offset = 0)
    {
        $result = Regex::match($pattern, $subject, $flags, $offset);
        $matches = $result->matches;

        return $result->matched ? 1 : 0;
    }

    /**
     * @param string $pattern
     * @param string $subject
     * @param array<string|null> &$matches
     * @param int    $flags PREG_UNMATCHED_AS_NULL, only available on PHP 7.2+
     * @param int    $offset
     * @return int Number of matches
     */
    public static function matchAll($pattern, $subject, &$matches = null, $flags = 0, $offset = 0)
    {
        $result = Regex::matchAll($pattern, $subject, $flags, $offset);
        $matches = $result->matches;

        return $result->count;
    }

    /**
     * @param string $pattern
     * @param string $replacement
     * @param string $subject
     * @param int    $limit
     * @param int    &$count
     * @return string
     */
    public static function replace($pattern, $replacement, $subject, $limit = -1, &$count = null)
    {
        $result = Regex::replace($pattern, $replacement, $subject, $limit);
        $count = $result->count;

        return $result->result;
    }

    /**
     * @param string   $pattern
     * @param callable $replacement
     * @param string   $subject
     * @param int      $limit
     * @param int      &$count
     * @param int      $flags PREG_OFFSET_CAPTURE or PREG_UNMATCHED_AS_NULL, only available on PHP 7.4+
     * @return string
     */
    public static function replaceCallback($pattern, $replacement, $subject, $limit = -1, &$count = null, $flags = 0)
    {
        $result = Regex::replaceCallback($pattern, $replacement, $subject, $limit);
        $count = $result->count;

        return $result->result;
    }
}
