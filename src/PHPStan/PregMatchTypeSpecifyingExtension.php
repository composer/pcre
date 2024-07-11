<?php declare(strict_types=1);

namespace Composer\Pcre\PHPStan;

use Composer\Pcre\Preg;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\MethodReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Php\RegexArrayShapeMatcher;
use PHPStan\Type\StaticMethodTypeSpecifyingExtension;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\Type;

final class PregMatchTypeSpecifyingExtension implements StaticMethodTypeSpecifyingExtension, TypeSpecifierAwareExtension
{
    /**
     * @var TypeSpecifier
     */
    private $typeSpecifier;

    /**
     * @var RegexArrayShapeMatcher
     */
    private $regexShapeMatcher;

    public function __construct(RegexArrayShapeMatcher $regexShapeMatcher)
    {
        $this->regexShapeMatcher = $regexShapeMatcher;
    }

    public function setTypeSpecifier(TypeSpecifier $typeSpecifier): void
    {
        $this->typeSpecifier = $typeSpecifier;
    }

    public function getClass(): string
    {
        return Preg::class;
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection, StaticCall $node, TypeSpecifierContext $context): bool
    {
        return in_array($methodReflection->getName(), ['match', 'isMatch', 'matchStrictGroups', 'isMatchStrictGroups'], true) && !$context->null();
    }

    public function specifyTypes(MethodReflection $methodReflection, StaticCall $node, Scope $scope, TypeSpecifierContext $context): SpecifiedTypes
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

        $flagsType = PregMatchFlags::getType($flagsArg, $scope);
        if ($flagsType === null) {
            return new SpecifiedTypes();
        }
        $patternType = $scope->getType($patternArg->value);

        $matchedType = $this->regexShapeMatcher->matchType($patternType, $flagsType, TrinaryLogic::createFromBoolean($context->true()));
        if ($matchedType === null) {
            return new SpecifiedTypes();
        }

        if (
            in_array($methodReflection->getName(), ['matchStrictGroups', 'isMatchStrictGroups'], true)
            && count($matchedType->getConstantArrays()) === 1
        ) {
            $matchedType = $matchedType->getConstantArrays()[0];
            foreach ($matchedType as $type) {
                if ($type->containsNull()) {
                    // TODO trigger an error here that $methodReflection->getName() should not be used, or the pattern needs to make sure that $type->?? get key name is not nullable
                }
            }
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
            $node
        );
    }
}
