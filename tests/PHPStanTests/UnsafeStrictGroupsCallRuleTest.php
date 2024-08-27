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
use Composer\Pcre\PHPStan\UnsafeStrictGroupsCallRule;
use PHPStan\Type\Php\RegexArrayShapeMatcher;

/**
 * Run with "vendor/bin/phpunit --testsuite phpstan"
 *
 * This is excluded by default to avoid side effects with the library tests
 *
 * @extends RuleTestCase<UnsafeStrictGroupsCallRule>
 */
class UnsafeStrictGroupsCallRuleTest extends RuleTestCase
{
    protected function getRule(): \PHPStan\Rules\Rule
    {
        return new UnsafeStrictGroupsCallRule(self::getContainer()->getByType(RegexArrayShapeMatcher::class));
    }

    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/nsrt/preg-match.php'], [
            [
                'The matchStrictGroups call is unsafe as match group "1" is optional and may be null.',
                80,
            ],
            [
                'The matchAllStrictGroups call is unsafe as match groups "foo", "2" are optional and may be null.',
                82,
            ],
            [
                'The isMatchStrictGroups call is potentially unsafe as $matches\' type could not be inferred.',
                86,
            ],
            [
                'The isMatchAllStrictGroups call is unsafe as match groups "test", "1" are optional and may be null.',
                114
            ]
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
