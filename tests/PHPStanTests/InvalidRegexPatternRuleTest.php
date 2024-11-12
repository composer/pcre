<?php

/*
 * This file is part of composer/pcre.
 *
 * (c) Composer <https://github.com/composer>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Composer\Pcre\PHPStanTests;

use PHPStan\Testing\RuleTestCase;
use Composer\Pcre\PHPStan\InvalidRegexPatternRule;
use PHPStan\Type\Php\RegexArrayShapeMatcher;

/**
 * Run with "vendor/bin/phpunit --testsuite phpstan"
 *
 * This is excluded by default to avoid side effects with the library tests
 *
 * @extends RuleTestCase<InvalidRegexPatternRule>
 */
class InvalidRegexPatternRuleTest extends RuleTestCase
{
    protected function getRule(): \PHPStan\Rules\Rule
    {
        return new InvalidRegexPatternRule();
    }

    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/fixtures/invalid-patterns.php'], [
            [
                'Regex pattern is invalid: Compilation failed: missing closing parenthesis at offset 1',
                11,
            ],
            [
                'Regex pattern is invalid: Compilation failed: missing closing parenthesis at offset 1',
                13,
            ],
            [
                'Regex pattern is invalid: Compilation failed: missing closing parenthesis at offset 1',
                15,
            ],
            [
                'Regex pattern is invalid: Compilation failed: missing closing parenthesis at offset 1',
                17,
            ],
            [
                'Regex pattern is invalid: Compilation failed: missing closing parenthesis at offset 1',
                19,
            ],
            [
                'Regex pattern is invalid: Compilation failed: missing closing parenthesis at offset 1',
                21,
            ],
        ]);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [
            'phar://' . __DIR__ . '/../../vendor/phpstan/phpstan/phpstan.phar/conf/bleedingEdge.neon',
            __DIR__ . '/../../extension.neon',
        ];
    }
}
