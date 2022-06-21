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

final class MatchFirstResult
{
    /**
     * First found match (first group or match)
     *
     * @readonly
     * @var string|null
     */
    public $match;

    /**
     * @readonly
     * @var bool
     */
    public $matched;

    /**
     * @param 0|positive-int $count
     * @param array<string|null> $matches
     */
    public function __construct($count, array $matches)
    {
        $this->matched = (bool) $count;
        $this->match = $this->matched ? $matches[1] ?? $matches[0] : null;
    }
}
