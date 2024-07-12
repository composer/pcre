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

use PHPStan\Testing\TypeInferenceTestCase;

/**
 * Run with "vendor/bin/phpunit --testsuite phpstan"
 *
 * This is excluded by default to avoid side effects with the library tests
 */
class TypeInferenceTest extends TypeInferenceTestCase
{
    /**
     * @return iterable<mixed>
     */
    public function dataFileAsserts(): iterable
    {
        yield from $this->gatherAssertTypesFromDirectory(__DIR__ . '/nsrt');
    }

    /**
     * @dataProvider dataFileAsserts
     * @param mixed ...$args
     */
    public function testFileAsserts(
        string $assertType,
        string $file,
        ...$args
    ): void
    {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [
            'phar://' . __DIR__ . '/../../vendor/phpstan/phpstan/phpstan.phar/conf/bleedingEdge.neon',
            __DIR__ . '/../../extension.neon',
        ];
    }
}
