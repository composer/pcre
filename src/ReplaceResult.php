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

final class ReplaceResult
{
    /**
     * @readonly
     * @var string
     */
    public $result;

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
     * @param  string $result
     * @return self
     */
    public static function create($count, $result)
    {
        $replaceResult = new self;
        $replaceResult->count = $count;
        $replaceResult->matched = (bool) $count;
        $replaceResult->result = $result;

        return $replaceResult;
    }
}
