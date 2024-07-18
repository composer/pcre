<?php

namespace PregMatchShapes;

use Composer\Pcre\Preg;
use Composer\Pcre\Regex;
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
        assertType('array{string, non-empty-string}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{string, non-empty-string}', $matches);

    if (Preg::match('/Price: (£|€)?\d+/', $s, $matches)) {
        assertType('array{string, non-empty-string|null}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{string, non-empty-string|null}', $matches);

    // passing the PREG_UNMATCHED_AS_NULL should change nothing compared to above as it is always set
    if (Preg::match('/Price: (£|€)?\d+/', $s, $matches, PREG_UNMATCHED_AS_NULL)) {
        assertType('array{string, non-empty-string|null}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{string, non-empty-string|null}', $matches);

    if (Preg::isMatch('/Price: (?<currency>£|€)\d+/', $s, $matches)) {
        assertType('array{0: string, currency: non-empty-string, 1: non-empty-string}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{0: string, currency: non-empty-string, 1: non-empty-string}', $matches);
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
        assertType('array{string, non-empty-string}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{string, non-empty-string}', $matches);

    if (Preg::isMatchStrictGroups('/Price: (?<test>£|€)\d+/', $s, $matches)) {
        assertType('array{0: string, test: non-empty-string, 1: non-empty-string}', $matches);
    } else {
        assertType('array{}', $matches);
    }
    assertType('array{}|array{0: string, test: non-empty-string, 1: non-empty-string}', $matches);
}

function doMatchStrictGroupsUnsafe(string $s): void
{
    if (Preg::isMatchStrictGroups('{Configure Command(?: *</td><td class="v">| *=> *)(.*)(?:</td>|$)}m', $s, $matches)) {
        // does not error because the match group might be empty but is not optional
        assertType('array{string, string}', $matches);
    }

    // should error as it is unsafe due to the optional group 1
    Regex::matchStrictGroups('{Configure Command(?: *</td><td class="v">| *=> *)(.*)?(?:</td>|$)}m', $s);

    if (Preg::matchAllStrictGroups('{((?<foo>.)?z)}m', $s, $matches)) {
        // should error as it is unsafe due to the optional group foo/2
    }

    if (Preg::isMatchStrictGroups('{'.$s.'}', $s, $matches)) {
        // should error as it is unsafe due not being introspectable with the dynamic string
    }
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
