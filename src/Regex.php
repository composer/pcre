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
     * @param int    $flags PREG_UNMATCHED_AS_NULL, only available on PHP 7.2+
     * @param int    $offset
     * @return MatchResult
     */
    public static function match($pattern, $subject, $flags = 0, $offset = 0)
    {
        if (($flags & PREG_OFFSET_CAPTURE) !== 0) {
            throw new \InvalidArgumentException('PREG_OFFSET_CAPTURE is not supported as it changes the return type');
        }

        $count = preg_match($pattern, $subject, $matches, $flags, $offset);
        if ($count === false) {
            throw PcreException::fromFunction('preg_match', $pattern, $subject);
        }

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
        if (($flags & PREG_OFFSET_CAPTURE) !== 0) {
            throw new \InvalidArgumentException('PREG_OFFSET_CAPTURE is not supported as it changes the return type');
        }

        $count = preg_match_all($pattern, $subject, $matches, $flags, $offset);
        if ($count === false || $count === null) {
            throw PcreException::fromFunction('preg_match_all', $pattern, $subject);
        }

        return MatchAllResult::create($count, $matches);
    }

    /**
     * @param string $pattern
     * @param string $replacement
     * @param string $subject
     * @param int    $limit
     * @return ReplaceResult
     */
    public static function replace($pattern, $replacement, $subject, $limit = -1)
    {
        if (is_array($subject)) { // @phpstan-ignore-line
            throw new \InvalidArgumentException('$subject as an array is not supported as it changes the return type');
        }

        $result = preg_replace($pattern, $replacement, $subject, $limit, $count);
        if ($result === null) {
            throw PcreException::fromFunction('preg_replace', $pattern, $subject);
        }

        return ReplaceResult::create($count, $result);
    }

    /**
     * @param string   $pattern
     * @param callable $replacement
     * @param string   $subject
     * @param int      $limit
     * @param int      $flags PREG_OFFSET_CAPTURE or PREG_UNMATCHED_AS_NULL, only available on PHP 7.4+
     * @return ReplaceResult
     */
    public static function replaceCallback($pattern, $replacement, $subject, $limit = -1, $flags = 0)
    {
        if (is_array($subject)) { // @phpstan-ignore-line
            throw new \InvalidArgumentException('$subject as an array is not supported as it changes the return type');
        }

        if (PHP_VERSION_ID >= 70400) {
            $result = preg_replace_callback($pattern, $replacement, $subject, $limit, $count, $flags);
        } else {
            $result = preg_replace_callback($pattern, $replacement, $subject, $limit, $count);
        }
        if ($result === null) {
            throw PcreException::fromFunction('preg_replace_callback', $pattern, $subject);
        }

        return ReplaceResult::create($count, $result);
    }
}
