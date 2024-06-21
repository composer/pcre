<?php declare(strict_types = 1);

namespace Composer\Pcre\PHPStan;

use Composer\Pcre\Preg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\FunctionTypeSpecifyingExtension;
use PHPStan\Type\MethodTypeSpecifyingExtension;
use PHPStan\Type\Php\RegexArrayShapeMatcher;
use PHPStan\Type\StaticMethodTypeSpecifyingExtension;
use function in_array;
use function strtolower;

final class PregMatchTypeSpecifyingExtension implements StaticMethodTypeSpecifyingExtension, TypeSpecifierAwareExtension
{

	private TypeSpecifier $typeSpecifier;

    private RegexArrayShapeMatcher $regexShapeMatcher;

    public function __construct(
        RegexArrayShapeMatcher $regexShapeMatcher
    )
    {
        $this->regexShapeMatcher = $regexShapeMatcher;
    }


	public function setTypeSpecifier(TypeSpecifier $typeSpecifier): void
	{
		$this->typeSpecifier = $typeSpecifier;
	}

    public function getClass(): string {
        return Preg::class;
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection, StaticCall $node, TypeSpecifierContext $context) : bool
    {
        return $methodReflection->getName() === 'match' && !$context->null();
    }

    public function specifyTypes(MethodReflection $methodReflection, StaticCall $node, Scope $scope, TypeSpecifierContext $context) : SpecifiedTypes
    {
		$args = $node->getArgs();
		$patternArg = $args[0] ?? null;
		$matchesArg = $args[2] ?? null;
		$flagsArg = $args[3] ?? null;

		if (
			$patternArg === null || $matchesArg === null
		) {
			return new SpecifiedTypes();
		}

		$patternType = $scope->getType($patternArg->value);
		$flagsType = null;
		if ($flagsArg !== null) {
			$flagsType = $scope->getType($flagsArg->value);
		}

		$matchedType = $this->regexShapeMatcher->matchType($patternType, $flagsType, TrinaryLogic::createFromBoolean($context->true()));
		if ($matchedType === null) {
			return new SpecifiedTypes();
		}

		$overwrite = false;
		if ($context->false()) {
			$overwrite = true;
			$context = $context->negate();
		}

		return $this->typeSpecifier->create(
			$matchesArg->value,
			$matchedType,
			$context,
			$overwrite,
			$scope,
			$node,
		);
	}

}
