<?php

namespace PregMatchShapes;

use Composer\Pcre\Preg;
use Composer\Pcre\Regex;
use function PHPStan\Testing\assertType;

function doMatch(string $s): void
{
    Preg::match('/(/i', $s, $matches);

    Regex::isMatch('/(/i', $s);

    Preg::grep('/(/i', [$s]);

    Preg::replaceCallback('/(/i', function ($match) { return ''; }, $s);

    Preg::replaceCallback(['/(/i', '{}'], function ($match) { return ''; }, $s);

    Preg::replaceCallbackArray(['/(/i' => function ($match) { return ''; }], $s);
}
