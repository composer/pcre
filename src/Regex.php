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
     * @param non-empty-string $pattern
     * @param string $subject
     * @param int    $offset
     * @return bool
     */
    public static function isMatch($pattern, $subject, $offset = 0)
    {
        return (bool) Preg::match($pattern, $subject, $matches, 0, $offset);
    }

    /**
     * @param non-empty-string $pattern
     * @param string $subject
     * @param int    $flags PREG_UNMATCHED_AS_NULL, only available on PHP 7.2+
     * @param int    $offset
     * @return MatchResult
     */
    public static function match($pattern, $subject, $flags = 0, $offset = 0)
    {
        if (($flags & PREG_OFFSET_CAPTURE) !== 0) {
            throw new \InvalidArgumentException('PREG_OFFSET_CAPTURE is not supported as it changes the return type, use matchWithOffsets() instead');
        }

        $count = Preg::match($pattern, $subject, $matches, $flags, $offset);

        return new MatchResult($count, $matches);
    }

    /**
     * Runs preg_match with PREG_OFFSET_CAPTURE
     *
     * @param non-empty-string $pattern
     * @param string $subject
     * @param int    $flags PREG_UNMATCHED_AS_NULL, only available on PHP 7.2+
     * @param int    $offset
     * @return MatchWithOffsetsResult
     */
    public static function matchWithOffsets($pattern, $subject, $flags = 0, $offset = 0)
    {
        $count = Preg::matchWithOffsets($pattern, $subject, $matches, $flags, $offset);

        return new MatchWithOffsetsResult($count, $matches);
    }

    /**
     * @param non-empty-string $pattern
     * @param string $subject
     * @param int    $flags PREG_UNMATCHED_AS_NULL, only available on PHP 7.2+
     * @param int    $offset
     * @return MatchAllResult
     */
    public static function matchAll($pattern, $subject, $flags = 0, $offset = 0)
    {
        if (($flags & PREG_OFFSET_CAPTURE) !== 0) {
            throw new \InvalidArgumentException('PREG_OFFSET_CAPTURE is not supported as it changes the return type, use matchAllWithOffsets() instead');
        }

        if (($flags & PREG_SET_ORDER) !== 0) {
            throw new \InvalidArgumentException('PREG_SET_ORDER is not supported as it changes the return type');
        }

        $count = Preg::matchAll($pattern, $subject, $matches, $flags, $offset);

        return new MatchAllResult($count, $matches);
    }

    /**
     * Runs preg_match_all with PREG_OFFSET_CAPTURE
     *
     * @param non-empty-string $pattern
     * @param string $subject
     * @param int    $flags PREG_UNMATCHED_AS_NULL, only available on PHP 7.2+
     * @param int    $offset
     * @return MatchAllWithOffsetsResult
     */
    public static function matchAllWithOffsets($pattern, $subject, $flags = 0, $offset = 0)
    {
        $count = Preg::matchAllWithOffsets($pattern, $subject, $matches, $flags, $offset);

        return new MatchAllWithOffsetsResult($count, $matches);
    }
    /**
     * @param string|string[] $pattern
     * @param string|string[] $replacement
     * @param string          $subject
     * @param int             $limit
     * @return ReplaceResult
     */
    public static function replace($pattern, $replacement, $subject, $limit = -1)
    {
        $result = Preg::replace($pattern, $replacement, $subject, $limit, $count);

        return new ReplaceResult($count, $result);
    }

    /**
     * @param string|string[] $pattern
     * @param callable        $replacement
     * @param string          $subject
     * @param int             $limit
     * @param int             $flags PREG_OFFSET_CAPTURE or PREG_UNMATCHED_AS_NULL, only available on PHP 7.4+
     * @return ReplaceResult
     */
    public static function replaceCallback($pattern, $replacement, $subject, $limit = -1, $flags = 0)
    {
        $result = Preg::replaceCallback($pattern, $replacement, $subject, $limit, $count, $flags);

        return new ReplaceResult($count, $result);
    }

    /**
     * Available from PHP 7.0
     *
     * @param array<string, callable> $pattern
     * @param string $subject
     * @param int    $limit
     * @param int    $flags PREG_OFFSET_CAPTURE or PREG_UNMATCHED_AS_NULL, only available on PHP 7.4+
     * @return ReplaceResult
     */
    public static function replaceCallbackArray($pattern, $subject, $limit = -1, $flags = 0)
    {
        $result = Preg::replaceCallbackArray($pattern, $subject, $limit, $count, $flags);

        return new ReplaceResult($count, $result);
    }
}
