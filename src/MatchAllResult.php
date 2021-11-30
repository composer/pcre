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

class MatchAllResult
{
    /**
     * @readonly
     * @var array<array<string|null>>
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
     * @param  array<array<string|null>> $matches
     * @return self
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
