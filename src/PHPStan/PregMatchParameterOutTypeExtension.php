<?php declare(strict_types = 1);

namespace Composer\Pcre\PHPStan;

use Composer\Pcre\Preg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\MethodParameterOutTypeExtension;
use PHPStan\Type\Php\RegexArrayShapeMatcher;
use PHPStan\Type\StaticMethodParameterOutTypeExtension;
use PHPStan\Type\Type;
use function in_array;
use function strtolower;

final class PregMatchParameterOutTypeExtension implements StaticMethodParameterOutTypeExtension
{

    private RegexArrayShapeMatcher $regexShapeMatcher;
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
            && $methodReflection->getName() === 'match'
            && $parameter->getName() === 'matches'
        ;
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

		$patternType = $scope->getType($patternArg->value);
		$flagsType = null;
		if ($flagsArg !== null) {
			$flagsType = $scope->getType($flagsArg->value);
		}

		return $this->regexShapeMatcher->matchType($patternType, $flagsType, TrinaryLogic::createMaybe());
	}

}
