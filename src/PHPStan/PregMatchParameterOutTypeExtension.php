<?php declare(strict_types=1);

namespace Composer\Pcre\PHPStan;

use Composer\Pcre\Preg;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Php\RegexArrayShapeMatcher;
use PHPStan\Type\StaticMethodParameterOutTypeExtension;
use PHPStan\Type\Type;

final class PregMatchParameterOutTypeExtension implements StaticMethodParameterOutTypeExtension
{
    /**
     * @var RegexArrayShapeMatcher
     */
    private $regexShapeMatcher;

    public function __construct(
        RegexArrayShapeMatcher $regexShapeMatcher
    )
    {
        $this->regexShapeMatcher = $regexShapeMatcher;
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection, ParameterReflection $parameter): bool
    {
        return
            $methodReflection->getDeclaringClass()->getName() === Preg::class
            && in_array($methodReflection->getName(), ['match', 'isMatch', 'matchStrictGroups', 'isMatchStrictGroups'], true)
            && $parameter->getName() === 'matches';
    }

    public function getParameterOutTypeFromStaticMethodCall(MethodReflection $methodReflection, StaticCall $methodCall, ParameterReflection $parameter, Scope $scope): ?Type
    {
        $args = $methodCall->getArgs();
        $patternArg = $args[0] ?? null;
        $matchesArg = $args[2] ?? null;
        $flagsArg = $args[3] ?? null;

        if (
            $patternArg === null || $matchesArg === null
        ) {
            return null;
        }

        $flagsType = PregMatchFlags::getType($flagsArg, $scope);
        if ($flagsType === null) {
            return null;
        }
        $patternType = $scope->getType($patternArg->value);

        return $this->regexShapeMatcher->matchType($patternType, $flagsType, TrinaryLogic::createMaybe());
    }

}
