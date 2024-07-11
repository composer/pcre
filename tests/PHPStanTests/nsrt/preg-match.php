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
        assertType('array{string, string|null}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{string, string|null}', $matches);

    if (Preg::match('/Price: (£|€)?\d+/', $s, $matches)) {
        assertType('array{0: string, 1?: string|null}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{0: string, 1?: string|null}', $matches);

    if (Preg::isMatch('/Price: (?<currency>£|€)\d+/', $s, $matches)) {
        assertType('array{0: string, currency: string|null, 1: string|null}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{0: string, currency: string|null, 1: string|null}', $matches);
}

function doMatchStrictGroups(string $s): void
{
    if (Preg::matchStrictGroups('/Price: /i', $s, $matches)) {
        assertType('array{string}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{string}', $matches);

    if (Preg::matchStrictGroups('/Price: (£|€)\d+/', $s, $matches)) {
        assertType('array{string, string}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{string, string}', $matches);

    if (Preg::isMatchStrictGroups('/Price: (£|€)?\d+/', $s, $matches)) {
        assertType('array{string, string}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{string, string}', $matches);
}

// disabled until https://github.com/phpstan/phpstan-src/pull/3185 can be resolved
//
//function identicalMatch(string $s): void
//{
//    if (Preg::match('/Price: /i', $s, $matches) === 1) {
//        assertType('array{string}', $matches);
//    } else {
//        assertType('array{}', $matches);
//    }
//    assertType('array{}|array{string}', $matches);
//}
//
//function equalMatch(string $s): void
//{
//    if (Preg::match('/Price: /i', $s, $matches) == 1) {
//        assertType('array{string}', $matches);
//    } else {
//        assertType('array{}', $matches);
//    }
//    assertType('array{}|array{string}', $matches);
//}
