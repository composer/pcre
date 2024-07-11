<?php declare(strict_types=1);

namespace Composer\Pcre\PHPStan;

use PHPStan\Analyser\Scope;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\Type;

final class PregMatchFlags
{
    static public function getType(?Arg $flagsArg, Scope $scope): ?Type
    {
        if ($flagsArg === null) {
            return new ConstantIntegerType(PREG_UNMATCHED_AS_NULL);
        }

        $flagsType = $scope->getType($flagsArg->value);

        $constantScalars = $flagsType->getConstantScalarValues();
        if ($constantScalars === []) {
            return null;
        }

        $internalFlagsTypes = [];
        foreach ($flagsType->getConstantScalarValues() as $constantScalarValue) {
            if (!is_int($constantScalarValue)) {
                return null;
            }

            $internalFlagsTypes[] = $constantScalarValue | PREG_UNMATCHED_AS_NULL;
        }
        return TypeCombinator::union(...$internalFlagsTypes);
    }
}