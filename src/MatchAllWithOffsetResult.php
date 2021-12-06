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

final class MatchAllWithOffsetResult
{
    /**
     * An array of match group => list of matches, every match being a pair of string matched + offset in bytes (or -1 if no match)
     *
     * @readonly
     * @var array<int|string, list<array{string|null, int}>>
     * @phpstan-var array<int|string, list<array{string|null, int<-1, max>}>>
     */
    public $matches;

    /**
     * @readonly
     * @var int
     */
    public $count;

    /**
     * @readonly
     * @var bool
     */
    public $matched;

    /**
     * @param  int $count
     * @param  array<int|string, list<array{string|null, int}>> $matches
     * @return self
     * @phpstan-param array<int|string, list<array{string|null, int<-1, max>}>> $matches
     */
    public static function create($count, $matches)
    {
        $result = new self;
        $result->matches = $matches;
        $result->matched = (bool) $count;
        $result->count = $count;

        return $result;
    }
}
