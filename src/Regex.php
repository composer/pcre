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
     */
    public static function isMatch(string $pattern, string $subject, int $offset = 0): bool
    {
        return (bool) Preg::match($pattern, $subject, $matches, 0, $offset);
    }

    /**
     * @param non-empty-string $pattern
     * @param int    $flags PREG_UNMATCHED_AS_NULL is always set, no other flags are supported
     */
    public static function match(string $pattern, string $subject, int $flags = 0, int $offset = 0): MatchResult
    {
        self::checkOffsetCapture($flags);

        $count = Preg::match($pattern, $subject, $matches, $flags, $offset);

        return new MatchResult($count, $matches);
    }

    /**
     * @param non-empty-string $pattern
     * @param int    $flags PREG_UNMATCHED_AS_NULL is always set, no other flags are supported
     */
    public static function matchFirst(string $pattern, string $subject, int $flags = 0, int $offset = 0): MatchFirstResult
    {
        self::checkOffsetCapture($flags);

        $count = Preg::match($pattern, $subject, $matches, $flags, $offset);

        return new MatchFirstResult($count, $matches);
    }

    /**
     * @param array<non-empty-string> $patterns
     * @param int    $flags PREG_UNMATCHED_AS_NULL is always set, no other flags are supported
     */
    public static function matchFirstOf(array $patterns, string $subject, int $flags = 0, int $offset = 0): MatchFirstResult
    {
        self::checkOffsetCapture($flags);

        if ($patterns === []) {
            throw new \LogicException('You have provided an empty patterns.');
        }

        $match = null; // Make PHPStan/IDE happy
        foreach ($patterns as $pattern) {
            $match = self::matchFirst($pattern, $subject, $flags, $offset);

            if ($match->matched === true) {
                return $match;
            }
        }

        return $match;
    }

    /**
     * Runs preg_match with PREG_OFFSET_CAPTURE
     *
     * @param non-empty-string $pattern
     * @param int    $flags PREG_UNMATCHED_AS_NULL and PREG_MATCH_OFFSET are always set, no other flags are supported
     */
    public static function matchWithOffsets(string $pattern, string $subject, int $flags = 0, int $offset = 0): MatchWithOffsetsResult
    {
        $count = Preg::matchWithOffsets($pattern, $subject, $matches, $flags, $offset);

        return new MatchWithOffsetsResult($count, $matches);
    }

    /**
     * @param non-empty-string $pattern
     * @param int    $flags PREG_UNMATCHED_AS_NULL is always set, no other flags are supported
     */
    public static function matchAll(string $pattern, string $subject, int $flags = 0, int $offset = 0): MatchAllResult
    {
        self::checkOffsetCapture($flags);

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
     * @param int    $flags PREG_UNMATCHED_AS_NULL and PREG_MATCH_OFFSET are always set, no other flags are supported
     */
    public static function matchAllWithOffsets(string $pattern, string $subject, int $flags = 0, int $offset = 0): MatchAllWithOffsetsResult
    {
        $count = Preg::matchAllWithOffsets($pattern, $subject, $matches, $flags, $offset);

        return new MatchAllWithOffsetsResult($count, $matches);
    }
    /**
     * @param string|string[] $pattern
     * @param string|string[] $replacement
     * @param string          $subject
     */
    public static function replace($pattern, $replacement, $subject, int $limit = -1): ReplaceResult
    {
        $result = Preg::replace($pattern, $replacement, $subject, $limit, $count);

        return new ReplaceResult($count, $result);
    }

    /**
     * @param string|string[] $pattern
     * @param string          $subject
     * @param int             $flags PREG_OFFSET_CAPTURE is supported, PREG_UNMATCHED_AS_NULL is always set
     */
    public static function replaceCallback($pattern, callable $replacement, $subject, int $limit = -1, int $flags = 0): ReplaceResult
    {
        $result = Preg::replaceCallback($pattern, $replacement, $subject, $limit, $count, $flags);

        return new ReplaceResult($count, $result);
    }

    /**
     * @param array<string, callable> $pattern
     * @param string $subject
     * @param int    $flags PREG_OFFSET_CAPTURE is supported, PREG_UNMATCHED_AS_NULL is always set
     */
    public static function replaceCallbackArray(array $pattern, $subject, int $limit = -1, int $flags = 0): ReplaceResult
    {
        $result = Preg::replaceCallbackArray($pattern, $subject, $limit, $count, $flags);

        return new ReplaceResult($count, $result);
    }

    protected static function checkOffsetCapture(int $flags): void
    {
        if (($flags & PREG_OFFSET_CAPTURE) !== 0) {
            throw new \InvalidArgumentException('PREG_OFFSET_CAPTURE is not supported as it changes the return type, use matchWithOffsets() instead');
        }
    }
}
