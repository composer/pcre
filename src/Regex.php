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

class Regex
{
    /**
     * @param string $pattern
     * @param string $subject
     * @param int    $offset
     * @return bool
     */
    public static function isMatch($pattern, $subject, $offset = 0)
    {
        return (bool) Preg::match($pattern, $subject, $matches, 0, $offset);
    }

    /**
     * @param string $pattern
     * @param string $subject
     * @param int    $flags PREG_UNMATCHED_AS_NULL, only available on PHP 7.2+
     * @param int    $offset
     * @return MatchResult
     */
    public static function match($pattern, $subject, $flags = 0, $offset = 0)
    {
        $count = Preg::match($pattern, $subject, $matches, $flags, $offset);

        return MatchResult::create($count, $matches);
    }

    /**
     * @param string $pattern
     * @param string $subject
     * @param int    $flags PREG_UNMATCHED_AS_NULL, only available on PHP 7.2+
     * @param int    $offset
     * @return MatchAllResult
     */
    public static function matchAll($pattern, $subject, $flags = 0, $offset = 0)
    {
        $count = Preg::matchAll($pattern, $subject, $matches, $flags, $offset);

        return MatchAllResult::create($count, $matches);
    }

    /**
     * @param string|string[] $pattern
     * @param string|string[] $replacement
     * @param string|string[] $subject
     * @param int             $limit
     * @return ReplaceResult
     */
    public static function replace($pattern, $replacement, $subject, $limit = -1)
    {
        $result = Preg::replace($pattern, $replacement, $subject, $limit, $count);

        return ReplaceResult::create($count, $result);
    }

    /**
     * @param string|string[] $pattern
     * @param callable        $replacement
     * @param string|string[] $subject
     * @param int             $limit
     * @param int             $flags PREG_OFFSET_CAPTURE or PREG_UNMATCHED_AS_NULL, only available on PHP 7.4+
     * @return ReplaceResult
     */
    public static function replaceCallback($pattern, $replacement, $subject, $limit = -1, $flags = 0)
    {
        $result = Preg::replaceCallback($pattern, $replacement, $subject, $limit, $count, $flags);

        return ReplaceResult::create($count, $result);
    }
}
