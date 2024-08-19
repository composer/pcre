<?php // lint >= 7.4

namespace PregMatchShapes;

use Composer\Pcre\Preg;
use Composer\Pcre\Regex;
use function PHPStan\Testing\assertType;

function (string $s): void {
    Preg::replaceCallback(
        $s,
        function ($matches) {
            assertType('array<int|string, string|null>', $matches);
            return '';
        },
        $s
    );

    Regex::replaceCallback(
        $s,
        function ($matches) {
            assertType('array<int|string, string|null>', $matches);
            return '';
        },
        $s
    );
};

function (string $s): void {
    Preg::replaceCallback(
        '|<p>(\s*)\w|',
        function ($matches) {
            assertType('array{string, string}', $matches);
            return '';
        },
        $s
    );
};

function (string $s): void {
    Preg::replaceCallback(
        '/(foo)?(bar)?(baz)?/',
        function ($matches) {
            assertType("array{string, 'foo'|null, 'bar'|null, 'baz'|null}", $matches);
            return '';
        },
        $s,
        -1,
        $count,
        PREG_UNMATCHED_AS_NULL
    );
};

function (string $s): void {
    Preg::replaceCallback(
        '/(foo)?(bar)?(baz)?/',
        function ($matches) {
            assertType("array{0: array{string, int<-1, max>}, 1?: array{''|'foo', int<-1, max>}, 2?: array{''|'bar', int<-1, max>}, 3?: array{'baz', int<-1, max>}}", $matches);
            return '';
        },
        $s,
        -1,
        $count,
        PREG_OFFSET_CAPTURE
    );
};

function (string $s): void {
    Preg::replaceCallback(
        '/(foo)?(bar)?(baz)?/',
        function ($matches) {
            assertType("array{array{string|null, int<-1, max>}, array{'foo'|null, int<-1, max>}, array{'bar'|null, int<-1, max>}, array{'baz'|null, int<-1, max>}}", $matches);
            return '';
        },
        $s,
        -1,
        $count,
        PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL
    );
};
