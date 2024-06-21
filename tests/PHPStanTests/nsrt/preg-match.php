<?php

namespace PregMatchShapes;

use Composer\Pcre\Preg;
use function PHPStan\Testing\assertType;

function doMatch(string $s): void
{
    if (Preg::match('/Price: /i', $s, $matches)) {
        assertType('array{string}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{string}', $matches);

    if (Preg::match('/Price: (£|€)\d+/', $s, $matches)) {
        assertType('array{string, string}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{string, string}', $matches);
}

function identicalMatch(string $s): void
{
    if (Preg::match('/Price: /i', $s, $matches) === 1) {
        assertType('array{string}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{string}', $matches);
}

function equalMatch(string $s): void
{
    if (Preg::match('/Price: /i', $s, $matches) == 1) {
        assertType('array{string}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{string}', $matches);
}