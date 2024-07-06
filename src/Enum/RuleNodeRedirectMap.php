<?php

declare(strict_types=1);

namespace App\Enum;

use PhpParser\Node;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\TraitUse;
use Rector\Contract\Rector\RectorInterface;
use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;
use Rector\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector;
use Rector\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchMethodCallReturnTypeRector;
use Rector\Php71\Rector\Assign\AssignArrayToStringRector;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\Rector\Property\NestedAnnotationToAttributeRector;
use Rector\Php81\Rector\Class_\MyCLabsClassToEnumRector;
use Rector\Php82\Rector\Param\AddSensitiveParameterAttributeRector;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Removing\Rector\Class_\RemoveTraitUseRector;
use Rector\Transform\Rector\Attribute\AttributeKeyToClassConstFetchRector;
use Rector\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector;
use Rector\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector;

/**
 * Some rules are bound to higher level nodes, to have more context,
 * but the search should them for a node they actually change.
 *
 * E.g. the class const type rule hooks to class, but actually should be visible for class const
 */
final class RuleNodeRedirectMap
{
    /**
     * @var array<class-string<RectorInterface>, array<class-string<Node>>>
     */
    public const MAP = [
        AssignArrayToStringRector::class => [Assign::class],
        AddTypeToConstRector::class => [ClassConst::class],
        // attributes
        AttributeKeyToClassConstFetchRector::class => [AttributeGroup::class],
        AnnotationToAttributeRector::class => [AttributeGroup::class],
        ReturnTypeWillChangeRector::class => [AttributeGroup::class],
        NestedAnnotationToAttributeRector::class => [AttributeGroup::class],
        TypedPropertyFromJMSSerializerAttributeTypeRector::class => [AttributeGroup::class],
        AddSensitiveParameterAttributeRector::class => [AttributeGroup::class],
        AddOverrideAttributeToOverriddenMethodsRector::class => [AttributeGroup::class],
        MyCLabsClassToEnumRector::class => [Enum_::class],
        RemoveTraitUseRector::class => [TraitUse::class],
        ChangeAndIfToEarlyReturnRector::class => [If_::class],
        RenameParamToMatchTypeRector::class => [Param::class],
        RenameForeachValueVariableToMatchMethodCallReturnTypeRector::class => [Foreach_::class],
    ];
}
